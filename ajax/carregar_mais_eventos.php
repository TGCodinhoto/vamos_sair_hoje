<?php
header('Content-Type: application/json');

try {
    require_once '../controllers/evento_controller.php';
    
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 6;

    // Captura filtros
    $cidade = $_GET['cidade'] ?? null;
    $dataInicial = $_GET['data_inicial'] ?? null;
    $dataFinal = $_GET['data_final'] ?? null;
    $tipoEvento = $_GET['tipo_evento'] ?? null;

    // Buscar eventos filtrados ou todos
    if ($cidade || $dataInicial || $dataFinal || $tipoEvento) {
        $todosEventos = buscarEventosFiltrados($conexao, $cidade, $dataInicial, $dataFinal, $tipoEvento);
    } else {
        $todosEventos = listarEventosCompletos();
    }

    // Pegar os eventos da página solicitada
    $eventos = array_slice($todosEventos, $offset, $limit);
    
    $html = '';
    
    foreach ($eventos as $evento) {
        ob_start();
        ?>
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
                    class="absolute top-4 right-4 text-white text-2xl p-2 rounded-full bg-black/50 hover:bg-black/70 transition focus:outline-none focus:ring-2 focus:ring-white"
                    type="button"
                    onclick="toggleFavorito(<?= $evento['publicacaoid'] ?>)"
                >
                    <i class="far fa-heart"></i>
                </button>    
                
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent p-6 text-white">
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
        <?php
        $html .= ob_get_clean();
    }
    
    $response = [
        'success' => true,
        'html' => $html,
        'hasMore' => ($offset + $limit) < count($todosEventos),
        'nextOffset' => $offset + $limit,
        'total' => count($todosEventos)
    ];
    
    echo json_encode($response);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
