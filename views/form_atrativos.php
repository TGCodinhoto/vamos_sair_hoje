<?php
require_once('../controllers/atrativos_controller.php');

$mensagem = '';
$cor = 'green';
$atrativoEditar = null;

if (isset($_GET['editar'])) {
    $atrativoEditar = buscarAtrativoPorId(intval($_GET['editar']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['atrativosnome'];

    if (!empty($_POST['id'])) {
        $atualizou = atualizarAtrativo($_POST['id'], $nome);
        $mensagem = $atualizou ? "updated" : "error";
    } else {
        $inseriu = criarAtrativo($nome);
        $mensagem = $inseriu ? "created" : "error";
    }

    header("Location: form_atrativos.php?msg=" . $mensagem);
    exit;
}

if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'created') {
        $mensagem = "Atrativo cadastrado com sucesso!";
    } elseif ($_GET['msg'] === 'updated') {
        $mensagem = "Atrativo atualizado com sucesso!";
    } elseif ($_GET['msg'] === 'deleted') {
        $mensagem = "Atrativo excluído com sucesso!";
    } elseif ($_GET['msg'] === 'error') {
        $cor = 'red';
        $mensagem = isset($_GET['erro']) ? $_GET['erro'] : "Erro ao processar a solicitação.";
    }
}

$atrativos = listarAtrativos();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <title>Gerenciar Atrativos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-900 p-8">
    <div class="max-w-5xl mx-auto bg-white p-8 rounded shadow">
        <a href="navegacao_forms.php" class="inline-block mb-6 px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition">&larr; Voltar</a>
        <h1 class="text-4xl md:text-5xl font-bold mb-8 text-center text-blue-600"><?= $atrativoEditar ? 'Editar' : 'Cadastrar' ?> Atrativo</h1>

        <?php if (!empty($mensagem)): ?>
            <div class="mb-4 p-4 bg-<?= $cor ?>-100 border border-<?= $cor ?>-400 text-<?= $cor ?>-700 rounded">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <?php if ($atrativoEditar): ?>
                <input type="hidden" name="id" value="<?= $atrativoEditar['atrativosid'] ?>" />
            <?php endif; ?>

            <div>
                <label class="block font-semibold" for="atrativosnome">Nome:</label>
                <input type="text" id="atrativosnome" name="atrativosnome" required
                    value="<?= $atrativoEditar ? htmlspecialchars($atrativoEditar['atrativosnome']) : '' ?>"
                    class="w-full border px-4 py-2 rounded" />
            </div>

            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                <?= $atrativoEditar ? 'Atualizar' : 'Cadastrar' ?>
            </button>
        </form>

        <hr class="my-6" />

        <h2 class="text-xl font-bold mb-4">Atrativos Cadastrados</h2>

        <table class="w-full table-auto border">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-2 text-left">ID</th>
                    <th class="p-2 text-left">Nome</th>
                    <th class="p-2 text-left">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($atrativos as $atrativo): ?>
                    <tr class="border-t">
                        <td class="p-2"><?= $atrativo['atrativosid'] ?></td>
                        <td class="p-2"><?= htmlspecialchars($atrativo['atrativosnome']) ?></td>
                        <td class="p-2">
                            <a class="text-blue-600 hover:underline" href="?editar=<?= $atrativo['atrativosid'] ?>">Editar</a> |
                            <a class="text-red-600 hover:underline" 
                               href="../controllers/atrativos_controller.php?delete=<?= $atrativo['atrativosid'] ?>" 
                               onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>