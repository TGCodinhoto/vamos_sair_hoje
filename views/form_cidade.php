<?php
require_once('../controllers/cidade_controller.php');
require_once('../controllers/estado_controller.php');

$mensagem = '';
$cor = 'green';
$cidadeEditar = null;

if (isset($_GET['editar'])) {
    $cidadeEditar = buscarCidadePorId($_GET['editar']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['cidadenome'];
    $estadoid = $_POST['estadoid'];

    if (!empty($_POST['id'])) {
        $atualizou = atualizarCidade($_POST['id'], $nome, $estadoid);
        $mensagem = $atualizou ? "updated" : "error";
    } else {
        $inseriu = criarCidade($nome, $estadoid);
        $mensagem = $inseriu ? "created" : "error";
    }

    header("Location: form_cidade.php?msg=" . $mensagem);
    exit;
}

if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'created') {
        $mensagem = "Cidade cadastrada com sucesso!";
    } elseif ($_GET['msg'] === 'updated') {
        $mensagem = "Cidade atualizada com sucesso!";
    } elseif ($_GET['msg'] === 'deleted') {
        $mensagem = "Cidade excluída com sucesso!";
    } elseif ($_GET['msg'] === 'error') {
        $cor = 'red';
        $mensagem = isset($_GET['erro']) ? $_GET['erro'] : "Erro ao processar a solicitação.";
    }
}

$cidades = listarCidades();
$estados = listarEstados();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <title>Gerenciar Cidades</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-900 p-8">
    <div class="max-w-5xl mx-auto bg-white p-8 rounded shadow">
        <a href="navegacao_forms.php" class="inline-block mb-6 px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition">&larr; Voltar</a>
        <h1 class="text-4xl md:text-5xl font-bold mb-8 text-center text-blue-600"><?= $cidadeEditar ? 'Editar' : 'Cadastrar' ?> Cidade</h1>

        <?php if (!empty($mensagem)): ?>
            <div class="mb-4 p-4 bg-<?= $cor ?>-100 border border-<?= $cor ?>-400 text-<?= $cor ?>-700 rounded">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <?php if ($cidadeEditar): ?>
                <input type="hidden" name="id" value="<?= $cidadeEditar['cidadeid'] ?>" />
            <?php endif; ?>

            <div>
                <label class="block font-semibold" for="cidadenome">Nome da Cidade:</label>
                <input type="text" id="cidadenome" name="cidadenome" required
                    value="<?= $cidadeEditar ? htmlspecialchars($cidadeEditar['cidadenome']) : '' ?>"
                    class="w-full border px-4 py-2 rounded" />
            </div>

            <div>
                <label class="block font-semibold" for="estadoid">Estado:</label>
                <select name="estadoid" id="estadoid" required class="w-full border px-4 py-2 rounded">
                    <option value="">Selecione um estado</option>
                    <?php foreach ($estados as $estado): ?>
                        <option value="<?= $estado['estadoid'] ?>"
                            <?= $cidadeEditar && $cidadeEditar['estadoid'] == $estado['estadoid'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($estado['estadonome']) ?> (<?= $estado['estadosigla'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                <?= $cidadeEditar ? 'Atualizar' : 'Cadastrar' ?>
            </button>
        </form>

        <hr class="my-6" />

        <h2 class="text-xl font-bold mb-4">Cidades Cadastradas</h2>

        <table class="w-full table-auto border">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-2 text-left">ID</th>
                    <th class="p-2 text-left">Cidade</th>
                    <th class="p-2 text-left">Estado</th>
                    <th class="p-2 text-left">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cidades as $cidade): ?>
                    <tr class="border-t">
                        <td class="p-2"><?= $cidade['cidadeid'] ?></td>
                        <td class="p-2"><?= htmlspecialchars($cidade['cidadenome']) ?></td>
                        <td class="p-2"><?= htmlspecialchars($cidade['estadonome']) ?> (<?= $cidade['estadosigla'] ?>)</td>
                        <td class="p-2">
                            <a class="text-blue-600 hover:underline" href="?editar=<?= $cidade['cidadeid'] ?>">Editar</a> |
                            <a class="text-red-600 hover:underline"
                               href="../controllers/cidade_controller.php?delete=<?= $cidade['cidadeid'] ?>"
                               onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>