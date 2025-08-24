    
<?php


require_once '../conexao.php';
require_once '../models/publicacao_model.php';
require_once '../models/anunciante_model.php';

$publicacaoModel = new PublicacaoModel($conexao);
$anuncianteModel = new AnuncianteModel($conexao);

function processarUpload($arquivo, $pasta, $tiposPermitidos = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'mp4', 'mov', 'avi'])
{
    if (!isset($arquivo) || $arquivo['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));

    if (!in_array($extensao, $tiposPermitidos)) {
        throw new Exception("Extensão de arquivo não permitida: " . htmlspecialchars($extensao));
    }

    $nomeUnico = uniqid() . '.' . $extensao;
    $caminhoDestino = "../$pasta/" . $nomeUnico;

    if (!move_uploaded_file($arquivo['tmp_name'], $caminhoDestino)) {
        throw new Exception("Falha ao mover o arquivo enviado.");
    }

    return $nomeUnico;
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? 'criar';

    if ($acao === 'criar') {
        try {
            $conexao->beginTransaction();

            $nomeFoto1 = processarUpload($_FILES['foto1'] ?? null, 'uploads');
            $nomeFoto2 = processarUpload($_FILES['foto2'] ?? null, 'uploads');
            $nomeVideo = processarUpload($_FILES['video1'] ?? null, 'uploads');
            $nomeBanner = processarUpload($_FILES['banner'] ?? null, 'uploads');

            $dadosPublicacao = [
                'user_id'          => 1, // TODO: Substituir pelo ID do usuário logado
                'nome'             => $_POST['nome'] ?? null,
                'validade_inicial' => $_POST['validade-inicial'] ?? null,
                'validade_final'   => $_POST['validade-final'] ?? null,
                'auditado'         => isset($_POST['auditado']) ? 1 : 0,
                'foto1'            => $nomeFoto1,
                'foto2'            => $nomeFoto2,
                'video'            => $nomeVideo,
                'paga'             => isset($_POST['publicacao-pagamento']) ? 1 : 0
            ];

            $publicacaoId = $publicacaoModel->criar($dadosPublicacao);

            if (!$publicacaoId) {
                throw new Exception("Falha ao criar a publicação, não foi retornado um ID.");
            }

            $dadosAnunciante = [
                'publicacaoid' => $publicacaoId,
                'banner'       => $nomeBanner
            ];

            $anuncianteModel->criar($dadosAnunciante);

            $conexao->commit();

            header("Location: ../views/form_anunciante.php?msg=success");
            exit;
        } catch (Exception $e) {
            if ($conexao->inTransaction()) {
                $conexao->rollBack();
            }
            error_log("Erro ao CADASTRAR anunciante: " . $e->getMessage());
            header("Location: ../views/form_anunciante.php?msg=error&erro=" . urlencode($e->getMessage()));
            exit;
        }
    }

    if ($acao === 'atualizar') {
        $publicacaoId = $_POST['publicacao_id'] ?? null;
        if ($publicacaoId) {
            try {
                $conexao->beginTransaction();

                // Busca dados atuais
                $dadosAtuais = $publicacaoModel->buscarPorId($publicacaoId);

                // Processa uploads se houver novos arquivos
                $nomeFoto1 = isset($_FILES['foto1']) && $_FILES['foto1']['error'] === UPLOAD_ERR_OK ? processarUpload($_FILES['foto1'], 'uploads') : null;
                $nomeFoto2 = isset($_FILES['foto2']) && $_FILES['foto2']['error'] === UPLOAD_ERR_OK ? processarUpload($_FILES['foto2'], 'uploads') : null;
                $nomeVideo = isset($_FILES['video1']) && $_FILES['video1']['error'] === UPLOAD_ERR_OK ? processarUpload($_FILES['video1'], 'uploads') : null;
                $nomeBanner = isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK ? processarUpload($_FILES['banner'], 'uploads') : null;

                // Atualiza publicação (mantém valores atuais se não enviados)
                $dadosPublicacao = [
                    'nome' => $_POST['nome'] ?? $dadosAtuais['publicacaonome'],
                    'validade_inicial' => $_POST['validade-inicial'] ?? $dadosAtuais['publicacaovalidadein'],
                    'validade_final' => $_POST['validade-final'] ?? $dadosAtuais['publicacaovalidadeout'],
                    'auditado' => isset($_POST['auditado']) ? 1 : $dadosAtuais['publicacaoauditada'],
                    'foto1' => $nomeFoto1 ?? $dadosAtuais['publicacaofoto01'],
                    'foto2' => $nomeFoto2 ?? $dadosAtuais['publicacaofoto02'],
                    'video' => $nomeVideo ?? $dadosAtuais['publicacaovideo'],
                    'paga' => isset($_POST['publicacao-pagamento']) ? 1 : $dadosAtuais['publicacaopaga'],
                    'publicacao_id' => $publicacaoId
                ];
                $publicacaoModel->atualizar($dadosPublicacao);

                // Atualiza anunciante (banner)
                if ($nomeBanner) {
                    $stmt = $conexao->prepare("UPDATE anunciante SET anunciantebanner = :banner WHERE publicacaoid = :publicacaoid");
                    $stmt->execute([':banner' => $nomeBanner, ':publicacaoid' => $publicacaoId]);
                }

                $conexao->commit();
                header("Location: ../views/form_anunciante.php?editar=true&publicacao_id=$publicacaoId&msg=success");
                exit;
            } catch (Exception $e) {
                if ($conexao->inTransaction()) {
                    $conexao->rollBack();
                }
                error_log("Erro ao ATUALIZAR anunciante/publicação: " . $e->getMessage());
                header("Location: ../views/form_anunciante.php?editar=true&publicacao_id=$publicacaoId&msg=error&erro=" . urlencode($e->getMessage()));
                exit;
            }
        }
    }


    if ($acao === 'excluir') {
        $publicacaoId = $_POST['publicacaoid'] ?? null;
        if ($publicacaoId) {
            try {
                $conexao->beginTransaction();
                // Exclui anunciante primeiro (por causa da FK)
                $anuncianteModel->excluir($publicacaoId);
                // Exclui publicação
                $publicacaoModel->excluir($publicacaoId);
                $conexao->commit();
                header("Location: ../views/listar_anunciantes.php?msg=success");
                exit;
            } catch (Exception $e) {
                if ($conexao->inTransaction()) {
                    $conexao->rollBack();
                }
                error_log("Erro ao EXCLUIR anunciante/publicação: " . $e->getMessage());
                header("Location: ../views/listar_anunciantes.php?msg=error&erro=" . urlencode($e->getMessage()));
                exit;
            }
        }
    }
}
function listarAnunciosCompletos()
{
    global $conexao;

    $publicacaoModel = new PublicacaoModel($conexao);
    return $publicacaoModel->listarTudo();
}
