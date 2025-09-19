<?php
// Buscar detalhes do local
require_once '../models/local_model.php';
$localModel = new LocalModel($conexao);
$detalhes = $localModel->buscarPorPublicacaoId($item['publicacaoid']);
?>

<div id="modal-<?= $item['publicacaoid'] ?>" class="modal">
    <div class="modal-content">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2"><?= htmlspecialchars($item['publicacaonome']) ?></h2>
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="tag bg-green-100 text-green-800">Local</span>
                    <span class="tag bg-blue-100 text-blue-800">Tipo: <?= htmlspecialchars($detalhes['tipolocalnome'] ?? '-') ?></span>
                    <span class="tag bg-yellow-100 text-yellow-800">Classificação: <?= htmlspecialchars($detalhes['classificacaonome'] ?? '-') ?></span>
                    <span class="tag bg-blue-100 text-blue-800">Categoria: <?= htmlspecialchars($detalhes['categorianome'] ?? '-') ?></span>
                </div>
            </div>
            <span class="btn-close" onclick="fecharModal('modal-<?= $item['publicacaoid'] ?>')">&times;</span>
        </div>

        <!-- Galeria de Mídia -->
        <div class="mb-8">
            <h3 class="section-title text-xl font-semibold text-gray-800">Galeria</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <?php if (!empty($detalhes['publicacaofoto01'])): ?>
                    <div class="rounded-lg overflow-hidden">
                        <img src="../uploads/<?= htmlspecialchars($detalhes['publicacaofoto01']) ?>" class="w-full h-48 object-cover rounded-lg image-thumbnail" onclick="abrirImagemModal('../uploads/<?= addslashes(htmlspecialchars($detalhes['publicacaofoto01'])) ?>')" alt="<?= htmlspecialchars($item['publicacaonome']) ?> - Foto 1">
                    </div>
                <?php endif; ?>
                <?php if (!empty($detalhes['publicacaofoto02'])): ?>
                    <div class="rounded-lg overflow-hidden">
                        <img src="../uploads/<?= htmlspecialchars($detalhes['publicacaofoto02']) ?>" class="w-full h-48 object-cover rounded-lg image-thumbnail" onclick="abrirImagemModal('../uploads/<?= addslashes(htmlspecialchars($detalhes['publicacaofoto02'])) ?>')" alt="<?= htmlspecialchars($item['publicacaonome']) ?> - Foto 2">
                    </div>
                <?php endif; ?>
                <?php if (!empty($detalhes['localfoto03'])): ?>
                    <div class="rounded-lg overflow-hidden">
                        <img src="../uploads/<?= htmlspecialchars($detalhes['localfoto03']) ?>" class="w-full h-48 object-cover rounded-lg image-thumbnail" onclick="abrirImagemModal('../uploads/<?= addslashes(htmlspecialchars($detalhes['localfoto03'])) ?>')" alt="<?= htmlspecialchars($item['publicacaonome']) ?> - Foto 3">
                    </div>
                <?php endif; ?>
                <?php if (!empty($detalhes['localfoto04'])): ?>
                    <div class="rounded-lg overflow-hidden">
                        <img src="../uploads/<?= htmlspecialchars($detalhes['localfoto04']) ?>" class="w-full h-48 object-cover rounded-lg image-thumbnail" onclick="abrirImagemModal('../uploads/<?= addslashes(htmlspecialchars($detalhes['localfoto04'])) ?>')" alt="<?= htmlspecialchars($item['publicacaonome']) ?> - Foto 4">
                    </div>
                <?php endif; ?>
                <?php if (!empty($detalhes['localfoto05'])): ?>
                    <div class="rounded-lg overflow-hidden">
                        <img src="../uploads/<?= htmlspecialchars($detalhes['localfoto05']) ?>" class="w-full h-48 object-cover rounded-lg image-thumbnail" onclick="abrirImagemModal('../uploads/<?= addslashes(htmlspecialchars($detalhes['localfoto05'])) ?>')" alt="<?= htmlspecialchars($item['publicacaonome']) ?> - Foto 5">
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Informações detalhadas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div>
                <h3 class="section-title text-lg font-semibold text-gray-800">Detalhes</h3>
                <ul class="space-y-3">
                    <li class="flex items-start">
                        <i class="fas fa-building info-icon mt-1"></i>
                        <div>
                            <p class="font-medium text-gray-700">Tipo de Local</p>
                            <p class="info-text"><?= htmlspecialchars($detalhes['tipolocalnome'] ?? 'Não especificado') ?></p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-map-marked-alt info-icon mt-1"></i>
                        <div>
                            <p class="font-medium text-gray-700">Localização</p>
                            <p class="info-text"><?= htmlspecialchars($detalhes['enderecorua'] ?? '-') ?>, <?= htmlspecialchars($detalhes['endereconumero'] ?? '-') ?></p>
                            <p class="info-text"><?= htmlspecialchars($detalhes['enderecobairro'] ?? '-') ?></p>
                            <p class="info-text"><?= htmlspecialchars($detalhes['nome_cidade'] ?? '-') ?>/<?= htmlspecialchars($detalhes['estadosigla'] ?? '-') ?> • CEP: <?= htmlspecialchars($detalhes['enderecocep'] ?? '-') ?></p>
                            <?php if (!empty($detalhes['enderecocomplemento'])): ?>
                                <p class="info-text">Complemento: <?= htmlspecialchars($detalhes['enderecocomplemento']) ?></p>
                            <?php endif; ?>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-calendar info-icon mt-1"></i>
                        <div>
                            <p class="font-medium text-gray-700">Validade da Publicação</p>
                            <p class="info-text"><?= !empty($detalhes['publicacaovalidadein']) ? date('d/m/Y', strtotime($detalhes['publicacaovalidadein'])) : '-' ?> a <?= !empty($detalhes['publicacaovalidadeout']) ? date('d/m/Y', strtotime($detalhes['publicacaovalidadeout'])) : '-' ?></p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-phone info-icon mt-1"></i>
                        <div>
                            <p class="font-medium text-gray-700">Telefone</p>
                            <p class="info-text"><?= htmlspecialchars($detalhes['atracaotelefone'] ?? '-') ?></p>
                        </div>
                    </li>
                </ul>
            </div>
            <div>
                <h3 class="section-title text-lg font-semibold text-gray-800">Informações Adicionais</h3>
                <ul class="space-y-3">
                    <li class="flex items-start">
                        <i class="fas fa-users info-icon mt-1"></i>
                        <div>
                            <p class="font-medium text-gray-700">Público-alvo</p>
                            <p class="info-text"><?= htmlspecialchars($detalhes['tipopubliconome'] ?? '-') ?></p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-chart-pie info-icon mt-1"></i>
                        <div>
                            <p class="font-medium text-gray-700">Segmento</p>
                            <p class="info-text"><?= htmlspecialchars($detalhes['segmentonome'] ?? '-') ?> • <?= htmlspecialchars($detalhes['categorianome'] ?? '-') ?></p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-star info-icon mt-1"></i>
                        <div>
                            <p class="font-medium text-gray-700">Classificação Etária</p>
                            <p class="info-text"><?= htmlspecialchars($detalhes['classificacaonome'] ?? '-') ?></p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Status da Publicação -->
        <div class="bg-gray-50 rounded-lg p-5">
            <h3 class="section-title text-lg font-semibold text-gray-800">Status da Publicação</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="font-medium text-gray-700">Validade</p>
                    <p class="info-text"><?= !empty($detalhes['publicacaovalidadein']) ? date('d/m/Y', strtotime($detalhes['publicacaovalidadein'])) : '-' ?> a <?= !empty($detalhes['publicacaovalidadeout']) ? date('d/m/Y', strtotime($detalhes['publicacaovalidadeout'])) : '-' ?></p>
                </div>
                <div>
                    <p class="font-medium text-gray-700">Auditado</p>
                    <p class="info-text"><?= !empty($detalhes['publicacaoauditada']) ? '<span class="text-green-600">Sim</span>' : '<span class="text-gray-600">Não</span>' ?></p>
                </div>
                <div>
                    <p class="font-medium text-gray-700">Pagamento</p>
                    <p class="info-text"><?= !empty($detalhes['publicacaopaga']) ? '<span class="text-green-600">Sim</span>' : '<span class="text-gray-600">Não</span>' ?></p>
                </div>
            </div>
        </div>

        <div class="flex space-x-4 mt-6 justify-end">
            <form method="post" action="../controllers/auditoria_controller.php" onsubmit="return confirm('Tem certeza que deseja aprovar/desaprovar?');">
                <input type="hidden" name="publicacaoid" value="<?= $item['publicacaoid'] ?>">
                <button type="submit" name="aprovar" value="<?= $item['publicacaoid'] ?>" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Aprovar</button>
                <button type="submit" name="desaprovar" value="<?= $item['publicacaoid'] ?>" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Desaprovar</button>
            </form>
        </div>
    </div>
</div>