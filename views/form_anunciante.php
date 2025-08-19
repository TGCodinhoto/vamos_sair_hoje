<?php
require_once('../conexao.php');

// Função para buscar local por ID (apenas para visualização/edição)
function buscarLocalPorId($publicacao_id)
{
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
            href="../views/listar_eventos.php">
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
                    <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="video1">
                        Vídeo
                    </label>
                    <div class="flex items-center space-x-4">
                        <div class="w-40 h-28 border border-gray-300 rounded-md flex items-center justify-center overflow-hidden bg-gray-50 shrink-0">
                            <video aria-label="Pré-visualização do vídeo do local, retangular, 160 por 112 pixels, fundo cinza claro" class="max-h-full max-w-full object-contain" controls="" height="112" id="preview-video1" width="160">
                                <source src="https://placehold.co/160x112?text=Vídeo+placeholder.mp4" type="video/mp4" />
                                Seu navegador não suporta o elemento de vídeo.
                            </video>
                        </div>
                        <input accept="video/*" class="border border-gray-300 rounded-md px-2 py-1 text-sm sm:text-base file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 w-full sm:w-auto" id="video1" name="video1" type="file" />
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
            </fieldset>
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