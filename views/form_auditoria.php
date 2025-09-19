<?php
require_once '../conexao.php';
require_once '../utils/session_manager.php';
SessionManager::iniciarSessao();

if (!isset($_SESSION['userid']) || intval($_SESSION['usertipo']) !== 1) {
    header("Location: ../index.php");
    exit();
}
require_once '../models/publicacao_model.php';

$conexao = Conexao::getInstance();
$publicacaoModel = new PublicacaoModel($conexao);
$naoAuditados = $publicacaoModel->getNaoAuditados();
?>

<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Auditoria de Eventos e Locais</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../styles/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
            color: #374151;
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
        @media (max-width: 768px) {
            .modal-content {
                margin: 10% auto;
                padding: 20px;
                width: 95%;
            }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center justify-start p-4 sm:p-6 md:p-8">
    <div class="flex justify-center space-x-4 mb-6" id="botoes">
        <a href="navegacao_forms.php"
            class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition flex items-center space-x-2">
            <i class="fas fa-arrow-left"></i>
            <span class="hidden sm:block">Voltar</span>
        </a>
        <a href="../index.php"
            class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition flex items-center space-x-2">
            <i class="fas fa-home"></i>
            <span class="hidden sm:block">Home</span>
        </a>
    </div>
    <section class="w-full max-w-4xl bg-white rounded-lg shadow-md p-4 sm:p-8 space-y-8">
        <h1 class="text-4xl md:text-5xl font-bold mb-8 text-center text-yellow-600 font-montserrat">
            Auditoria de Eventos e Locais
        </h1>
        <?php if (empty($naoAuditados)): ?>
            <div class="p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded text-center">
                Nenhum evento ou local pendente de auditoria.
            </div>
        <?php else: ?>
            <form method="post" action="../controllers/auditoria_controller.php" class="space-y-6">
                <table class="min-w-full divide-y divide-gray-200 mb-6">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        <?php foreach ($naoAuditados as $item): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 font-semibold"><?= htmlspecialchars($item['publicacaonome']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500"><?= htmlspecialchars($item['tipo']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap flex flex-row gap-2">
                        <button type="button" onclick="abrirModal('modal-<?= $item['publicacaoid'] ?>')" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">Ver detalhes</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php foreach ($naoAuditados as $item): ?>
                    <?php if ($item['tipo'] === 'Evento'): ?>
                        <?php include 'partials/modal_evento.php'; ?>
                    <?php else: ?>
                        <?php include 'partials/modal_local.php'; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php foreach ($naoAuditados as $item): ?>
                        <div id="modal-<?= $item['publicacaoid'] ?>" class="modal">
                        <div class="modal-content">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-800 mb-2"><?= htmlspecialchars($item['publicacaonome']) ?></h2>
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        <?php if ($item['tipo'] === 'Evento'): ?>
                                            <span class="tag bg-indigo-100 text-indigo-800">Evento</span>
                                            <span class="tag bg-green-100 text-green-800">Formato: <?= htmlspecialchars($detalhes['formatonome'] ?? '-') ?></span>
                                            <span class="tag bg-blue-100 text-blue-800">Tipo: <?= htmlspecialchars($detalhes['tipoeventonome'] ?? '-') ?></span>
                                            <span class="tag bg-yellow-100 text-yellow-800">Classificação: <?= htmlspecialchars($detalhes['classificacaonome'] ?? '-') ?></span>
                                        <?php else: ?>
                                            <span class="tag bg-green-100 text-green-800">Local</span>
                                            <span class="tag bg-blue-100 text-blue-800">Tipo: <?= htmlspecialchars($detalhes['tipolocalnome'] ?? '-') ?></span>
                                            <span class="tag bg-yellow-100 text-yellow-800">Classificação: <?= htmlspecialchars($detalhes['classificacaonome'] ?? '-') ?></span>
                                            <span class="tag bg-blue-100 text-blue-800">Categoria: <?= htmlspecialchars($detalhes['categorianome'] ?? '-') ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <span class="btn-close" onclick="fecharModal('modal-<?= $item['publicacaoid'] ?>')">&times;</span>
                            </div>
                            <!-- Galeria de Mídia -->
                            <div class="mb-8">
                                <h3 class="section-title text-xl font-semibold text-gray-800">Galeria</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <?php if ($item['tipo'] === 'Evento'): ?>
                                        <?php if (!empty($detalhes['publicacaofoto01'])): ?>
                                            <div class="rounded-lg overflow-hidden">
                                                <img src="../uploads/<?= htmlspecialchars($detalhes['publicacaofoto01']) ?>" class="w-full h-48 object-cover rounded-lg image-thumbnail" onclick="abrirImagemModal('../uploads/<?= htmlspecialchars($detalhes['publicacaofoto01']) ?>')" alt="<?= htmlspecialchars($item['publicacaonome']) ?> - Foto 1">
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($detalhes['publicacaofoto02'])): ?>
                                            <div class="rounded-lg overflow-hidden">
                                                <img src="../uploads/<?= htmlspecialchars($detalhes['publicacaofoto02']) ?>" class="w-full h-48 object-cover rounded-lg image-thumbnail" onclick="abrirImagemModal('../uploads/<?= htmlspecialchars($detalhes['publicacaofoto02']) ?>')" alt="<?= htmlspecialchars($item['publicacaonome']) ?> - Foto 2">
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($detalhes['publicacaovideo'])): ?>
                                            <div class="rounded-lg overflow-hidden">
                                                <video controls class="w-full h-48 object-cover rounded-lg">
                                                    <source src="../uploads/<?= htmlspecialchars($detalhes['publicacaovideo']) ?>" type="video/mp4">
                                                    Seu navegador não suporta vídeo HTML5.
                                                </video>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php if (!empty($detalhes['publicacaofoto01'])): ?>
                                            <div class="rounded-lg overflow-hidden">
                                                <img src="../uploads/<?= htmlspecialchars($detalhes['publicacaofoto01']) ?>" class="w-full h-48 object-cover rounded-lg image-thumbnail" onclick="abrirImagemModal('../uploads/<?= htmlspecialchars($detalhes['publicacaofoto01']) ?>')" alt="<?= htmlspecialchars($item['publicacaonome']) ?> - Foto 1">
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($detalhes['publicacaofoto02'])): ?>
                                            <div class="rounded-lg overflow-hidden">
                                                <img src="../uploads/<?= htmlspecialchars($detalhes['publicacaofoto02']) ?>" class="w-full h-48 object-cover rounded-lg image-thumbnail" onclick="abrirImagemModal('../uploads/<?= htmlspecialchars($detalhes['publicacaofoto02']) ?>')" alt="<?= htmlspecialchars($item['publicacaonome']) ?> - Foto 2">
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($detalhes['localfoto03'])): ?>
                                            <div class="rounded-lg overflow-hidden">
                                                <img src="../uploads/<?= htmlspecialchars($detalhes['localfoto03']) ?>" class="w-full h-48 object-cover rounded-lg image-thumbnail" onclick="abrirImagemModal('../uploads/<?= htmlspecialchars($detalhes['localfoto03']) ?>')" alt="<?= htmlspecialchars($item['publicacaonome']) ?> - Foto 3">
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($detalhes['localfoto04'])): ?>
                                            <div class="rounded-lg overflow-hidden">
                                                <img src="../uploads/<?= htmlspecialchars($detalhes['localfoto04']) ?>" class="w-full h-48 object-cover rounded-lg image-thumbnail" onclick="abrirImagemModal('../uploads/<?= htmlspecialchars($detalhes['localfoto04']) ?>')" alt="<?= htmlspecialchars($item['publicacaonome']) ?> - Foto 4">
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($detalhes['localfoto05'])): ?>
                                            <div class="rounded-lg overflow-hidden">
                                                <img src="../uploads/<?= htmlspecialchars($detalhes['localfoto05']) ?>" class="w-full h-48 object-cover rounded-lg image-thumbnail" onclick="abrirImagemModal('../uploads/<?= htmlspecialchars($detalhes['localfoto05']) ?>')" alt="<?= htmlspecialchars($item['publicacaonome']) ?> - Foto 5">
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <!-- Informações detalhadas -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                                <div>
                                    <h3 class="section-title text-lg font-semibold text-gray-800">Detalhes</h3>
                                    <ul class="space-y-3">
                                        <?php if ($item['tipo'] === 'Evento'): ?>
                                            <li class="flex items-start">
                                                <i class="fas fa-calendar-day info-icon mt-1"></i>
                                                <div>
                                                    <p class="font-medium text-gray-700">Data e Horário</p>
                                                    <p class="info-text"><?= !empty($detalhes['eventodia']) ? date('d/m/Y', strtotime($detalhes['eventodia'])) : '-' ?> • <?= !empty($detalhes['eventohora']) ? substr($detalhes['eventohora'], 0, 5) : '-' ?></p>
                                                    <?php if (!empty($detalhes['eventoduracao'])): ?>
                                                        <p class="info-text text-sm">Duração: <?= htmlspecialchars($detalhes['eventoduracao']) ?></p>
                                                    <?php endif; ?>
                                                    <?php if (!empty($detalhes['eventoexpectativa'])): ?>
                                                        <p class="info-text text-sm">Expectativa: <?= htmlspecialchars($detalhes['eventoexpectativa']) ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            </li>
                                            <li class="flex items-start">
                                                <i class="fas fa-map-marked-alt info-icon mt-1"></i>
                                                <div>
                                                    <p class="font-medium text-gray-700">Localização</p>
                                                    <p class="info-text"><?= htmlspecialchars($detalhes['enderecorua'] ?? '-') ?>, <?= htmlspecialchars($detalhes['endereconumero'] ?? '-') ?></p>
                                                    <p class="info-text">Bairro: <?= htmlspecialchars($detalhes['enderecobairro'] ?? '-') ?></p>
                                                    <p class="info-text">Cidade: <?= htmlspecialchars($detalhes['nome_cidade'] ?? '-') ?>/<?= htmlspecialchars($detalhes['estadosigla'] ?? '-') ?></p>
                                                    <p class="info-text">CEP: <?= htmlspecialchars($detalhes['enderecocep'] ?? '-') ?></p>
                                                    <?php if (!empty($detalhes['enderecocomplemento'])): ?>
                                                        <p class="info-text">Complemento: <?= htmlspecialchars($detalhes['enderecocomplemento']) ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            </li>
                                            <?php if (!empty($detalhes['eventolinkingresso'])): ?>
                                            <li class="flex items-start">
                                                <i class="fas fa-ticket-alt info-icon mt-1"></i>
                                                <div>
                                                    <p class="font-medium text-gray-700">Ingressos</p>
                                                    <a href="<?= htmlspecialchars($detalhes['eventolinkingresso']) ?>" target="_blank" class="text-indigo-600 hover:text-indigo-800 hover:underline">Comprar ingressos</a>
                                                </div>
                                            </li>
                                            <?php endif; ?>
                                        <?php else: ?>
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
                                        <?php endif; ?>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="section-title text-lg font-semibold text-gray-800">Informações Adicionais</h3>
                                    <ul class="space-y-3">
                                        <?php if ($item['tipo'] === 'Evento'): ?>
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
                                            <li class="flex items-start">
                                                <i class="fas fa-info-circle info-icon mt-1"></i>
                                                <div>
                                                    <p class="font-medium text-gray-700">Informações</p>
                                                    <p class="info-text"><?= nl2br(htmlspecialchars($detalhes['eventoinformacao'] ?? 'Nenhuma informação adicional disponível')) ?></p>
                                                </div>
                                            </li>
                                        <?php else: ?>
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
                                            <li class="flex items-start">
                                                <i class="fas fa-phone info-icon mt-1"></i>
                                                <div>
                                                    <p class="font-medium text-gray-700">Telefone</p>
                                                    <p class="info-text"><?= htmlspecialchars($detalhes['atracaotelefone'] ?? '-') ?></p>
                                                </div>
                                            </li>
                                        <?php endif; ?>
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
                <?php endforeach; ?>
                <script>
                    function abrirModal(modalId) {
                        document.getElementById(modalId).style.display = 'block';
                        document.body.style.overflow = 'hidden';
                    }
                    function fecharModal(modalId) {
                        document.getElementById(modalId).style.display = 'none';
                        document.body.style.overflow = 'auto';
                    }
                    window.onclick = function(event) {
                        if (event.target.className === 'modal') {
                            event.target.style.display = 'none';
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
                        }
                    };
                </script>
            </form>
        <?php endif; ?>
    </section>

    <!-- Modal de Imagem -->
    <div id="imageModal" class="image-modal">
        <span class="image-modal-close" onclick="fecharImagemModal()">&times;</span>
        <img class="image-modal-content" id="modalImage">
    </div>

    <script>
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

        // Atualiza o listener de clique da janela para incluir o modal de imagem
        window.onclick = function(event) {
            if (event.target.className === 'modal') {
                event.target.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
            
            const imageModal = document.getElementById('imageModal');
            if (event.target === imageModal) {
                imageModal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        }

        // Atualiza o listener de tecla para incluir o modal de imagem
        document.onkeydown = function(evt) {
            evt = evt || window.event;
            if (evt.key === "Escape") {
                const modals = document.querySelectorAll('.modal');
                modals.forEach(modal => {
                    modal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                });
                
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
