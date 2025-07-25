<?php
require_once('../controllers/estado_controller.php');

$mensagem = '';
$cor = 'green';
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'created') {
        $mensagem = "Estado cadastrado com sucesso!";
    } elseif ($_GET['msg'] === 'updated') {
        $mensagem = "Estado atualizado com sucesso!";
    } elseif ($_GET['msg'] === 'deleted') {
        $mensagem = "Estado excluído com sucesso!";
    } elseif ($_GET['msg'] === 'error') {
        $cor = 'red';
        $mensagem = isset($_GET['erro']) ? $_GET['erro'] : "Erro ao processar a solicitação.";
    }
}
?>
<?php if (!empty($mensagem)): ?>
    <div class="mb-4 p-4 bg-<?= $cor ?>-100 border border-<?= $cor ?>-400 text-<?= $cor ?>-700 rounded">
        <?= htmlspecialchars($mensagem) ?>
    </div>
<?php endif; ?>

<?php
$estadoEditar = null;

if (isset($_GET['editar'])) {
    $estadoEditar = buscarEstadoPorId($_GET['editar']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['estadonome'];
    $sigla = $_POST['estadosigla'];

    if (!empty($_POST['id'])) {
        $atualizou = atualizarEstado($_POST['id'], $nome, $sigla);
        $mensagem = $atualizou ? "Estado atualizado com sucesso!" : "Erro ao atualizar.";
    } else {
        $inseriu = criarEstado($nome, $sigla);
        $mensagem = $inseriu ? "Estado cadastrado com sucesso!" : "Erro ao cadastrar.";
    }

    header("Location: form_estado.php?mensagem=" . urlencode($mensagem));
    exit;
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
    <div class="max-w-5xl mx-auto bg-white p-8 rounded shadow">
        <a href="navegacao_forms.php" class="inline-block mb-6 px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition">&larr; Voltar</a>
        <h1 class="text-4xl md:text-5xl font-bold mb-8 text-center text-blue-600"><?= $estadoEditar ? 'Editar' : 'Cadastrar' ?> Estado</h1>

        <?php if (isset($_GET['mensagem'])): ?>
            <p class="mb-4 p-2 bg-green-100 text-green-700 rounded"><?= htmlspecialchars($_GET['mensagem']) ?></p>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <?php if ($estadoEditar): ?>
                <input type="hidden" name="id" value="<?= $estadoEditar['estadoid'] ?>" />
            <?php endif; ?>

            <div>
                <label class="block font-semibold" for="estadonome">Nome do Estado</label>
                <input type="text" id="estadonome" name="estadonome" required
                    value="<?= $estadoEditar ? htmlspecialchars($estadoEditar['estadonome']) : '' ?>"
                    class="w-full border px-4 py-2 rounded" />
            </div>

            <div>
                <label class="block font-semibold" for="estadosigla">Sigla do Estado</label>
                <input type="text" id="estadosigla" name="estadosigla" required maxlength="2"
                    value="<?= $estadoEditar ? htmlspecialchars($estadoEditar['estadosigla']) : '' ?>"
                    class="w-full border px-4 py-2 rounded uppercase" />
            </div>
            <div class="flex justify-between items-center mt-6">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                    <?= $estadoEditar ? 'Atualizar' : 'Cadastrar' ?>
                </button>
            </div>
        </form>

        <hr class="my-6" />

        <h2 class="text-xl font-bold mb-4">Estados Cadastrados</h2>

        <table class="w-full table-auto border">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-2 text-left">ID</th>
                    <th class="p-2 text-left">Nome</th>
                    <th class="p-2 text-left">Sigla</th>
                    <th class="p-2 text-left">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($estados as $estado): ?>
                    <tr class="border-t">
                        <td class="p-2"><?= $estado['estadoid'] ?></td>
                        <td class="p-2"><?= htmlspecialchars($estado['estadonome']) ?></td>
                        <td class="p-2"><?= htmlspecialchars($estado['estadosigla']) ?></td>
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