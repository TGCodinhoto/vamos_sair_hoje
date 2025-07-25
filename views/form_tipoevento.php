<?php
require_once('../controllers/tipoevento_controller.php');

$mensagem = '';
$cor = 'green';

if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'created') {
        $mensagem = "Tipo de evento cadastrado com sucesso!";
    } elseif ($_GET['msg'] === 'updated') {
        $mensagem = "Tipo de evento atualizado com sucesso!";
    } elseif ($_GET['msg'] === 'deleted') {
        $mensagem = "Tipo de evento excluído com sucesso!";
    } elseif ($_GET['msg'] === 'error') {
        $cor = 'red';
        $mensagem = isset($_GET['erro']) ? $_GET['erro'] : "Erro ao processar a solicitação.";
    }
}

$eventoParaEditar = null;
if (isset($_GET['editar'])) {
    $eventoParaEditar = buscarTipoEventoPorId(intval($_GET['editar']));
}

$tiposEvento = listarTiposEvento();
?>

<!DOCTYPE html>
<html class="scroll-smooth" lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>Cadastro - Tipo Evento</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    </link>

    <style>
        #botoes {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen p-6">

    <!-- Botões Superiores Voltar e Home -->
    <div class="flex justify-center space-x-4 mb-6" id="botoes">
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
    </div>


    <div class="max-w-5xl mx-auto bg-white p-8 rounded shadow">
       
        <h1 class="text-4xl md:text-5xl font-bold mb-8 text-center text-blue-600">Gerenciar - Tipo de Evento</h1>

        <?php if (!empty($mensagem)): ?>
            <div class="mb-4 p-4 bg-<?= $cor ?>-100 border border-<?= $cor ?>-400 text-<?= $cor ?>-700 rounded">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data" class="mb-8">
            <?php if ($eventoParaEditar): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($eventoParaEditar['tipoeventoid']) ?>" />
            <?php endif; ?>

            <label for="tipoeventonome" class="block font-semibold mb-2">Nome do Tipo de Evento:</label>
            <input
                type="text"
                id="tipoeventonome"
                name="tipoeventonome"
                required
                value="<?= $eventoParaEditar ? htmlspecialchars($eventoParaEditar['tipoeventonome']) : '' ?>"
                class="w-full border border-gray-300 rounded p-2 mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />

            <?php if (!$eventoParaEditar): ?>
                <label for="fileImg" class="block font-semibold mb-2">Imagem:</label>
                <input
                    type="file"
                    name="fileImg[]"
                    accept=".jpg,.jpeg,.png"
                    required
                    class="mb-4"
                />
            <?php else: ?>
                <?php if (!empty($eventoParaEditar['tipoeventoimage'])): ?>
                    <label class="block font-semibold mb-2">Imagem atual:</label>
                    <div class="flex space-x-4 mb-4">
                        <img
                            src="../uploads/<?= htmlspecialchars($eventoParaEditar['tipoeventoimage']) ?>"
                            alt="Imagem do evento"
                            class="w-20 h-20 object-cover rounded shadow"
                        />
                    </div>
                    <p class="text-gray-500 mb-4">* Para atualizar imagens, exclua e cadastre novamente.</p>
                <?php endif; ?>
            <?php endif; ?>

            <button
                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition"
                type="submit">
                <?= $eventoParaEditar ? 'Atualizar' : 'Cadastrar' ?>
            </button>
        </form>

        <hr class="my-6" />

        <h2 class="text-2xl font-semibold mb-4">Tipos de Evento Cadastrados</h2>

        <table class="w-full table-auto border-collapse border border-gray-300">
            <thead>
                <tr class="bg-blue-100">
                    <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Nome</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Imagem</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tiposEvento as $evento): ?>
                    <tr class="hover:bg-gray-100">
                        <td class="border border-gray-300 px-4 py-2"><?= $evento['tipoeventoid'] ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($evento['tipoeventonome']) ?></td>
                        <td class="border border-gray-300 px-4 py-2">
                            <?php if (!empty($evento['tipoeventoimage'])): ?>
                                <img
                                    src="../uploads/<?= htmlspecialchars($evento['tipoeventoimage']) ?>"
                                    alt="Imagem"
                                    class="w-16 h-16 object-cover rounded"
                                />
                            <?php endif; ?>
                        </td>
                        <td class="border border-gray-300 px-4 py-2">
                            <a href="?editar=<?= $evento['tipoeventoid'] ?>"
                               class="text-blue-600 hover:underline mr-2">Editar</a>
                            <a href="../controllers/tipoevento_controller.php?delete=<?= $evento['tipoeventoid'] ?>"
                               onclick="return confirm('Tem certeza que deseja excluir este tipo de evento?')"
                               class="text-red-600 hover:underline">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
