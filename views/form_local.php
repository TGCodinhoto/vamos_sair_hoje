<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_log('=== INÍCIO FORM_LOCAL.PHP ===');
error_log('Status da sessão: ' . session_status());
error_log('Session ID: ' . session_id());

// Debug detalhado
error_log('============= DEBUG FORM_LOCAL.PHP =============');
error_log('Todas as variáveis de sessão:');
error_log(print_r($_SESSION, true));
error_log('userid: ' . (isset($_SESSION['userid']) ? $_SESSION['userid'] : 'não definido'));
error_log('usertipo: ' . (isset($_SESSION['usertipo']) ? $_SESSION['usertipo'] : 'não definido'));
error_log('POST data:');
error_log(print_r($_POST, true));
error_log('============= FIM DEBUG =============');

// Verifica se o usuário está logado e é do tipo 1 (adm) ou 2 (estabelecimento)
if (!isset($_SESSION['userid'])) {
    error_log('Redirecionando: userid não está definido na sessão');
    header("Location: ../views/login.php");
    exit();
}

// Converte para inteiro para garantir comparação correta
$tipo = intval($_SESSION['usertipo']);
if ($tipo !== 1 && $tipo !== 2) {
    error_log('Redirecionando: usertipo (' . $tipo . ') não é 1 nem 2');
    header("Location: ../views/login.php");
    exit();
}

require_once('../conexao.php');

$conexao = Conexao::getInstance();

// Função para buscar local por ID (apenas para visualização/edição)
function buscarLocalPorId($publicacao_id) {
    global $conexao;
    require_once('../models/local_model.php');
    
    $localModel = new LocalModel($conexao);
    return $localModel->buscarLocalPorId($publicacao_id);
}

// Obter dados para os dropdowns diretamente via SQL para evitar problemas com models
try {
    // Buscar cidades
    $cidades = $conexao->query("
        SELECT c.cidadeid, c.cidadenome, e.estadoid, e.estadosigla 
        FROM cidade c 
        JOIN estado e ON c.estadoid = e.estadoid 
        ORDER BY c.cidadenome
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Buscar estados
    $estados = $conexao->query("SELECT * FROM estado ORDER BY estadonome")->fetchAll(PDO::FETCH_ASSOC);

    // Buscar classificações etárias
    $classificacoes = $conexao->query("SELECT * FROM classificacaoetaria ORDER BY classificacaonome")->fetchAll(PDO::FETCH_ASSOC);

    // Buscar tipos de público
    $tiposPublico = $conexao->query("SELECT * FROM tipopublico ORDER BY tipopubliconome")->fetchAll(PDO::FETCH_ASSOC);

    // Buscar segmentos
    $segmentos = $conexao->query("SELECT * FROM segmento ORDER BY segmentonome")->fetchAll(PDO::FETCH_ASSOC);

    // Buscar categorias
    $categorias = $conexao->query("SELECT * FROM categoria ORDER BY categorianome")->fetchAll(PDO::FETCH_ASSOC);

    // Buscar tipos de local
    $tiposLocal = $conexao->query("SELECT * FROM tipolocal ORDER BY tipolocalnome")->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    error_log("Erro ao carregar dados do formulário: " . $e->getMessage());
    $cidades = [];
    $estados = [];
    $classificacoes = [];
    $tiposPublico = [];
    $segmentos = [];
    $categorias = [];
    $tiposLocal = [];
}

$mensagem = '';
$cor = 'green';

// Verificar se estamos editando um local
$edicao = false;
$local = null;

if (isset($_GET['editar']) && $_GET['editar'] == 'true' && isset($_GET['publicacao_id'])) {
  $publicacao_id = $_GET['publicacao_id'];
  $local = buscarLocalPorId($publicacao_id);
  $edicao = true;
}

if (isset($_GET['msg'])) {
  if ($_GET['msg'] === 'success') {
    $mensagem = $edicao ? "Local atualizado com sucesso!" : "Local cadastrado com sucesso!";
  } elseif ($_GET['msg'] === 'error') {
    $cor = 'red';
    $mensagem = isset($_GET['erro']) ? $_GET['erro'] : "Erro ao processar a solicitação.";
  }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>
        Cadastrar Local
    </title>
    <script src="https://cdn.tailwindcss.com">
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&amp;display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <style>
        #botoes {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen flex flex-col items-center justify-start p-4 sm:p-6 md:p-8">
    
    <!-- Pop-up de notificação -->
    <?php if (!empty($mensagem)): ?>
    <div id="notification" class="fixed top-4 right-4 z-50 bg-<?= $cor ?>-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2">
        <i class="fas fa-<?= $cor === 'green' ? 'check-circle' : 'exclamation-triangle' ?>"></i>
        <span><?= htmlspecialchars($mensagem) ?></span>
        <button onclick="document.getElementById('notification').style.display='none'" class="ml-4 text-white hover:text-gray-200">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <script>
        // Auto-hide notification after 5 seconds
        setTimeout(function() {
            const notification = document.getElementById('notification');
            if (notification) {
                notification.style.display = 'none';
            }
        }, 5000);
    </script>
    <?php endif; ?>

    <div class="flex justify-center space-x-4 mb-6" id="botoes">
        <a class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition flex items-center space-x-2"
            href="navegacao_forms.php">
            <i class="fas fa-arrow-left">
            </i>
            <span class="hidden sm:block">
                Voltar
            </span>
        </a>
        <a class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition flex items-center space-x-2"
            href="../index.php">
            <i class="fas fa-home">
            </i>
            <span class="hidden sm:block">
                Home
            </span>
        </a>
        <a class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition flex items-center space-x-2"
            href="../views/listar_local.php">

            <i class="fas fa-list">
            </i>
            <span class="hidden sm:block">
                Locais Cadastrados
            </span>
        </a>
    </div>
    <section class="w-full max-w-4xl bg-white rounded-lg shadow-md p-4 sm:p-8 space-y-8">
        <h1
            class="text-3xl sm:text-4xl md:text-5xl font-bold mb-8 text-center text-blue-600 font-montserrat leading-tight">
            <?= $edicao ? 'Editar Local' : 'Cadastrar Local' ?>
        </h1>
        <form action="../controllers/local_controller.php" class="space-y-8" enctype="multipart/form-data" id="local-form" method="POST">
            <?php if ($edicao): ?>
                <input type="hidden" name="acao" value="atualizar">
                <input type="hidden" name="publicacao_id" value="<?= htmlspecialchars($local['publicacaoid']) ?>">
                <input type="hidden" name="endereco_id" value="<?= htmlspecialchars($local['enderecoid']) ?>">
            <?php endif; ?>
            
            <fieldset class="border border-gray-300 rounded-lg p-4 sm:p-6 space-y-6">
                <div class="flex flex-col w-full">
                    <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="nome">
                        Nome
                    </label>
                    <input
                        class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                        id="nome" name="nome" placeholder="Nome do Local" required="" type="text" 
                        value="<?= $edicao ? htmlspecialchars($local['publicacaonome']) : '' ?>" />
                </div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 sm:gap-6">
                    <div class="flex flex-col w-full">
                        <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg"
                            for="validade-inicial">
                            Validade Inicial
                        </label>
                        <input
                            class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                            id="validade-inicial" name="validade-inicial" required="" type="date" 
                            value="<?= $edicao ? htmlspecialchars($local['publicacaovalidadein']) : '' ?>" />
                    </div>
                    <div class="flex flex-col w-full">
                        <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="validade-final">
                            Validade Final
                        </label>
                        <input
                            class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                            id="validade-final" name="validade-final" required="" type="date" 
                            value="<?= $edicao ? htmlspecialchars($local['publicacaovalidadeout']) : '' ?>" />
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <input class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" id="auditado"
                        name="auditado" type="checkbox" <?= ($edicao && $local['publicacaoauditada']) ? 'checked' : '' ?> />
                    <label class="font-medium text-gray-700 text-base sm:text-lg cursor-pointer" for="auditado">
                        Auditado
                    </label>
                </div>
                <div class="flex flex-col w-full space-y-2">
                    <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="foto1">
                        Foto 1
                    </label>
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-20 h-20 border border-gray-300 rounded-md flex items-center justify-center overflow-hidden bg-gray-50 shrink-0">
                            <img alt="Pré-visualização da Foto 1 do local, quadrado, 80 por 80 pixels, fundo cinza claro"
                                class="max-h-full max-w-full object-contain" id="preview-foto1"
                                src="https://placehold.co/80x80?text=Foto+1" />
                        </div>
                        <input accept="image/*"
                            class="border border-gray-300 rounded-md px-2 py-1 text-sm sm:text-base file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 w-full sm:w-auto"
                            id="foto1" name="foto1" required="" type="file" />
                    </div>
                </div>
                <div class="flex flex-col w-full space-y-2">
                    <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="foto2">
                        Foto 2
                    </label>
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-20 h-20 border border-gray-300 rounded-md flex items-center justify-center overflow-hidden bg-gray-50 shrink-0">
                            <img alt="Pré-visualização da Foto 2 do local, quadrado, 80 por 80 pixels, fundo cinza claro"
                                class="max-h-full max-w-full object-contain" id="preview-foto2"
                                src="https://placehold.co/80x80?text=Foto+2" />
                        </div>
                        <input accept="image/*"
                            class="border border-gray-300 rounded-md px-2 py-1 text-sm sm:text-base file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 w-full sm:w-auto"
                            id="foto2" name="foto2" type="file" />
                    </div>
                </div>
                <div class="flex flex-col w-full space-y-2">
                    <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="foto3">
                        Foto 3
                    </label>
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-20 h-20 border border-gray-300 rounded-md flex items-center justify-center overflow-hidden bg-gray-50 shrink-0">
                            <img alt="Pré-visualização da Foto 3 do local, quadrado, 80 por 80 pixels, fundo cinza claro"
                                class="max-h-full max-w-full object-contain" id="preview-foto3"
                                src="https://placehold.co/80x80?text=Foto+3" />
                        </div>
                        <input accept="image/*"
                            class="border border-gray-300 rounded-md px-2 py-1 text-sm sm:text-base file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 w-full sm:w-auto"
                            id="foto3" name="foto3" type="file" />
                    </div>
                </div>
                <div class="flex flex-col w-full space-y-2">
                    <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="foto4">
                        Foto 4
                    </label>
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-20 h-20 border border-gray-300 rounded-md flex items-center justify-center overflow-hidden bg-gray-50 shrink-0">
                            <img alt="Pré-visualização da Foto 4 do local, quadrado, 80 por 80 pixels, fundo cinza claro"
                                class="max-h-full max-w-full object-contain" id="preview-foto4"
                                src="https://placehold.co/80x80?text=Foto+4" />
                        </div>
                        <input accept="image/*"
                            class="border border-gray-300 rounded-md px-2 py-1 text-sm sm:text-base file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 w-full sm:w-auto"
                            id="foto4" name="foto4" type="file" />
                    </div>
                </div>
                <div class="flex flex-col w-full space-y-2">
                    <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="foto5">
                        Foto 5
                    </label>
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-20 h-20 border border-gray-300 rounded-md flex items-center justify-center overflow-hidden bg-gray-50 shrink-0">
                            <img alt="Pré-visualização da Foto 5 do local, quadrado, 80 por 80 pixels, fundo cinza claro"
                                class="max-h-full max-w-full object-contain" id="preview-foto5"
                                src="https://placehold.co/80x80?text=Foto+5" />
                        </div>
                        <input accept="image/*"
                            class="border border-gray-300 rounded-md px-2 py-1 text-sm sm:text-base file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 w-full sm:w-auto"
                            id="foto5" name="foto5" type="file" />
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <input class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        id="publicacao-pagamento" name="publicacao-pagamento" type="checkbox" 
                        <?= ($edicao && $local['publicacaopaga']) ? 'checked' : '' ?> />
                    <label class="font-medium text-gray-700 text-base sm:text-lg cursor-pointer"
                        for="publicacao-pagamento">
                        Publicação Pagamento
                    </label>
                </div>
                <div>
                    <input name="endereco_id" type="hidden" value="<?= $edicao ? htmlspecialchars($local['enderecoid']) : '' ?>" />
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="flex flex-col w-full">
                            <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="logradouro">
                                Rua
                            </label>
                            <input autocomplete="off"
                                class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                                id="logradouro" name="logradouro" placeholder="Rua" required="" type="text" 
                                value="<?= $edicao ? htmlspecialchars($local['enderecorua']) : '' ?>" />
                        </div>
                        <div class="flex flex-col w-full">
                            <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="bairro">
                                Bairro
                            </label>
                            <input autocomplete="off"
                                class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                                id="bairro" name="bairro" placeholder="Bairro" required="" type="text" 
                                value="<?= $edicao ? htmlspecialchars($local['enderecobairro']) : '' ?>" />
                        </div>
                        <div class="flex flex-col w-full">
                            <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="numero">
                                Número
                            </label>
                            <input autocomplete="off"
                                class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                                id="numero" name="numero" placeholder="Número" required="" type="text" 
                                value="<?= $edicao ? htmlspecialchars($local['endereconumero']) : '' ?>" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                        <div class="flex flex-col w-full">
                            <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="cidade">
                                Cidade
                            </label>
                            <select
                                class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                                id="cidade" name="cidade" required="">
                                <option disabled="" <?= !$edicao ? 'selected' : '' ?> value="">
                                    Selecione a cidade
                                </option>
                                <?php foreach ($cidades as $cidade): ?>
                                    <option 
                                        data-estadoid="<?= htmlspecialchars($cidade['estadoid']) ?>" 
                                        data-estadosigla="<?= htmlspecialchars($cidade['estadosigla']) ?>" 
                                        value="<?= htmlspecialchars($cidade['cidadeid']) ?>"
                                        <?= ($edicao && $local['cidadeid'] == $cidade['cidadeid']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cidade['cidadenome']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="flex flex-col w-full">
                            <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="estado">
                                Estado
                            </label>
                            <input autocomplete="off"
                                class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full bg-gray-100"
                                id="estado" name="estado_display" placeholder="--" readonly="" required="" type="text"
                                value="<?= $edicao ? htmlspecialchars($local['estadosigla']) : '' ?>" />
                            <input id="estado_id_hidden" name="estado" type="hidden" 
                                value="<?= $edicao ? htmlspecialchars($local['estadoid']) : '' ?>" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div class="flex flex-col w-full">
                            <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg"
                                for="complemento">
                                Complemento
                            </label>
                            <input autocomplete="off"
                                class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                                id="complemento" name="complemento" placeholder="Complemento" type="text" 
                                value="<?= $edicao ? htmlspecialchars($local['enderecocomplemento']) : '' ?>" />
                        </div>
                        <div class="flex flex-col w-full">
                            <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="cep">
                                CEP
                            </label>
                            <input autocomplete="off"
                                class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                                id="cep" name="cep" placeholder="CEP" required="" type="text" 
                                value="<?= $edicao ? htmlspecialchars($local['enderecocep']) : '' ?>" />
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex flex-col w-full">
                        <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="classificacao">
                            Classificação
                        </label>
                        <select
                            class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                            id="classificacao" name="classificacao" required="">
                            <option disabled="" <?= !$edicao ? 'selected' : '' ?> value="">
                                Selecione a classificação
                            </option>
                            <?php foreach ($classificacoes as $classificacao): ?>
                                <option value="<?= htmlspecialchars($classificacao['classificacaoid']) ?>"
                                    <?= ($edicao && $local['classificacaoid'] == $classificacao['classificacaoid']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($classificacao['classificacaonome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex flex-col w-full">
                        <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg"
                            for="tipo-publicacao">
                            Tipo de Público
                        </label>
                        <select
                            class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                            id="tipo-publicacao" name="tipo-publicacao" required="">
                            <option disabled="" <?= !$edicao ? 'selected' : '' ?> value="">
                                Selecione o tipo
                            </option>
                            <?php foreach ($tiposPublico as $tipoPublico): ?>
                                <option value="<?= htmlspecialchars($tipoPublico['tipopublicoid']) ?>"
                                    <?= ($edicao && $local['tipopublicoid'] == $tipoPublico['tipopublicoid']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($tipoPublico['tipopubliconome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex flex-col w-full">
                        <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="segmento">
                            Segmento
                        </label>
                        <select
                            class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                            id="segmento" name="segmento" required="">
                            <option disabled="" <?= !$edicao ? 'selected' : '' ?> value="">
                                Selecione o segmento
                            </option>
                            <?php foreach ($segmentos as $segmento): ?>
                                <option value="<?= htmlspecialchars($segmento['segmentoid']) ?>"
                                    <?= ($edicao && $local['segmentoid'] == $segmento['segmentoid']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($segmento['segmentonome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex flex-col w-full">
                        <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="categoria">
                            Categoria
                        </label>
                        <select
                            class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                            id="categoria" name="categoria" required="">
                            <option disabled="" <?= !$edicao ? 'selected' : '' ?> value="">
                                Selecione a categoria
                            </option>
                            <?php foreach ($categorias as $categoria): ?>
                                <option value="<?= htmlspecialchars($categoria['categoriaid']) ?>"
                                    <?= ($edicao && $local['categoriaid'] == $categoria['categoriaid']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($categoria['categorianome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex flex-col w-full">
                        <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="tipo-local">
                            Tipo de Local
                        </label>
                        <select
                            class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                            id="tipo-local" name="tipo-local" required="">
                            <option disabled="" <?= !$edicao ? 'selected' : '' ?> value="">
                                Selecione o tipo de local
                            </option>
                            <?php foreach ($tiposLocal as $tipoLocal): ?>
                                <option value="<?= htmlspecialchars($tipoLocal['tipolocalid']) ?>"
                                    <?= ($edicao && $local['tipolocalid'] == $tipoLocal['tipolocalid']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($tipoLocal['tipolocalnome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex flex-col w-full space-y-2">
                        <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="telefone">
                            Telefone Celular
                        </label>
                        <div
                            class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-4">
                            <input autocomplete="off"
                                class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                                id="telefone" name="telefone" placeholder="(XX) XXXXX-XXXX" type="tel" 
                                value="<?= $edicao ? htmlspecialchars($local['atracaotelefone']) : '' ?>" />
                            <div class="flex items-center space-x-2">
                                <input class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                    id="whatsapp" name="whatsapp" type="checkbox" 
                                    <?= ($edicao && $local['atracaotelefonewz']) ? 'checked' : '' ?> />
                                <label class="font-medium text-gray-700 text-base sm:text-lg cursor-pointer select-none"
                                    for="whatsapp">
                                    WhatsApp
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="flex flex-col w-full">
                        <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="instagram">
                            Instagram
                        </label>
                        <input autocomplete="off"
                            class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                            id="instagram" name="instagram" placeholder="@usuario" type="text" 
                            value="<?= $edicao ? htmlspecialchars($local['atracaoinstagram']) : '' ?>" />
                    </div>
                    <div class="flex flex-col w-full">
                        <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="tiktok">
                            Tiktok
                        </label>
                        <input autocomplete="off"
                            class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                            id="tiktok" name="tiktok" placeholder="@usuario" type="text" 
                            value="<?= $edicao ? htmlspecialchars($local['atracaotictoc']) : '' ?>" />
                    </div>
                    <div class="flex flex-col w-full">
                        <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="site">
                            Site
                        </label>
                        <input autocomplete="off"
                            class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                            id="site" name="site" placeholder="https://exemplo.com" type="url" 
                            value="<?= $edicao ? htmlspecialchars($local['atracaowebsite']) : '' ?>" />
                    </div>
                </div>
            </fieldset>
            <div class="flex justify-center">
                <button
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md px-10 py-2 transition focus:outline-none focus:ring-2 focus:ring-blue-500"
                    type="submit">
                    <?= $edicao ? 'Atualizar' : 'Cadastrar' ?>
                </button>
            </div>
        </form>
    </section>
    <script>
        // Preview Images
        const inputsFoto = document.querySelectorAll('input[type="file"]');

        inputsFoto.forEach(input => {
            input.addEventListener('change', function() {
                const previewId = `preview-${this.id}`;
                const previewElement = document.getElementById(previewId);
                const file = this.files[0];

                if (file) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        previewElement.src = e.target.result;
                    }

                    reader.readAsDataURL(file);
                } else {
                    // Reseta a pré-visualização se o usuário cancelar a seleção
                    previewElement.src = `https://placehold.co/80x80?text=${previewId.replace('preview-', 'Foto+')}`;
                }
            });
        });



        // Estado e Cidade
        const cidadeSelect = document.getElementById('cidade');
        const estadoInput = document.getElementById('estado');
        const estadoIdHidden = document.getElementById('estado_id_hidden');

        cidadeSelect.addEventListener('change', () => {
            const selectedOption = cidadeSelect.options[cidadeSelect.selectedIndex];
            const estadoSigla = selectedOption.getAttribute('data-estadosigla') || '';
            const estadoId = selectedOption.getAttribute('data-estadoid') || '';
            estadoInput.value = estadoSigla;
            estadoIdHidden.value = estadoId;
        });
    </script>
</body>

</html>