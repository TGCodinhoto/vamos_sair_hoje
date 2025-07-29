<?php
require_once '../conexao.php';
require_once '../models/publicacao_model.php';
require_once '../models/atracao_model.php';
require_once '../models/endereco_model.php';
require_once '../models/evento_model.php';

// Inicializa os models
$publicacaoModel = new PublicacaoModel($conexao);
$atracaoModel = new AtracaoModel($conexao);
$enderecoModel = new EnderecoModel($conexao);
$eventoModel = new EventoModel($conexao);


// No início do arquivo, após as outras requisições
// 2. ROTEAMENTO DE REQUISIÇÕES (O "CÉREBRO" DO CONTROLLER)
// =================================================================

// Verifica o tipo de requisição HTTP (POST para submeter dados, GET para buscar)
$request_method = $_SERVER['REQUEST_METHOD'];

// --- SE A REQUISIÇÃO FOR POST (Formulário foi enviado) ---
if ($request_method === 'POST') {

    // Roteia a ação com base no campo oculto 'acao' do formulário
    $acao = $_POST['acao'] ?? 'criar'; // Se 'acao' não existir, assume que é 'criar'

    if ($acao === 'atualizar') {
        // --- LÓGICA DE ATUALIZAÇÃO ---
        try {
            // Chama o método de atualização do model, passando os dados do formulário e os arquivos
            $eventoModel->atualizarEvento($_POST, $_FILES);
            
            // Redireciona de volta para o formulário de edição com mensagem de sucesso
            header("Location: ../views/form_evento.php?editar=true&publicacao_id=" . $_POST['publicacao_id'] . "&msg=success");
            exit;

        } catch (Exception $e) {
            // Em caso de erro, redireciona de volta com a mensagem de erro
            error_log("Erro ao ATUALIZAR evento: " . $e->getMessage());
            header("Location: ../views/form_evento.php?editar=true&publicacao_id=" . $_POST['publicacao_id'] . "&msg=error&erro=" . urlencode($e->getMessage()));
            exit;
        }

    } elseif ($acao === 'excluir') {
        // --- LÓGICA DE EXCLUSÃO ---
        try {
            $publicacaoId = $_POST['publicacao_id'];
            $eventoModel->excluirPorPublicacaoId($publicacaoId);
            
            // Redireciona para a lista de eventos com mensagem de sucesso
            header("Location: ../views/listar_eventos.php?msg=delete_success");
            exit;

        } catch (Exception $e) {
            // Em caso de erro, redireciona com a mensagem de erro
            error_log("Erro ao EXCLUIR evento: " . $e->getMessage());
            header("Location: ../views/listar_eventos.php?msg=delete_error&erro=" . urlencode($e->getMessage()));
            exit;
        }

    } else {
        // --- LÓGICA DE CRIAÇÃO (DEFAULT) ---
        try {
            $conexao->beginTransaction();

            // 1. Processar uploads
            $nomeFoto1 = processarUpload($_FILES['foto1'] ?? null, 'uploads');
            $nomeFoto2 = processarUpload($_FILES['foto2'] ?? null, 'uploads');
            $nomeVideo = processarUpload($_FILES['video'] ?? null, 'uploads');

            // 2. Criar publicação
            $publicacaoId = $publicacaoModel->criar([
                'nome' => $_POST['nome'],
                'validade_inicial' => $_POST['validade-inicial'],
                'validade_final' => $_POST['validade-final'],
                'auditado' => isset($_POST['auditado']) ? 1 : 0,
                'foto1' => $nomeFoto1,
                'foto2' => $nomeFoto2,
                'video' => $nomeVideo,
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

            // 5. Criar evento
            $eventoModel->criar([
                'publicacao_id' => $publicacaoId,
                'tipo_evento_id' => $_POST['tipo-evento'],
                'formato_id' => $_POST['formato-evento'],
                'expectativa' => $_POST['expectativa'],
                'dia' => $_POST['dia-evento'],
                'hora' => $_POST['hora-evento'],
                'informacoes' => $_POST['infos-gerais'],
                'link_ingresso' => $_POST['link-compra'] ?? null,
                'duracao' => $_POST['duracao-evento'] ?? null
            ]);

            $conexao->commit();

            header("Location: ../views/form_evento.php?msg=success");
            exit;

        } catch (Exception $e) {
            if ($conexao->inTransaction()) {
                $conexao->rollBack();
            }
            error_log("Erro ao CADASTRAR evento: " . $e->getMessage());
            header("Location: ../views/form_evento.php?msg=error&erro=" . urlencode($e->getMessage()));
            exit;
        }
    }
}

function processarUpload($arquivo, $pasta)
{
    if (!isset($arquivo) || $arquivo['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $extensoesPermitidas = $pasta === 'uploads'
        ? ['jpg', 'jpeg', 'png']
        : ['mp4', 'mov', 'avi'];

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

function listarEventosCompletos()
{
    global $conexao;

    $stmt = $conexao->query("
        SELECT 
            p.publicacaoid,
            p.publicacaonome,
            p.publicacaofoto01,
            p.publicacaofoto02,
            p.publicacaovalidadein,
            p.publicacaovalidadeout,
            p.publicacaovideo,
            p.publicacaoauditada,
            p.publicacaopaga,

            e.eventodia,
            e.eventohora,
            e.eventoduracao,
            e.eventoexpectativa,
            e.eventoinformacao,
            e.eventolinkingresso,
            
            a.atracaotelefone,
            a.atracaowebsite,
            a.atracaotelefonewz,
            a.atracaoinstagram,
            a.atracaotictoc,
            
            te.tipoeventonome,
            fe.formatonome,
            
            en.enderecorua, 
            en.endereconumero, 
            en.enderecobairro, 
            en.enderecocep, 
            en.enderecocomplemento,
            c.cidadenome AS nome_cidade, 
            es.estadosigla,
            
            cl.classificacaonome,
            tp.tipopubliconome,
            s.segmentonome,
            cat.categorianome
            
        FROM 
            publicacao p
        JOIN evento e ON p.publicacaoid = e.publicacaoid
        JOIN atracao a ON p.publicacaoid = a.publicacaoid
        LEFT JOIN tipoevento te ON e.tipoeventoid = te.tipoeventoid
        LEFT JOIN formatoevento fe ON e.formatoid = fe.formatoid
        LEFT JOIN endereco en ON a.enderecoid = en.enderecoid
        LEFT JOIN cidade c ON en.cidadeid = c.cidadeid
        LEFT JOIN estado es ON c.estadoid = es.estadoid
        LEFT JOIN classificacaoetaria cl ON a.classificacaoid = cl.classificacaoid
        LEFT JOIN tipopublico tp ON a.tipopublicoid = tp.tipopublicoid
        LEFT JOIN segmento s ON a.segmentoid = s.segmentoid
        LEFT JOIN categoria cat ON a.categoriaid = cat.categoriaid
        ORDER BY p.publicacaoid DESC
    ");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function buscarEventoPorId($publicacao_id) {
    global $eventoModel;
    return $eventoModel->buscarEventoPorId($publicacao_id);
}
