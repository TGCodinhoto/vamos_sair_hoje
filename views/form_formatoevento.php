<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../controllers/formatoevento_controller.php');

$mensagem = '';
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'created') {
        $mensagem = "Formato de evento cadastrado com sucesso!";
    } elseif ($_GET['msg'] === 'updated') {
        $mensagem = "Formato de evento atualizado com sucesso!";
    } elseif ($_GET['msg'] === 'deleted') {
        $mensagem = "Formato de evento excluído com sucesso!";
    }
}

$formatoParaEditar = null;
if (isset($_GET['editar'])) {
    $formatoParaEditar = buscarFormatoEventoPorId(intval($_GET['editar']));
}

$formatosEvento = listarFormatosEvento();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <title>Gerenciar - Formato do Evento</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen p-6">
    <div class="max-w-5xl mx-auto bg-white p-8 rounded shadow">
        <a href="navegacao_forms.php" class="inline-block mb-6 px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition">&larr; Voltar</a>
        <h1 class="text-3xl font-bold mb-6 text-center">Gerenciar - Formato do Evento</h1>

        <?php if (!empty($mensagem)): ?>
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="mb-8">
            <?php if ($formatoParaEditar): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($formatoParaEditar['formatoid']) ?>" />
            <?php endif; ?>

            <label for="formatonome" class="block font-semibold mb-2">Nome do Formato do Evento:</label>
            <input type="text" id="formatonome" name="formatonome" required
                value="<?= $formatoParaEditar ? htmlspecialchars($formatoParaEditar['formatonome']) : '' ?>"
                class="w-full border border-gray-300 rounded p-2 mb-4 focus:outline-none focus:ring-2 focus:ring-indigo-500" />

            <label for="formatodescricao" class="block font-semibold mb-2">Descrição do Formato do Evento:</label>
            <input type="text" id="formatodescricao" name="formatodescricao"
                value="<?= $formatoParaEditar ? htmlspecialchars($formatoParaEditar['formatodescricao']) : '' ?>"
                class="w-full border border-gray-300 rounded p-2 mb-4 focus:outline-none focus:ring-2 focus:ring-indigo-500" />

            <button
                class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition"
                type="submit">
                <?= $formatoParaEditar ? 'Atualizar' : 'Cadastrar' ?>
            </button>
        </form>

        <hr class="my-6" />

        <h2 class="text-2xl font-semibold mb-4">Formatos de Evento Cadastrados</h2>

        <table class="w-full table-auto border-collapse border border-gray-300">
            <thead>
                <tr class="bg-indigo-100">
                    <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Nome</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Descrição</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($formatosEvento as $formato): ?>
                    <tr class="hover:bg-gray-100">
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($formato['formatoid']) ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($formato['formatonome']) ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($formato['formatodescricao']) ?></td>
                        <td class="border border-gray-300 px-4 py-2">
                            <a href="?editar=<?= $formato['formatoid'] ?>"
                                class="text-indigo-600 hover:underline mr-2">Editar</a>
                            <a href="../controllers/formatoevento_controller.php?delete=<?= $formato['formatoid'] ?>"
                                onclick="return confirm('Tem certeza que deseja excluir este formato de evento?')"
                                class="text-red-600 hover:underline">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html> 