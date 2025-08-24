<?php
require_once('../controllers/anunciante_controller.php');

$anuncios = listarAnunciosCompletos();

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
    <title>Anunciantes | Lista Completa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
            color: #374151;
        }

        .card {
            transition: all 0.3s;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .card-image {
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
            max-width: 700px;
            max-height: 85vh;
            overflow-y: auto;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
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

        .image-thumbnail {
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .image-thumbnail:hover {
            transform: scale(1.05);
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
        }

        .image-modal-content {
            margin: auto;
            display: block;
            width: 90%;
            max-width: 1200px;
            max-height: 90vh;
            object-fit: contain;
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
        }
    </style>
</head>

<body class="min-h-screen">

    <div class="container mx-auto px-4 py-12 max-w-7xl">
        <!-- Toast de confirmação -->
        <div id="toast-success" class="fixed top-6 right-6 z-50 hidden bg-green-500 text-white px-6 py-3 rounded shadow-lg flex items-center space-x-2">
            <i class="fas fa-check-circle"></i>
            <span id="toast-msg">Anunciante excluído com sucesso!</span>
        </div>

        <div class="flex justify-center space-x-4 mb-8" id="botoes">
            <a href="navegacao_forms.php" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition flex items-center space-x-2"><i class="fas fa-arrow-left"></i><span>Voltar</span></a>
            <a href="../index.php" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition flex items-center space-x-2"><i class="fas fa-home"></i><span>Home</span></a>
            <a href="form_anunciante.php" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition flex items-center space-x-2"><i class="fas fa-plus"></i><span>Inserir Anúncio</span></a>
        </div>

        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Anunciantes Cadastrados</h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($anuncios as $anuncio): ?>
                <div class="card" onclick="abrirModal('modal-<?= $anuncio['publicacaoid'] ?>')">
                    <?php if (!empty($anuncio['publicacaofoto01'])): ?>
                        <img src="../uploads/<?= htmlspecialchars($anuncio['publicacaofoto01']) ?>" alt="<?= htmlspecialchars($anuncio['publicacaonome']) ?>" class="card-image">
                    <?php else: ?>
                        <div class="card-image bg-gradient-to-r from-gray-100 to-gray-200 flex items-center justify-center">
                            <i class="fas fa-bullhorn text-4xl text-gray-400"></i>
                        </div>
                    <?php endif; ?>

                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-3"><?= htmlspecialchars($anuncio['publicacaonome'] ?? 'Nome não informado') ?></h3>
                        <div class="flex items-center mb-2">
                            <i class="fas fa-calendar-check info-icon"></i>
                            <span class="info-text">Validade: <?= $anuncio['publicacaovalidadein'] ? date('d/m/Y', strtotime($anuncio['publicacaovalidadein'])) : 'N/A' ?></span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check-circle info-icon <?= $anuncio['publicacaoauditada'] ? 'text-green-500' : 'text-gray-400' ?>"></i>
                            <span class="info-text"><?= $anuncio['publicacaoauditada'] ? 'Auditado' : 'Não Auditado' ?></span>
                        </div>
                    </div>
                </div>

                <div id="modal-<?= $anuncio['publicacaoid'] ?>" class="modal">
                    <div class="modal-content">
                        <!-- Header do Modal -->
                        <div class="flex justify-between items-start mb-6">
                            <h2 class="text-2xl font-bold text-gray-800"><?= htmlspecialchars($anuncio['publicacaonome'] ?? 'Nome não informado') ?></h2>
                            <span class="btn-close" onclick="fecharModal('modal-<?= $anuncio['publicacaoid'] ?>')">&times;</span>
                        </div>

                        <div class="mb-8">
                            <h3 class="section-title text-xl font-semibold text-gray-800">Galeria</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <?php if (!empty($anuncio['publicacaofoto01'])): ?>
                                    <img src="../uploads/<?= htmlspecialchars($anuncio['publicacaofoto01']) ?>" class="w-full h-40 object-cover rounded-lg image-thumbnail" onclick="abrirImagemModal(this.src)">
                                <?php endif; ?>
                                <?php if (!empty($anuncio['publicacaofoto02'])): ?>
                                    <img src="../uploads/<?= htmlspecialchars($anuncio['publicacaofoto02']) ?>" class="w-full h-40 object-cover rounded-lg image-thumbnail" onclick="abrirImagemModal(this.src)">
                                <?php endif; ?>

                                <?php if (!empty($anuncio['publicacaovideo'])): ?>
                                    <video controls class="w-full h-40 object-cover rounded-lg">
                                        <source src="../uploads/<?= htmlspecialchars($anuncio['publicacaovideo']) ?>" type="video/mp4">
                                    </video>
                                <?php endif; ?>
                            </div>

                            <div class="mt-4">
                                <h3 class="section-title text-lg font-semibold text-gray-800">Banner</h3>
                                <?php if (!empty($anuncio['anunciantebanner'])): ?>
                                    <img src="../uploads/<?= htmlspecialchars($anuncio['anunciantebanner']) ?>" class="w-full h-40 object-cover rounded-lg image-thumbnail" onclick="abrirImagemModal(this.src)">
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-5">
                            <h3 class="section-title text-lg font-semibold text-gray-800">Status da Publicação</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <p class="font-medium text-gray-700">Validade</p>
                                    <p class="info-text"><?= $anuncio['publicacaovalidadein'] ? date('d/m/Y', strtotime($anuncio['publicacaovalidadein'])) : 'N/A' ?> a <?= $anuncio['publicacaovalidadeout'] ? date('d/m/Y', strtotime($anuncio['publicacaovalidadeout'])) : 'N/A' ?></p>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-700">Auditado</p>
                                    <p class="info-text"><?= $anuncio['publicacaoauditada'] ? '<span class="text-green-600 font-semibold">Sim</span>' : 'Não' ?></p>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-700">Pagamento</p>
                                    <p class="info-text"><?= $anuncio['publicacaopaga'] ? '<span class="text-green-600 font-semibold">Sim</span>' : 'Não' ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="flex space-x-4 mt-6">
                            <form method="POST" action="../controllers/anunciante_controller.php" onsubmit="return confirm('Tem certeza que deseja excluir este anúncio?');">
                                <input type="hidden" name="acao" value="excluir">
                                <input type="hidden" name="publicacao_id" value="<?= $anuncio['publicacaoid'] ?>">
                                <input type="hidden" name="publicacaoid" value="<?= $anuncio['publicacaoid'] ?>">
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Excluir</button>
                            </form>
                            <a href="form_anunciante.php?editar=true&publicacao_id=<?= $anuncio['publicacaoid'] ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Editar</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="imageModal" class="image-modal" onclick="fecharImagemModal()">
        <span class="image-modal-close">&times;</span>
        <img class="image-modal-content" id="modalImage">
    </div>

    <script>
        // Exibe o toast se houver mensagem de sucesso
        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'success'): ?>
        document.addEventListener('DOMContentLoaded', function() {
            var toast = document.getElementById('toast-success');
            toast.style.display = 'flex';
            setTimeout(function() {
                toast.style.display = 'none';
            }, 3000);
        });
        <?php endif; ?>
    function abrirModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function fecharModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        function abrirImagemModal(src) {
            document.getElementById('imageModal').style.display = 'block';
            document.getElementById('modalImage').src = src;
        }

        function fecharImagemModal() {
            document.getElementById('imageModal').style.display = 'none';
        }
        window.onclick = function(event) {
            if (event.target.className === 'modal') {
                fecharModal(event.target.id);
            }
        };
        document.onkeydown = function(evt) {
            if ((evt || window.event).key === "Escape") {
                document.querySelectorAll('.modal, .image-modal').forEach(m => m.style.display = 'none');
                document.body.style.overflow = 'auto';
            }
        };
    </script>
</body>

</html>