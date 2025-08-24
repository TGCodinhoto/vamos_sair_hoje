<?php
require_once('../conexao.php');

$mensagem = '';
$cor = 'green';

$edicao = false;

if (isset($_GET['editar']) && $_GET['editar'] == 'true' && isset($_GET['publicacao_id'])) {
    $publicacao_id = $_GET['publicacao_id'];
    require_once('../models/publicacao_model.php');
    require_once('../models/anunciante_model.php');
    $publicacaoModel = new PublicacaoModel($conexao);
    $anuncianteModel = new AnuncianteModel($conexao);
    $publicacao = $publicacaoModel->buscarPorId($publicacao_id);
    $anunciante = $anuncianteModel->buscarPorPublicacaoId($publicacao_id);
    $edicao = true;
}

if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'success') {
        $mensagem = $edicao ? "Anunciante atualizado com sucesso!" : "Anunciante cadastrado com sucesso!";
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
        Cadastrar Anúncio
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
            href="../views/listar_anunciantes.php">
            <i class="fas fa-list">
            </i>
            <span class="hidden sm:block">
                Anúncios Cadastrados
            </span>
        </a>
    </div>

    

    <section class="w-full max-w-4xl bg-white rounded-lg shadow-md p-4 sm:p-8 space-y-8">
             <!-- condicional para caso a lista de anunciantes esteja vazia -->
    
        <h1
            class="text-3xl sm:text-4xl md:text-5xl font-bold mb-8 text-center text-blue-600 font-montserrat leading-tight">
            <?= $edicao ? 'Editar Anunciante' : 'Cadastrar Anúncio' ?>
        </h1>
        <!-- A linha abaixo está correta? -->
        <form action="../controllers/anunciante_controller.php" class="space-y-8" enctype="multipart/form-data" id="anunciante-form" method="POST">

            <?php if ($edicao): ?>
                <input type="hidden" name="acao" value="atualizar">
                <input type="hidden" name="publicacao_id" value="<?= htmlspecialchars($publicacao['publicacaoid']) ?>">
            <?php endif; ?>

            <fieldset class="border border-gray-300 rounded-lg p-4 sm:p-6 space-y-6">
                <div class="flex flex-col w-full">
                    <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="nome">
                        Nome
                    </label>
                    <input
                        class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                        id="nome" name="nome" placeholder="Anunciante" required="" type="text"
                        value="<?= $edicao ? htmlspecialchars($publicacao['publicacaonome']) : '' ?>" />
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
                            value="<?= $edicao ? htmlspecialchars($publicacao['publicacaovalidadein']) : '' ?>" />
                    </div>
                    <div class="flex flex-col w-full">
                        <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="validade-final">
                            Validade Final
                        </label>
                        <input
                            class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                            id="validade-final" name="validade-final" required="" type="date"
                            value="<?= $edicao ? htmlspecialchars($publicacao['publicacaovalidadeout']) : '' ?>" />
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <input class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" id="auditado"
                        name="auditado" type="checkbox" <?= ($edicao && !empty($publicacao['publicacaoauditada'])) ? 'checked' : '' ?> />
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
                                src="<?= $edicao && !empty($publicacao['publicacaofoto01']) ? '../uploads/' . htmlspecialchars($publicacao['publicacaofoto01']) : 'https://placehold.co/80x80?text=Foto+1' ?>" />
                        </div>
                        <input accept="image/*"
                            class="border border-gray-300 rounded-md px-2 py-1 text-sm sm:text-base file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 w-full sm:w-auto"
                            id="foto1" name="foto1" <?= $edicao ? '' : 'required' ?> type="file" />
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
                                src="<?= $edicao && !empty($publicacao['publicacaofoto02']) ? '../uploads/' . htmlspecialchars($publicacao['publicacaofoto02']) : 'https://placehold.co/80x80?text=Foto+2' ?>" />
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
                            <video aria-label="Pré-visualização do vídeo do local, retangular, 160 por 112 pixels, fundo cinza claro" class="max-h-full max-w-full object-contain" controls height="112" id="preview-video1" width="160">
                                <source src="<?= $edicao && !empty($publicacao['publicacaovideo']) ? '../uploads/' . htmlspecialchars($publicacao['publicacaovideo']) : 'https://placehold.co/160x112?text=Vídeo+placeholder.mp4' ?>" type="video/mp4" />
                                Seu navegador não suporta o elemento de vídeo.
                            </video>
                        </div>
                        <input accept="video/*" class="border border-gray-300 rounded-md px-2 py-1 text-sm sm:text-base file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 w-full sm:w-auto" id="video1" name="video1" type="file" />
                    </div>
                </div>

                <div class="flex flex-col w-full space-y-2">
                    <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="banner">
                        Banner
                    </label>
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-40 h-16 border border-gray-300 rounded-md flex items-center justify-center overflow-hidden bg-gray-50 shrink-0">
                            <img alt="Pré-visualização do banner, retangular, 160 por 64 pixels, fundo cinza claro"
                                class="max-h-full max-w-full object-contain" id="preview-banner"
                                src="<?= $edicao && !empty($anunciante['anunciantebanner']) ? '../uploads/' . htmlspecialchars($anunciante['anunciantebanner']) : 'https://placehold.co/160x64?text=Banner' ?>" />
                        </div>
                        <input accept="image/*"
                            class="border border-gray-300 rounded-md px-2 py-1 text-sm sm:text-base file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 w-full sm:w-auto"
                            id="banner" name="banner" type="file" />
                    </div>
                </div>


                <div class="flex items-center space-x-3">
                    <input class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        id="publicacao-pagamento" name="publicacao-pagamento" type="checkbox"
                        <?= ($edicao && !empty($publicacao['publicacaopaga'])) ? 'checked' : '' ?> />
                    <label class="font-medium text-gray-700 text-base sm:text-lg cursor-pointer"
                        for="publicacao-pagamento">
                        Publicação Pagamento
                    </label>
                </div>
            </fieldset>
            <!-- Este botão está DENTRO da tag <form>? -->
            <div class="flex justify-center">
                <button
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md px-10 py-2 transition focus:outline-none focus:ring-2 focus:ring-blue-500"
                    type="submit"> <!-- O type="submit" é essencial! -->
                    Cadastrar
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
                        if (previewElement.tagName.toLowerCase() === 'img' || previewElement.tagName.toLowerCase() === 'video') {
                            previewElement.src = e.target.result;
                            if (previewElement.tagName.toLowerCase() === 'video') {
                                previewElement.style.display = 'block';
                                previewElement.load();
                            }
                        }
                    }
                    reader.readAsDataURL(file);
                } else {
                    // Reseta a pré-visualização se o usuário cancelar a seleção
                    // Mantém o valor do banco se existir, senão mostra o placeholder
                    let srcBanco = null;
                    if (previewId === 'preview-foto1') {
                        srcBanco = "<?= $edicao && !empty($publicacao['publicacaofoto01']) ? '../uploads/' . htmlspecialchars($publicacao['publicacaofoto01']) : '' ?>";
                        previewElement.src = srcBanco || `https://placehold.co/${previewElement.width}x${previewElement.height}?text=Foto+1`;
                    } else if (previewId === 'preview-foto2') {
                        srcBanco = "<?= $edicao && !empty($publicacao['publicacaofoto02']) ? '../uploads/' . htmlspecialchars($publicacao['publicacaofoto02']) : '' ?>";
                        previewElement.src = srcBanco || `https://placehold.co/${previewElement.width}x${previewElement.height}?text=Foto+2`;
                    } else if (previewId === 'preview-banner') {
                        srcBanco = "<?= $edicao && !empty($anunciante['anunciantebanner']) ? '../uploads/' . htmlspecialchars($anunciante['anunciantebanner']) : '' ?>";
                        previewElement.src = srcBanco || `https://placehold.co/${previewElement.width}x${previewElement.height}?text=Banner`;
                    } else if (previewId === 'preview-video1') {
                        srcBanco = "<?= $edicao && !empty($publicacao['publicacaovideo']) ? '../uploads/' . htmlspecialchars($publicacao['publicacaovideo']) : '' ?>";
                        previewElement.src = srcBanco || `https://placehold.co/${previewElement.width}x${previewElement.height}?text=Vídeo+placeholder.mp4`;
                        previewElement.style.display = 'block';
                        previewElement.load();
                    }
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