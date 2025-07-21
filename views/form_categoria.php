<?php
require_once('../controllers/categoria_controller.php');

$mensagem = '';
$categoriaEditar = null;

if (isset($_GET['editar'])) {
  $categoriaEditar = buscarCategoriaPorId($_GET['editar']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome = $_POST['categorianome'];

  if (!empty($_POST['id'])) {
    $atualizou = atualizarCategoria($_POST['id'], $nome);
    $mensagem = $atualizou ? "Categoria atualizada com sucesso!" : "Erro ao atualizar.";
  } else {
    $inseriu = criarCategoria($nome);
    $mensagem = $inseriu ? "Categoria cadastrada com sucesso!" : "Erro ao cadastrar.";
  }

  header("Location: form_categoria.php?mensagem=" . urlencode($mensagem));
  exit;
}

$categorias = listarCategorias();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <title>Gerenciar Categoria</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-900 p-8">
  <div class="max-w-5xl mx-auto bg-white p-8 rounded shadow">
    <h1 class="text-2xl font-bold mb-6"><?= $categoriaEditar ? 'Editar' : 'Cadastrar' ?> Categoria</h1>

    <?php
    if (isset($_GET['mensagem'])):
    ?>
      <p class="mb-4 p-2 bg-green-100 text-green-700 rounded"><?= htmlspecialchars($_GET['mensagem']) ?></p>
    <?php endif; ?>


    <form method="POST" class="space-y-4">
      <?php if ($categoriaEditar): ?>
        <input type="hidden" name="id" value="<?= $categoriaEditar['categoriaid'] ?>" />
      <?php endif; ?>

      <div>
        <label class="block font-semibold" for="categorianome">Nome da Categoria:</label>
        <input type="text" id="categorianome" name="categorianome" required
          value="<?= $categoriaEditar ? htmlspecialchars($categoriaEditar['categorianome']) : '' ?>"
          class="w-full border px-4 py-2 rounded" />
      </div>

      <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
        <?= $categoriaEditar ? 'Atualizar' : 'Cadastrar' ?>
      </button>
    </form>

    <hr class="my-6" />

    <h2 class="text-xl font-bold mb-4">Categorias Cadastradas</h2>

    <table class="w-full table-auto border">
      <thead class="bg-gray-200">
        <tr>
          <th class="p-2 text-left">ID</th>
          <th class="p-2 text-left">Nome</th>
          <th class="p-2 text-left">Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($categorias as $categoria): ?>
          <tr class="border-t">
            <td class="p-2"><?= $categoria['categoriaid'] ?></td>
            <td class="p-2"><?= htmlspecialchars($categoria['categorianome']) ?></td>
            <td class="p-2">
              <a class="text-blue-600 hover:underline" href="?editar=<?= $categoria['categoriaid'] ?>">Editar</a> |
              <a class="text-red-600 hover:underline" href="../controllers/categoria_controller.php?delete=<?= $categoria['categoriaid'] ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>

</html>