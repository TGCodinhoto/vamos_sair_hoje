<?php

// ----- CÓDIGO PARA PODER FAZER A DEPURAÇÃO -----
// echo '<pre>';
// echo '<h2>Conteúdo de $_POST:</h2>';
// var_dump($_POST);

// echo '<h2>Conteúdo de $_FILES:</h2>';
// var_dump($_FILES);
// echo '</pre>';
// die(); // Interrompe a execução do script aqui para podermos ver os valores

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
}
function listarAnunciosCompletos()
{
    global $conexao;

    $publicacaoModel = new PublicacaoModel($conexao);
    return $publicacaoModel->listarTudo();
}
