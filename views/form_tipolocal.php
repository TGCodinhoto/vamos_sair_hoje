<?php
require_once('../controllers/tipolocal_controller.php');

$mensagem = '';
$tipoLocalEditar = null;

if (isset($_GET['editar'])) {
  $tipoLocalEditar = buscarTipoLocalPorId($_GET['editar']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome = $_POST['tipolocalnome'];

  if (!empty($_POST['id'])) {
    $atualizou = atualizarTipoLocal($_POST['id'], $nome);
    $mensagem = $atualizou ? "Tipo de Local atualizado com sucesso!" : "Erro ao atualizar.";
    $tipoLocalEditar = buscarTipoLocalPorId($_POST['id']);
  } else {
    $inseriu = criarTipoLocal($nome);
    $mensagem = $inseriu ? "Tipo de Local cadastrado com sucesso!" : "Erro ao cadastrar.";
  }
}

$tiposLocal = listarTipoLocal();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <title>Gerenciar Tipo de Local</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-900 p-8">
  <!-- botao para voltar para navegacao_forms.php -->


  <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-lg">

    <h1 class="text-2xl font-bold mb-6"><?= $tipoLocalEditar ? 'Editar' : 'Cadastrar' ?> Tipo de Local</h1>

    <?php if (!empty($mensagem)): ?>
      <p class="mb-4 p-2 bg-green-100 text-green-700 rounded"><?= htmlspecialchars($mensagem) ?></p>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
      <?php if ($tipoLocalEditar): ?>
        <input type="hidden" name="id" value="<?= $tipoLocalEditar['tipolocalid'] ?>" />
      <?php endif; ?>

      <div>
        <label class="block font-semibold" for="tipolocalnome">Nome:</label>
        <input type="text" id="tipolocalnome" name="tipolocalnome" required
          value="<?= $tipoLocalEditar ? htmlspecialchars($tipoLocalEditar['tipolocalnome']) : '' ?>"
          class="w-full border px-4 py-2 rounded" />
      </div>


      <div class="flex justify-between items-center mt-6">
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
          <?= $tipoLocalEditar ? 'Atualizar' : 'Cadastrar' ?>
        </button>

        <button type="submit" class="bg-gray-600 text-white  px-6 py-2 rounded hover:bg-blue-700 transition">

          <a href="../views/navegacao_forms.php" class="text-white hover:underline">Voltar</a>
        </button>
      </div>
    </form>

    <hr class="my-6" />

    <h2 class="text-xl font-bold mb-4">Tipo de Local</h2>

    <table class="w-full table-auto border">
      <thead class="bg-gray-200">
        <tr>
          <th class="p-2 text-left">ID</th>
          <th class="p-2 text-left">Tipo de Local</th>
          <th class="p-2 text-left">Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($tiposLocal as $tipolocal): ?>
          <tr class="border-t">
            <td class="p-2"><?= $tipolocal['tipolocalid'] ?></td>
            <td class="p-2"><?= htmlspecialchars($tipolocal['tipolocalnome']) ?></td>
            <td class="p-2">
              <a class="text-blue-600 hover:underline" href="?editar=<?= $tipolocal['tipolocalid'] ?>">Editar</a> |
              <a class="text-red-600 hover:underline" href="../controllers/tipolocal_controller.php?delete=<?= $tipolocal['tipolocalid'] ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>

</html>