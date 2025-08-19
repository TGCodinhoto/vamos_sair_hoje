<?php
require_once('../controllers/evento_controller.php');

$eventos = listarEventosCompletos();

// Tratamento de mensagens
$mensagem = '';
if (isset($_GET['msg'])) {
    $tipo = strpos($_GET['msg'], 'Erro') === 0 ? 'error' : 'success';
    $mensagem = htmlspecialchars($_GET['msg']);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>Próximos Eventos | Lista Completa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
            color: #374151;
        }

        .event-card {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }

        .event-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .event-image {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }

        .info-icon {
            color: #6b7280;
            width: 20px;
            text-align: center;
            margin-right: 8px;
        }

        .info-text {
            color: #4b5563;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
        }

        .modal-content {
            background-color: #ffffff;
            margin: 5% auto;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 900px;
            max-height: 85vh;
            overflow-y: auto;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .section-title {
            position: relative;
            padding-bottom: 8px;
            margin-bottom: 16px;
        }

        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: #6366f1;
            border-radius: 3px;
        }

        .tag {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            margin-right: 6px;
            margin-bottom: 6px;
        }

        .btn-close {
            color: #9ca3af;
            font-size: 28px;
            font-weight: bold;
            transition: all 0.2s;
        }

        .btn-close:hover {
            color: #6b7280;
            transform: scale(1.1);
        }

        /* Image Modal Styles */
        .image-modal {
            display: none;
            position: fixed;
            z-index: 200;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(4px);
        }

        .image-modal-content {
            margin: auto;
            display: block;
            width: 90%;
            max-width: 1200px;
            max-height: 90vh;
            object-fit: contain;
            border-radius: 8px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .image-modal-close {
            position: absolute;
            top: 20px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
            cursor: pointer;
            z-index: 201;
        }

        .image-modal-close:hover,
        .image-modal-close:focus {
            color: #bbb;
            transform: scale(1.1);
        }

        .image-thumbnail {
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .image-thumbnail:hover {
            transform: scale(1.02);
        }

        @media (max-width: 768px) {
            .modal-content {
                margin: 10% auto;
                padding: 20px;
                width: 95%;
            }

            .image-modal-content {
                width: 95%;
                max-height: 80vh;
            }

            .image-modal-close {
                top: 10px;
                right: 20px;
                font-size: 30px;
            }
        }
    </style>
</head>

<body class="min-h-screen">

    <div class="container mx-auto px-4 py-12 max-w-7xl">

        <!-- Adicione isto após o container -->
        <?php if ($mensagem): ?>
            <div class="fixed top-4 right-4 z-50">
                <div class="<?= $tipo === 'error' ? 'bg-red-100 border-red-400 text-red-700' : 'bg-green-100 border-green-400 text-green-700' ?> px-4 py-3 rounded relative shadow-lg" role="alert">
                    <span class="block sm:inline"><?= $mensagem ?></span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                        <svg class="fill-current h-6 w-6 <?= $tipo === 'error' ? 'text-red-500' : 'text-green-500' ?>" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <title>Fechar</title>
                            <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                        </svg>
                    </span>
                </div>
            </div>
        <?php endif; ?>

        <div class="flex justify-center space-x-4 mb-8" id="botoes">
            <a href="navegacao_forms.php"
                class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition flex items-center space-x-2">
                <i class="fas fa-arrow-left"></i>
                <span>Voltar</span>
            </a>
            <a href="../index.php"
                class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition flex items-center space-x-2">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
            <a href="form_evento.php"
                class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition flex items-center space-x-2">
                <i class="fas fa-plus"></i>
                <span>Inserir</span>
            </a>
        </div>

        <!-- Header Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Próximos Eventos</h1>
        </div>

        <!-- Events Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($eventos as $evento): ?>
                <!-- <?php var_dump($evento); ?> -->
                <!-- Event Card -->
                <div class="event-card" onclick="abrirModal('modal-<?= $evento['publicacaoid'] ?>')">
                    <!-- Event Image -->
                    <?php if ($evento['publicacaofoto01']): ?>
                        <img src="../uploads/<?= htmlspecialchars($evento['publicacaofoto01']) ?>" alt="<?= htmlspecialchars($evento['publicacaonome']) ?>" class="event-image">
                    <?php else: ?>
                        <div class="event-image bg-gradient-to-r from-purple-100 to-blue-100 flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-4xl text-gray-400"></i>
                        </div>
                    <?php endif; ?>

                    <!-- Event Content -->
                    <div class="p-6">
                        <!-- Event Title -->
                        <h3 class="text-xl font-semibold text-gray-800 mb-3"><?= htmlspecialchars($evento['publicacaonome']) ?></h3>

                        <!-- Event Date & Time -->
                        <div class="flex items-center mb-2">
                            <i class="fas fa-calendar-day info-icon"></i>
                            <span class="info-text"><?= date('d/m/Y', strtotime($evento['eventodia'])) ?> • <?= substr($evento['eventohora'], 0, 5) ?></span>
                        </div>

                        <!-- Event Location -->
                        <div class="flex items-start mb-3">
                            <i class="fas fa-map-marker-alt info-icon mt-1"></i>
                            <div>
                                <p class="info-text"><?= htmlspecialchars($evento['enderecorua'] ?? 'Rua indisponível') ?>, <?= htmlspecialchars($evento['endereconumero']) ?></p>
                                <p class="info-text text-sm"><?= htmlspecialchars($evento['enderecobairro'] ?? 'Bairro indisponível') ?> • <?= htmlspecialchars($evento['nome_cidade'] ?? 'Cidade indisponível') ?>/<?= htmlspecialchars($evento['estadosigla'] ?? 'Estado indisponível') ?></p>
                            </div>
                        </div>

                        <!-- Event Type Tags -->
                        <div class="mt-4">
                            <span class="tag bg-indigo-100 text-indigo-800"><?= htmlspecialchars($evento['tipoeventonome']) ?></span>
                            <span class="tag bg-green-100 text-green-800"><?= htmlspecialchars($evento['formatonome']) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Event Modal -->
                <div id="modal-<?= $evento['publicacaoid'] ?>" class="modal">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800 mb-2"><?= htmlspecialchars($evento['publicacaonome']) ?></h2>
                                <div class="flex flex-wrap gap-2 mb-4">
                                    <span class="tag bg-indigo-100 text-indigo-800"><?= htmlspecialchars($evento['tipoeventonome']) ?></span>
                                    <span class="tag bg-green-100 text-green-800"><?= htmlspecialchars($evento['formatonome']) ?></span>
                                    <span class="tag bg-yellow-100 text-yellow-800"><?= htmlspecialchars($evento['classificacaonome']) ?></span>
                                </div>
                            </div>
                            <span class="btn-close" onclick="fecharModal('modal-<?= $evento['publicacaoid'] ?>')">&times;</span>
                        </div>

                        <!-- Media Gallery -->
                        <div class="mb-8">
                            <h3 class="section-title text-xl font-semibold text-gray-800">Galeria</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <?php if ($evento['publicacaofoto01']): ?>
                                    <div class="rounded-lg overflow-hidden">
                                        <img src="../uploads/<?= htmlspecialchars($evento['publicacaofoto01']) ?>"
                                            class="w-full h-48 object-cover rounded-lg image-thumbnail"
                                            onclick="abrirImagemModal('../uploads/<?= htmlspecialchars($evento['publicacaofoto01']) ?>')"
                                            alt="<?= htmlspecialchars($evento['publicacaonome']) ?> - Foto 1">
                                    </div>
                                <?php endif; ?>
                                <?php if ($evento['publicacaofoto02']): ?>
                                    <div class="rounded-lg overflow-hidden">
                                        <img src="../uploads/<?= htmlspecialchars($evento['publicacaofoto02']) ?>"
                                            class="w-full h-48 object-cover rounded-lg image-thumbnail"
                                            onclick="abrirImagemModal('../uploads/<?= htmlspecialchars($evento['publicacaofoto02']) ?>')"
                                            alt="<?= htmlspecialchars($evento['publicacaonome']) ?> - Foto 2">
                                    </div>
                                <?php endif; ?>
                                <?php if ($evento['publicacaovideo']): ?>
                                    <div class="rounded-lg overflow-hidden">
                                        <video controls class="w-full h-48 object-cover rounded-lg">
                                            <source src="../uploads/<?= htmlspecialchars($evento['publicacaovideo']) ?>" type="video/mp4">
                                            <source src="../uploads/<?= htmlspecialchars($evento['publicacaovideo']) ?>" type="video/mov">
                                            <source src="../uploads/<?= htmlspecialchars($evento['publicacaovideo']) ?>" type="video/avi">
                                            <source src="../uploads/<?= htmlspecialchars($evento['publicacaovideo']) ?>" type="video/mkv">
                                            Seu navegador não suporta vídeo HTML5.
                                        </video>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Two Column Layout -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                            <!-- Left Column -->
                            <div>
                                <!-- Event Details -->
                                <div class="mb-6">
                                    <h3 class="section-title text-lg font-semibold text-gray-800">Detalhes do Evento</h3>
                                    <!-- <?php var_dump($evento); ?> -->
                                    <ul class="space-y-3">
                                        <li class="flex items-start">
                                            <i class="fas fa-calendar-day info-icon mt-1"></i>
                                            <div>
                                                <p class="font-medium text-gray-700">Data e Horário</p>
                                                <p class="info-text"><?= date('d/m/Y', strtotime($evento['eventodia'])) ?> • <?= substr($evento['eventohora'], 0, 5) ?></p>
                                                <?php if ($evento['eventoduracao']): ?>
                                                    <p class="info-text text-sm">Duração: <?= htmlspecialchars($evento['eventoduracao']) ?></p>
                                                <?php endif; ?>
                                                <p class="info-text text-sm">Expectativa: <?= htmlspecialchars($evento['eventoexpectativa']) ?></p>
                                            </div>
                                        </li>
                                        <li class="flex items-start">
                                            <i class="fas fa-map-marked-alt info-icon mt-1"></i>
                                            <div>
                                                <p class="font-medium text-gray-700">Localização</p>
                                                <p class="info-text"><?= htmlspecialchars($evento['enderecorua'] ?? 'Rua indisponível') ?>, <?= htmlspecialchars($evento['endereconumero']) ?></p>
                                                <p class="info-text"><?= htmlspecialchars($evento['enderecobairro']) ?></p>
                                                <p class="info-text"><?= htmlspecialchars($evento['nome_cidade']) ?>/<?= htmlspecialchars($evento['estadosigla']) ?> • CEP: <?= htmlspecialchars($evento['enderecocep']) ?></p>
                                                <?php if ($evento['enderecocomplemento']): ?>
                                                    <p class="info-text">Complemento: <?= htmlspecialchars($evento['enderecocomplemento']) ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </li>
                                        <?php if ($evento['eventolinkingresso']): ?>
                                            <li class="flex items-start">
                                                <i class="fas fa-ticket-alt info-icon mt-1"></i>
                                                <div>
                                                    <p class="font-medium text-gray-700">Ingressos</p>
                                                    <a href="<?= htmlspecialchars($evento['eventolinkingresso']) ?>" target="_blank" class="text-indigo-600 hover:text-indigo-800 hover:underline">Comprar ingressos</a>
                                                </div>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>

                                <!-- Additional Info -->
                                <div class="mb-6">
                                    <h3 class="section-title text-lg font-semibold text-gray-800">Informações Adicionais</h3>
                                    <div class="prose prose-sm max-w-none text-gray-600">
                                        <?= nl2br(htmlspecialchars($evento['eventoinformacao'] ?? 'Nenhuma informação adicional disponível')) ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div>
                                <!-- Organizer Info -->
                                <div class="bg-gray-50 rounded-lg p-5 mb-6">
                                    <h3 class="section-title text-lg font-semibold text-gray-800">Organização</h3>
                                    <ul class="space-y-3">
                                        <li class="flex items-start">
                                            <i class="fas fa-building info-icon mt-1"></i>
                                            <div>
                                                <p class="font-medium text-gray-700">Realização</p>
                                                <p class="info-text"><?= htmlspecialchars($evento['realizacao-evento'] ?? 'Não especificado') ?></p>
                                            </div>
                                        </li>
                                        <li class="flex items-start">
                                            <i class="fas fa-users info-icon mt-1"></i>
                                            <div>
                                                <p class="font-medium text-gray-700">Público-alvo</p>
                                                <p class="info-text"><?= htmlspecialchars($evento['tipopubliconome']) ?></p>
                                            </div>
                                        </li>
                                        <li class="flex items-start">
                                            <i class="fas fa-chart-pie info-icon mt-1"></i>
                                            <div>
                                                <p class="font-medium text-gray-700">Segmento</p>
                                                <p class="info-text"><?= htmlspecialchars($evento['segmentonome']) ?> • <?= htmlspecialchars($evento['categorianome']) ?></p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Contact Info -->
                                <div class="bg-gray-50 rounded-lg p-5">
                                    <h3 class="section-title text-lg font-semibold text-gray-800">Contato</h3>
                                    <ul class="space-y-3">
                                        <?php if ($evento['atracaotelefone']): ?>
                                            <li class="flex items-start">
                                                <i class="fas fa-phone-alt info-icon mt-1"></i>
                                                <div>
                                                    <p class="font-medium text-gray-700">Telefone</p>
                                                    <p class="info-text"><?= htmlspecialchars($evento['atracaotelefone']) ?>
                                                        <?php if ($evento['atracaotelefonewz']): ?>
                                                            <span class="ml-2 bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded">WhatsApp</span>
                                                        <?php endif; ?>
                                                    </p>
                                                </div>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ($evento['atracaoinstagram']): ?>
                                            <li class="flex items-start">
                                                <i class="fab fa-instagram info-icon mt-1"></i>
                                                <div>
                                                    <p class="font-medium text-gray-700">Instagram</p>
                                                    <a href="https://instagram.com/<?= htmlspecialchars(ltrim($evento['atracaoinstagram'], '@')) ?>" target="_blank" class="text-indigo-600 hover:text-indigo-800 hover:underline"><?= htmlspecialchars($evento['atracaoinstagram']) ?></a>
                                                </div>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ($evento['atracaotictoc']): ?>
                                            <li class="flex items-start">
                                                <i class="fab fa-tiktok info-icon mt-1"></i>
                                                <div>
                                                    <p class="font-medium text-gray-700">TikTok</p>
                                                    <a href="https://tiktok.com/@<?= htmlspecialchars($evento['atracaotictoc']) ?>" target="_blank" class="text-indigo-600 hover:text-indigo-800 hover:underline">@<?= htmlspecialchars($evento['atracaotictoc']) ?></a>
                                                </div>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ($evento['atracaowebsite']): ?>
                                            <li class="flex items-start">
                                                <i class="fas fa-globe info-icon mt-1"></i>
                                                <div>
                                                    <p class="font-medium text-gray-700">Website</p>
                                                    <a href="<?= htmlspecialchars($evento['atracaowebsite']) ?>" target="_blank" class="text-indigo-600 hover:text-indigo-800 hover:underline break-all"><?= htmlspecialchars($evento['atracaowebsite']) ?></a>
                                                </div>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Validity and Status -->
                        <div class="bg-gray-50 rounded-lg p-5">
                            <h3 class="section-title text-lg font-semibold text-gray-800">Status da Publicação</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <p class="font-medium text-gray-700">Validade</p>
                                    <p class="info-text"><?= date('d/m/Y', strtotime($evento['publicacaovalidadein'])) ?> a <?= date('d/m/Y', strtotime($evento['publicacaovalidadeout'])) ?></p>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-700">Auditado</p>
                                    <p class="info-text"><?= $evento['publicacaoauditada'] ? '<span class="text-green-600">Sim</span>' : '<span class="text-gray-600">Não</span>' ?></p>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-700">Pagamento</p>
                                    <p class="info-text"><?= $evento['publicacaopaga'] ? '<span class="text-green-600">Sim</span>' : '<span class="text-gray-600">Não</span>' ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="flex space-x-4 mt-6">
                            <form method="POST" action="../controllers/evento_controller.php" onsubmit="return confirm('Tem certeza que deseja excluir este evento?');">
                                <input type="hidden" name="acao" value="excluir">
                                <input type="hidden" name="publicacao_id" value="<?= $evento['publicacaoid'] ?>">
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Excluir Evento
                                </button>
                            </form>

                            <a href="../views/form_evento.php?editar=true&publicacao_id=<?= $evento['publicacaoid'] ?>"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Editar Evento
                            </a>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="image-modal">
        <span class="image-modal-close" onclick="fecharImagemModal()">&times;</span>
        <img class="image-modal-content" id="modalImage">
    </div>

    <script>
        function abrirModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function fecharModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        function abrirImagemModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            modal.style.display = 'block';
            modalImg.src = imageSrc;
            document.body.style.overflow = 'hidden';
        }

        function fecharImagemModal() {
            const modal = document.getElementById('imageModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        window.onclick = function(event) {
            const imageModal = document.getElementById('imageModal');

            if (event.target.className === 'modal') {
                event.target.style.display = 'none';
                document.body.style.overflow = 'auto';
            }

            if (event.target === imageModal) {
                imageModal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        }

        document.onkeydown = function(evt) {
            evt = evt || window.event;
            if (evt.key === "Escape") {
                const modals = document.querySelectorAll('.modal');
                modals.forEach(modal => {
                    modal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                });

                // Also close image modal
                const imageModal = document.getElementById('imageModal');
                if (imageModal.style.display === 'block') {
                    imageModal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            }
        };
    </script>

</body>

</html>