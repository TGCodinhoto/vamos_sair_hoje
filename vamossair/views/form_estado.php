<?php
require_once('../controllers/estado_controller.php');

$mensagem = '';
$estadoEditar = null;

if (isset($_GET['editar'])) {
    $estadoEditar = buscarEstadoPorId($_GET['editar']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['estadonome'];

    if (!empty($_POST['id'])) {
        $atualizou = atualizarEstado($_POST['id'], $nome);
        $mensagem = $atualizou ? "Estado atualizada com sucesso!" : "Erro ao atualizar.";
        $estadoEditar = buscarEstadoPorId($_POST['id']);
    } else {
        $inseriu = criarEstado($nome);
        $mensagem = $inseriu ? "Estado cadastrada com sucesso!" : "Erro ao cadastrar.";
    }
}

$estados = listarEstados();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <title>Gerenciar Estado</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-900 p-8">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold mb-6"><?= $estadoEditar ? 'Editar' : 'Cadastrar' ?> Estado</h1>

        <?php if (!empty($mensagem)): ?>
            <p class="mb-4 p-2 bg-green-100 text-green-700 rounded"><?= htmlspecialchars($mensagem) ?></p>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <?php if ($estadoEditar): ?>
                <input type="hidden" name="id" value="<?= $estadoEditar['estadoid'] ?>" />
            <?php endif; ?>

            <div>
                <label class="block font-semibold" for="estadonome">Sigla do Estado</label>
                <input type="text" id="estadonome" name="estadonome" required
                    value="<?= $estadoEditar ? htmlspecialchars($estadoEditar['estadonome']) : '' ?>"
                    class="w-full border px-4 py-2 rounded" />
            </div>

            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                <?= $estadoEditar ? 'Atualizar' : 'Cadastrar' ?>
            </button>
        </form>

        <hr class="my-6" />

        <h2 class="text-xl font-bold mb-4">Estados Cadastrados</h2>

        <table class="w-full table-auto border">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-2 text-left">ID</th>
                    <th class="p-2 text-left">Nome</th>
                    <th class="p-2 text-left">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($estados as $estado): ?>
                    <tr class="border-t">
                        <td class="p-2"><?= $estado['estadoid'] ?></td>
                        <td class="p-2"><?= htmlspecialchars($estado['estadonome']) ?></td>
                        <td class="p-2">
                            <a class="text-blue-600 hover:underline"
                                href="?editar=<?= $estado['estadoid'] ?>">Editar</a> |
                            <a class="text-red-600 hover:underline"
                                href="../controllers/estado_controller.php?delete=<?= $estado['estadoid'] ?>"
                                onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>