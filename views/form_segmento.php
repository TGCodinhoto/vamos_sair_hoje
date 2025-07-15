<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../controllers/segmento_controller.php');

$mensagem = '';
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'created') {
        $mensagem = "Segmento cadastrado com sucesso!";
    } elseif ($_GET['msg'] === 'updated') {
        $mensagem = "Segmento atualizado com sucesso!";
    } elseif ($_GET['msg'] === 'deleted') {
        $mensagem = "Segmento excluído com sucesso!";
    }
}

$segmentoParaEditar = null;
if (isset($_GET['editar'])) {
    $segmentoParaEditar = buscarSegmentoPorId(intval($_GET['editar']));
}

$segmentos = listarSegmentos();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <title>Gerenciar - Segmentos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen p-6">
    <div class="max-w-5xl mx-auto bg-white p-8 rounded shadow">
        <h1 class="text-3xl font-bold mb-6 text-center">Gerenciar - Segmento</h1>

        <?php if (!empty($mensagem)): ?>
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="mb-8">
            <?php if ($segmentoParaEditar): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($segmentoParaEditar['segmentoid']) ?>" />
            <?php endif; ?>

            <label for="segmentonome" class="block font-semibold mb-2">Nome do Segmento:</label>
            <input type="text" id="segmentonome" name="segmentonome" required
                value="<?= $segmentoParaEditar ? htmlspecialchars($segmentoParaEditar['segmentonome']) : '' ?>"
                class="w-full border border-gray-300 rounded p-2 mb-4 focus:outline-none focus:ring-2 focus:ring-indigo-500" />

            <button
                class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition"
                type="submit">
                <?= $segmentoParaEditar ? 'Atualizar' : 'Cadastrar' ?>
            </button>
        </form>

        <hr class="my-6" />

        <h2 class="text-2xl font-semibold mb-4">Segmentos Cadastrados</h2>

        <table class="w-full table-auto border-collapse border border-gray-300">
            <thead>
                <tr class="bg-indigo-100">
                    <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Nome</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($segmentos as $segmento): ?>
                    <tr class="hover:bg-gray-100">
                        <td class="border border-gray-300 px-4 py-2"><?= $segmento['segmentoid'] ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($segmento['segmentonome']) ?></td>
                        <td class="border border-gray-300 px-4 py-2">
                            <a href="?editar=<?= $segmento['segmentoid'] ?>"
                                class="text-indigo-600 hover:underline mr-2">Editar</a>
                            <a href="../controllers/segmento_controller.php?delete=<?= $segmento['segmentoid'] ?>"
                                onclick="return confirm('Tem certeza que deseja excluir este segmento?')"
                                class="text-red-600 hover:underline">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html> 
