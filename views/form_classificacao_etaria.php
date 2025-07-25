<?php
require_once('../controllers/classificacao_etaria_controller.php');

$mensagem = '';
$cor = 'green';
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'created') {
        $mensagem = "Classificação etária cadastrada com sucesso!";
    } elseif ($_GET['msg'] === 'updated') {
        $mensagem = "Classificação etária atualizada com sucesso!";
    } elseif ($_GET['msg'] === 'deleted') {
        $mensagem = "Classificação etária excluída com sucesso!";
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
$classificacaoEtariaEditar = null;

if (isset($_GET['editar'])) {
  $classificacaoEtariaEditar = buscarClassificacaoEtariaPorId($_GET['editar']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome = $_POST['classificacaonome'] ?? '';

  if (!empty($_POST['id'])) {
    $atualizou = atualizarClassificacaoEtaria($_POST['id'], $nome);
    $mensagem = $atualizou ? "Classificação Etária atualizada com sucesso!" : "Erro ao atualizar.";
  } else {
    $inseriu = criarClassificacaoEtaria($nome);
    $mensagem = $inseriu ? "Classificação Etária cadastrada com sucesso!" : "Erro ao cadastrar.";
  }

  header("Location: form_classificacao_etaria.php?mensagem=" . urlencode($mensagem));
  exit;
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
  <div class="max-w-5xl mx-auto bg-white p-8 rounded shadow">
    <a href="navegacao_forms.php" class="inline-block mb-6 px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition">&larr; Voltar</a>
    <h1 class="text-4xl md:text-5xl font-bold mb-8 text-center text-blue-600"><?= $classificacaoEtariaEditar ? 'Editar' : 'Cadastrar' ?> Classificação Etária</h1>

    <?php
    if (isset($_GET['mensagem'])):
    ?>
      <p class="mb-4 p-2 bg-green-100 text-green-700 rounded"><?= htmlspecialchars($_GET['mensagem']) ?></p>
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