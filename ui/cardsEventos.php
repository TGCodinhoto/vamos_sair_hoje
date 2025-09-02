<?php
// O array $eventos já é definido no index.php, conforme filtros aplicados.
$eventosPorPagina = 6;
$totalEventos = isset($eventos) ? count($eventos) : 0;
?>

<!-- Cards de Eventos -->
<section id="eventos-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 my-10 justify-center">
    <?php foreach ($eventos as $evento): ?>
        <!-- Card Evento -->
        <div class="bg-white rounded-lg shadow-xl overflow-hidden">
            <div class="relative">
                <?php if ($evento['publicacaofoto01']): ?>
                    <img alt="<?= htmlspecialchars($evento['publicacaonome']) ?>"
                        class="w-full h-[500px] object-cover"
                        src="uploads/<?= htmlspecialchars($evento['publicacaofoto01']) ?>" />
                <?php else: ?>
                    <div class="w-full h-[500px] bg-gradient-to-r from-purple-100 to-blue-100 flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-6xl text-gray-400"></i>
                    </div>
                <?php endif; ?>

                <button
                    aria-label="Favoritar <?= htmlspecialchars($evento['publicacaonome']) ?>"
                    class="absolute top-4 right-4 text-white text-2xl w-10 h-10 flex items-center justify-center rounded-full bg-black/50 hover:bg-black/70 transition focus:outline-none focus:ring-2 focus:ring-white"
                    type="button"
                    onclick="toggleFavorito(<?= $evento['publicacaoid'] ?>)">
                    <i class="far fa-heart"></i>
                </button>

                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent p-6 text-white">
                    <!--<h3 class="text-2xl font-bold mb-2">
                        <?= htmlspecialchars($evento['publicacaonome']) ?>
                    </h3>
                     <p class="text-sm mb-1 flex items-center gap-2">
                        <i class="fas fa-calendar-alt"></i>
                        Data: <?= date('d/m/Y', strtotime($evento['eventodia'])) ?>
                    </p>
                    <p class="text-sm mb-4 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt"></i>
                        Local: <?= htmlspecialchars($evento['enderecorua'] ?? 'Local a definir') ?> - <?= htmlspecialchars($evento['nome_cidade'] ?? 'Cidade') ?>
                    </p> -->
                    <button
                        class="w-full py-3 border border-white bg-white/20 hover:bg-white/30 rounded-md font-semibold transition"
                        type="button"
                        onclick="abrirModalEvento('modal-home-<?= $evento['publicacaoid'] ?>')">
                        Ver Detalhes
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Evento Home -->
        <div id="modal-home-<?= $evento['publicacaoid'] ?>" class="modal">
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
                    <span class="btn-close" onclick="fecharModalEvento('modal-home-<?= $evento['publicacaoid'] ?>')">&times;</span>
                </div>

                <!-- Media Gallery -->
                <div class="mb-8">
                    <h3 class="section-title text-xl font-semibold text-gray-800">Galeria</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <?php if ($evento['publicacaofoto01']): ?>
                            <div class="rounded-lg overflow-hidden">
                                <img src="uploads/<?= htmlspecialchars($evento['publicacaofoto01']) ?>"
                                    class="w-full h-48 object-cover rounded-lg image-thumbnail"
                                    onclick="abrirImagemModal('uploads/<?= htmlspecialchars($evento['publicacaofoto01']) ?>')"
                                    alt="<?= htmlspecialchars($evento['publicacaonome']) ?> - Foto 1">
                            </div>
                        <?php endif; ?>
                        <?php if ($evento['publicacaofoto02']): ?>
                            <div class="rounded-lg overflow-hidden">
                                <img src="uploads/<?= htmlspecialchars($evento['publicacaofoto02']) ?>"
                                    class="w-full h-48 object-cover rounded-lg image-thumbnail"
                                    onclick="abrirImagemModal('uploads/<?= htmlspecialchars($evento['publicacaofoto02']) ?>')"
                                    alt="<?= htmlspecialchars($evento['publicacaonome']) ?> - Foto 2">
                            </div>
                        <?php endif; ?>
                        <?php if ($evento['publicacaovideo']): ?>
                            <div class="rounded-lg overflow-hidden">
                                <video controls class="w-full h-48 object-cover rounded-lg">
                                    <source src="uploads/<?= htmlspecialchars($evento['publicacaovideo']) ?>" type="video/mp4">
                                    <source src="uploads/<?= htmlspecialchars($evento['publicacaovideo']) ?>" type="video/mov">
                                    <source src="uploads/<?= htmlspecialchars($evento['publicacaovideo']) ?>" type="video/avi">
                                    <source src="uploads/<?= htmlspecialchars($evento['publicacaovideo']) ?>" type="video/mkv">
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
            </div>
        </div>
    <?php endforeach; ?>
</section>

<!-- Botão Load More -->
<?php if ($totalEventos > $eventosPorPagina): ?>
    <div class="text-center my-8">
        <button
            id="load-more-btn"
            class="bg-[#D9A940] text-white font-bold py-3 px-8 rounded-full transition duration-300 ease-in-out transform hover:scale-105 shadow-lg"
            onclick="carregarMaisEventos()">
            <i class="fas fa-plus-circle mr-2"></i>
            Carregar Mais Eventos
        </button>
        <div id="loading-spinner" class="hidden mt-4">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <p class="text-gray-600 mt-2">Carregando eventos...</p>
        </div>
    </div>
<?php endif; ?>


<!-- Image Modal -->
<div id="imageModal" class="image-modal">
    <span class="image-modal-close" onclick="fecharImagemModal()">&times;</span>
    <img class="image-modal-content" id="modalImage">
</div>

<!-- CSS adicional para os modais -->
<style>
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
        cursor: pointer;
    }

    .btn-close:hover {
        color: #6b7280;
        transform: scale(1.1);
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

    .image-thumbnail {
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    .image-thumbnail:hover {
        transform: scale(1.02);
    }

    /* Image Modal */
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

<script>
    function abrirModalEvento(modalId) {
        document.getElementById(modalId).style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function fecharModalEvento(modalId) {
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

    function toggleFavorito(eventoId) {
        // Por enquanto, sempre redirecionar para login até implementar sistema de autenticação
        window.location.href = 'views/login.php';
        return;

        // TODO: Implementar funcionalidade de favoritar quando houver sistema de login
        /*
        // Código para quando implementar sistema de login
        fetch('controllers/favoritos_controller.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                evento_id: eventoId,
                action: 'toggle'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Alterar o ícone do coração baseado no status
                const button = event.target.closest('button');
                const icon = button.querySelector('i');
                
                if (data.favorited) {
                    icon.className = 'fas fa-heart'; // Coração preenchido
                    button.setAttribute('aria-label', 'Remover dos favoritos');
                } else {
                    icon.className = 'far fa-heart'; // Coração vazio
                    button.setAttribute('aria-label', 'Adicionar aos favoritos');
                }
            }
        })
        .catch(error => {
            console.error('Erro ao favoritar:', error);
        });
        */
    }

    let currentOffset = <?= $eventosPorPagina ?>;
    const eventosPerPage = <?= $eventosPorPagina ?>;

    function carregarMaisEventos() {
        const loadMoreBtn = document.getElementById('load-more-btn');
        const loadingSpinner = document.getElementById('loading-spinner');
        const eventosContainer = document.getElementById('eventos-container');

        // Mostrar loading
        loadMoreBtn.classList.add('hidden');
        loadingSpinner.classList.remove('hidden');

    // Captura filtros da URL
    const params = new URLSearchParams(window.location.search);
    params.set('offset', currentOffset);
    params.set('limit', eventosPerPage);
    fetch(`ajax/carregar_mais_eventos.php?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Adicionar novos eventos ao container
                    eventosContainer.insertAdjacentHTML('beforeend', data.html);

                    // Atualizar offset
                    currentOffset = data.nextOffset;

                    // Esconder loading
                    loadingSpinner.classList.add('hidden');

                    // Mostrar/esconder botão baseado em se há mais eventos
                    if (data.hasMore) {
                        loadMoreBtn.classList.remove('hidden');
                    } else {
                        // Se não há mais eventos, esconder o botão
                        loadMoreBtn.style.display = 'none';
                    }
                } else {
                    console.error('Erro ao carregar mais eventos:', data.error);
                    loadingSpinner.classList.add('hidden');
                    loadMoreBtn.classList.remove('hidden');
                    loadMoreBtn.textContent = 'Erro ao carregar. Tente novamente';
                    loadMoreBtn.className = loadMoreBtn.className.replace('from-purple-600 to-blue-600', 'from-red-600 to-red-700');
                }
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
                loadingSpinner.classList.add('hidden');
                loadMoreBtn.classList.remove('hidden');
                loadMoreBtn.textContent = 'Erro ao carregar. Tente novamente';
            });
    }

    document.onkeydown = function(evt) {
        evt = evt || window.event;
        if (evt.key === "Escape") {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                modal.style.display = 'none';
            });
            const imageModal = document.getElementById('imageModal');
            imageModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    };
</script>