<?php
require_once '../conexao.php';
require_once '../models/publicacao_model.php';
require_once '../models/atracao_model.php';
require_once '../models/endereco_model.php';
require_once '../models/local_model.php';

// Inicializa os models
$publicacaoModel = new PublicacaoModel($conexao);
$atracaoModel = new AtracaoModel($conexao);
$enderecoModel = new EnderecoModel($conexao);
$localModel = new LocalModel($conexao);

// ROTEAMENTO DE REQUISIÇÕES
$request_method = $_SERVER['REQUEST_METHOD'];

// --- SE A REQUISIÇÃO FOR POST (Formulário foi enviado) ---
if ($request_method === 'POST') {

    // Roteia a ação com base no campo oculto 'acao' do formulário
    $acao = $_POST['acao'] ?? 'criar'; // Se 'acao' não existir, assume que é 'criar'

    if ($acao === 'atualizar') {
        // --- LÓGICA DE ATUALIZAÇÃO ---
        try {
            // Processar uploads de imagens para a atualização
            $dados = $_POST;
            
            // Processar fotos 1 e 2 (publicacao)
            $foto1 = processarUpload($_FILES['foto1'] ?? null, 'uploads');
            $foto2 = processarUpload($_FILES['foto2'] ?? null, 'uploads');
            
            // Processar fotos 3, 4 e 5 (local)
            $foto3 = processarUpload($_FILES['foto3'] ?? null, 'uploads');
            $foto4 = processarUpload($_FILES['foto4'] ?? null, 'uploads');
            $foto5 = processarUpload($_FILES['foto5'] ?? null, 'uploads');
            
            // Adicionar as fotos processadas aos dados
            if ($foto1) $dados['foto01'] = $foto1;
            if ($foto2) $dados['foto02'] = $foto2;
            if ($foto3) $dados['foto03'] = $foto3;
            if ($foto4) $dados['foto04'] = $foto4;
            if ($foto5) $dados['foto05'] = $foto5;
            
            // Chama o método de atualização do model
            $localModel->atualizarLocal($dados);
            
            // Redireciona de volta para o formulário de edição com mensagem de sucesso
            header("Location: ../views/form_local.php?editar=true&publicacao_id=" . $_POST['publicacao_id'] . "&msg=success");
            exit;

        } catch (Exception $e) {
            // Em caso de erro, redireciona de volta com a mensagem de erro
            error_log("Erro ao ATUALIZAR local: " . $e->getMessage());
            header("Location: ../views/form_local.php?editar=true&publicacao_id=" . $_POST['publicacao_id'] . "&msg=error&erro=" . urlencode($e->getMessage()));
            exit;
        }

    } elseif ($acao === 'excluir') {
        // --- LÓGICA DE EXCLUSÃO ---
        try {
            $publicacaoId = $_POST['publicacao_id'];
            $localModel->excluirPorPublicacaoId($publicacaoId);
            
            // Redireciona para a lista de locais com mensagem de sucesso
            header("Location: ../views/listar_eventos.php?msg=delete_success");
            exit;

        } catch (Exception $e) {
            // Em caso de erro, redireciona com a mensagem de erro
            error_log("Erro ao EXCLUIR local: " . $e->getMessage());
            header("Location: ../views/listar_eventos.php?msg=delete_error&erro=" . urlencode($e->getMessage()));
            exit;
        }

        } else {
        // --- LÓGICA DE CRIAÇÃO (DEFAULT) ---
        try {
            $conexao->beginTransaction();

            // Debug: Verificar dados recebidos
            error_log("Dados recebidos no local_controller:");
            error_log("Cidade: " . ($_POST['cidade'] ?? 'não definido'));
            error_log("Estado: " . ($_POST['estado'] ?? 'não definido'));

            // Verificar se a cidade existe antes de tentar criar o endereço
            if (empty($_POST['cidade'])) {
                throw new Exception("Cidade não foi selecionada.");
            }

            $stmt = $conexao->prepare("SELECT cidadeid FROM cidade WHERE cidadeid = :cidade_id");
            $stmt->bindParam(':cidade_id', $_POST['cidade']);
            $stmt->execute();
            
            if ($stmt->rowCount() === 0) {
                throw new Exception("Cidade selecionada não existe no banco de dados. ID: " . $_POST['cidade']);
            }

            // 1. Processar uploads de imagens
            // Fotos 1 e 2 vão para tabela publicacao
            $nomeFoto1 = processarUpload($_FILES['foto1'] ?? null, 'uploads');
            $nomeFoto2 = processarUpload($_FILES['foto2'] ?? null, 'uploads');
            
            // Fotos 3, 4 e 5 vão para tabela local
            $nomeFoto3 = processarUpload($_FILES['foto3'] ?? null, 'uploads');
            $nomeFoto4 = processarUpload($_FILES['foto4'] ?? null, 'uploads');
            $nomeFoto5 = processarUpload($_FILES['foto5'] ?? null, 'uploads');

            // Debug: Log das fotos processadas
            error_log("Fotos processadas:");
            error_log("Foto1: " . ($nomeFoto1 ?: 'null'));
            error_log("Foto2: " . ($nomeFoto2 ?: 'null'));
            error_log("Foto3: " . ($nomeFoto3 ?: 'null'));
            error_log("Foto4: " . ($nomeFoto4 ?: 'null'));
            error_log("Foto5: " . ($nomeFoto5 ?: 'null'));

            // 2. Criar publicação com foto1 e foto2
            $publicacaoId = $publicacaoModel->criar([
                'nome' => $_POST['nome'],
                'validade_inicial' => $_POST['validade-inicial'],
                'validade_final' => $_POST['validade-final'],
                'auditado' => isset($_POST['auditado']) ? 1 : 0,
                'foto1' => $nomeFoto1,
                'foto2' => $nomeFoto2,
                'video' => null, // Locais não têm vídeo no formulário atual
                'paga' => isset($_POST['publicacao-pagamento']) ? 1 : 0,
                'user_id' => 1 // TODO: Substituir pelo ID do usuário logado
            ]);

            // 3. Criar endereço
            $enderecoId = $enderecoModel->criar([
                'logradouro' => $_POST['logradouro'],
                'numero' => $_POST['numero'],
                'bairro' => $_POST['bairro'],
                'cidade' => $_POST['cidade'],
                'estado' => $_POST['estado'],
                'cep' => $_POST['cep'],
                'complemento' => $_POST['complemento'] ?? null
            ]);

            // 4. Criar atração
            $atracaoModel->criar([
                'publicacao_id' => $publicacaoId,
                'endereco_id' => $enderecoId,
                'classificacao_id' => $_POST['classificacao'],
                'tipo_publico_id' => $_POST['tipo-publicacao'],
                'segmento_id' => $_POST['segmento'],
                'categoria_id' => $_POST['categoria'],
                'telefone' => $_POST['telefone'],
                'whatsapp' => isset($_POST['whatsapp']) ? 1 : 0,
                'website' => $_POST['site'] ?? null,
                'instagram' => $_POST['instagram'] ?? null,
                'tiktok' => $_POST['tiktok'] ?? null
            ]);

            // 5. Criar entrada na tabela local com foto3, foto4 e foto5
            $localModel->criar([
                'publicacao_id' => $publicacaoId,
                'tipo_local_id' => $_POST['tipo-local'],
                'foto03' => $nomeFoto3,
                'foto04' => $nomeFoto4,
                'foto05' => $nomeFoto5
            ]);

            $conexao->commit();

            header("Location: ../views/form_local.php?msg=success");
            exit;

        } catch (Exception $e) {
            if ($conexao->inTransaction()) {
                $conexao->rollBack();
            }
            error_log("Erro ao CADASTRAR local: " . $e->getMessage());
            header("Location: ../views/form_local.php?msg=error&erro=" . urlencode($e->getMessage()));
            exit;
        }
    }
}

function processarUpload($arquivo, $pasta)
{
    if (!isset($arquivo) || $arquivo['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));

    if (!in_array($extensao, $extensoesPermitidas)) {
        throw new Exception("Extensão de arquivo não permitida.");
    }

    $nomeUnico = uniqid() . '.' . $extensao;
    $caminhoDestino = "../$pasta/" . $nomeUnico;

    if (!move_uploaded_file($arquivo['tmp_name'], $caminhoDestino)) {
        throw new Exception("Falha ao mover o arquivo enviado.");
    }

    return $nomeUnico;
}

function listarLocaisCompletos()
{
    global $localModel;
    return $localModel->listarLocaisCompletos();
}

function buscarLocalPorId($publicacao_id) {
    global $localModel;
    return $localModel->buscarLocalPorId($publicacao_id);
}