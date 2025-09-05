<?php
session_start();

// Verifica se o usuário está logado e é do tipo 1 (adm) ou 2 (estabelecimento)
if (!isset($_SESSION['userid'])) {
    header("Location: ../views/login.php");
    exit();
}

// Converte para inteiro para garantir comparação correta
$tipo = intval($_SESSION['usertipo']);
if ($tipo !== 1 && $tipo !== 2) {
    header("Location: login.php");
    exit();
}

require_once('../controllers/categoria_controller.php');

$mensagem = '';
$cor = 'green';
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
<html class="scroll-smooth" lang="pt-BR">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <title>Cadastro - Categoria</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  </link>

  <style>
    #botoes {
      font-family: 'Montserrat', sans-serif;
    }
  </style>
</head>

<body class="bg-gray-100 text-gray-900 p-8">


  <!-- Botões Superiores Voltar e Home -->
  <div class="flex justify-center space-x-4 mb-6" id="botoes">
    <a href="navegacao_forms.php"
      class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition flex items-center space-x-2">
      <i class="fas fa-arrow-left"></i>
      <span>Voltar</span>
    </a>
    <a href="../index.php"
      class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition flex items-center space-x-2">
      <i class="fas fa-home"></i>
      <span>Home</span>
    </a>
  </div>

  <div class="max-w-5xl mx-auto bg-white p-8 rounded shadow">
    <h1 class="text-4xl md:text-5xl font-bold mb-8 text-center text-blue-600">
      <?= $categoriaEditar ? 'Editar' : 'Cadastrar' ?> Categoria
    </h1>

    <?php
    if (isset($_GET['msg'])) {
      if ($_GET['msg'] === 'created') {
        $mensagem = "Categoria cadastrada com sucesso!";
      } elseif ($_GET['msg'] === 'updated') {
        $mensagem = "Categoria atualizada com sucesso!";
      } elseif ($_GET['msg'] === 'deleted') {
        $mensagem = "Categoria excluída com sucesso!";
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
              <a class="text-red-600 hover:underline"
                href="../controllers/categoria_controller.php?delete=<?= $categoria['categoriaid'] ?>"
                onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>

</html>