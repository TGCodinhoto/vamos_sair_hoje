<?php
require_once('../controllers/classificacao_etaria_controller.php');

$mensagem = '';
$classificacaoEtariaEditar = null;

if (isset($_GET['editar'])) {
    $classificacaoEtariaEditar = buscarClassificacaoEtariaPorId($_GET['editar']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['classificacaonome'] ?? '';

    if (!empty($_POST['id'])) {
        $atualizou = atualizarClassificacaoEtaria($_POST['id'], $nome);
        $mensagem = $atualizou ? "Classificação Etária atualizada com sucesso!" : "Erro ao atualizar.";
        $classificacaoEtariaEditar = buscarClassificacaoEtariaPorId($_POST['id']);
    } else {
        $inseriu = criarClassificacaoEtaria($nome);
        $mensagem = $inseriu ? "Classificação Etária cadastrada com sucesso!" : "Erro ao cadastrar.";
    }
}

$classificacoesEtarias = listarClassificacaoEtaria();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Gerenciar Classificação Etária</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900 p-8">
  <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold mb-6"><?= $classificacaoEtariaEditar ? 'Editar' : 'Cadastrar' ?> Classificação Etária</h1>

    <?php if (!empty($mensagem)): ?>
      <p class="mb-4 p-2 bg-green-100 text-green-700 rounded"><?= htmlspecialchars($mensagem) ?></p>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
      <?php if ($classificacaoEtariaEditar): ?>
        <input type="hidden" name="id" value="<?= $classificacaoEtariaEditar['classificacaoid'] ?>" />
      <?php endif; ?>

      <div>
        <label class="block font-semibold" for="classificacaonome">Nome:</label>
        <input type="text" id="classificacaonome" name="classificacaonome" required
          value="<?= $classificacaoEtariaEditar ? htmlspecialchars($classificacaoEtariaEditar['classificacaonome']) : '' ?>"
          class="w-full border px-4 py-2 rounded" />
      </div>

      <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
        <?= $classificacaoEtariaEditar ? 'Atualizar' : 'Cadastrar' ?>
      </button>
    </form>

    <hr class="my-6" />

    <h2 class="text-xl font-bold mb-4">Classificação Etária</h2>

    <table class="w-full table-auto border">
      <thead class="bg-gray-200">
        <tr>
          <th class="p-2 text-left">ID</th>
          <th class="p-2 text-left">Nome</th>
          <th class="p-2 text-left">Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($classificacoesEtarias as $classificacaoEtaria): ?>
          <tr class="border-t">
            <td class="p-2"><?= $classificacaoEtaria['classificacaoid'] ?></td>
            <td class="p-2"><?= htmlspecialchars($classificacaoEtaria['classificacaonome']) ?></td>
            <td class="p-2">
              <a class="text-blue-600 hover:underline" href="?editar=<?= $classificacaoEtaria['classificacaoid'] ?>">Editar</a> |
              <a class="text-red-600 hover:underline" href="../controllers/classificacao_etaria_controller.php?delete=<?= $classificacaoEtaria['classificacaoid'] ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
