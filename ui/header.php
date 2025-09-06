<?php
ob_start(); // Inicia o buffer de saída

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../conexao.php';
require_once __DIR__ . '/../models/tipo_evento_model.php';
require_once __DIR__ . '/../models/cidade_model.php';

$conexao = Conexao::getInstance();
$tipoEventoModel = new TipoEventoModel($conexao);
$tipoEvento = $tipoEventoModel->listar();
$cidadeModel = new CidadeModel($conexao);
$cidades = $cidadeModel->listar();
?>

<!-- Header -->
<header class="bg-[#1B3B57] text-white py-5 sm:py-6 relative z-50">
  <div class="max-w-5xl mx-auto px-4 flex items-center justify-between sm:justify-center sm:space-x-6">

    <div class="flex items-center space-x-2 sm:space-x-4">
      <a href="index.php"><img src="./image/LogoVSHoje.png" alt="Logo Vamos Sair Hoje" class="w-16 sm:w-28 "></a>
      <h1 class="font-bold text-xl md:text-4xl text-white drop-shadow-lg tracking-wide uppercase select-none">
        Vamos Sair Hoje!
      </h1>
    </div>

    <button id="menu-button" aria-label="Abrir menu" aria-expanded="false" aria-controls="mobile-menu"
      class="sm:hidden text-white text-2xl focus:outline-none" onclick="toggleMenu()">
      <i class="fas fa-bars"></i>
    </button>

    <?php if (isset($_SESSION['userid'])): ?>
      <div class="hidden sm:flex items-center gap-4">
        <?php if ($_SESSION['usertipo'] == 2 || $_SESSION['usertipo'] == 1): ?>
          <a href="views/navegacao_forms.php"
            class="border border-white bg-white bg-opacity-20 hover:bg-opacity-40 text-white px-4 py-2 rounded text-sm font-medium transition items-center whitespace-nowrap backdrop-blur-sm">
            <i class="fas fa-cog mr-1"></i>Administração
          </a>
        <?php endif; ?>
        <span class="text-white mr-2">Olá, <?= htmlspecialchars($_SESSION['usernome']) ?></span>
        <a href="views/logout.php" class="text-white hover:text-gray-200">
          <i class="fas fa-sign-out-alt"></i> Sair
        </a>
      </div>
    <?php else: ?>
      <div class="hidden sm:flex items-center space-x-4">
        <a href="views/login.php" class="text-white hover:text-gray-200">
          <i class="fas fa-sign-in-alt mr-1"></i> Login
        </a>
      </div>
    <?php endif; ?>
  </div>

  <!-- Mobile -->
  <nav id="mobile-menu"
    class="sm:hidden hidden absolute top-full left-0 right-0 py-1 px-4 max-w-5xl mx-auto bg-[#1B3B57] shadow-lg z-50 rounded-b-md">
    <?php if (isset($_SESSION['userid'])): ?>
      <div class="flex flex-col gap-2 mt-2 mb-4">
        <?php if ($_SESSION['usertipo'] == 2): ?>
          <a href="views/navegacao_forms.php"
            class="block border border-white bg-white bg-opacity-20 hover:bg-opacity-40 text-white rounded text-sm font-medium transition items-center whitespace-nowrap backdrop-blur-sm px-4 py-2 mt-2">
            <i class="fas fa-cog mr-2"></i>Administração
          </a>
        <?php endif; ?>
        <span class="text-white">Olá, <?= htmlspecialchars($_SESSION['usernome']) ?></span>
        <a href="views/logout.php" class="text-white hover:text-gray-200 flex items-center">
          <i class="fas fa-sign-out-alt mr-2"></i> Sair
        </a>
      </div>
    <?php else: ?>
      <div class="flex flex-col gap-2 mt-2 mb-4">
        <a href="views/login.php" class="text-white hover:text-gray-200 flex items-center">
          <i class="fas fa-sign-in-alt mr-2"></i> Login
        </a>
      </div>
    <?php endif; ?>
    <form class="mt-4 flex flex-col gap-4 pb-4" aria-label="Formulário de filtro de eventos">
      <form method="GET" action="index.php" class="mt-4 flex flex-col gap-4 pb-4" aria-label="Formulário de filtro de eventos">
        <?php
        $cidadeSelecionada = $_GET['cidade'] ?? '';
        $dataInicialSelecionada = $_GET['data_inicial'] ?? '';
        $dataFinalSelecionada = $_GET['data_final'] ?? '';
        $tipoEventoSelecionado = $_GET['tipo_evento'] ?? '';
        ?>
        <select name="cidade" aria-label="Cidade do evento"
          class="rounded-md border border-gray-300 px-4 py-2 text-base text-black min-w-full">
          <option value=""<?= $cidadeSelecionada === '' ? ' selected' : '' ?>>Todas as cidades</option>
          <?php foreach ($cidades as $cidade): ?>
            <option value="<?= htmlspecialchars($cidade['cidadeid']) ?>"<?= $cidadeSelecionada == $cidade['cidadeid'] ? ' selected' : '' ?>>
              <?= htmlspecialchars($cidade['cidadenome']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <input type="date" name="data_inicial" aria-label="Data inicial"
          class="rounded-md border border-gray-300 px-4 py-2 text-base text-black min-w-full" value="<?= htmlspecialchars($dataInicialSelecionada) ?>" />
        <input type="date" name="data_final" aria-label="Data final"
          class="rounded-md border border-gray-300 px-4 py-2 text-base text-black min-w-full" value="<?= htmlspecialchars($dataFinalSelecionada) ?>" />
        <!-- <select name="tipo_evento" aria-label="Tipo de Evento"
          class="rounded-md border border-gray-300 px-4 py-2 text-base text-black min-w-full">
          <option selected disabled value="">Tipo de evento</option>
          <?php foreach ($tipoEvento as $tipo): ?>
            <option value="<?= htmlspecialchars($tipo['tipoeventoid']) ?>">
              <?= htmlspecialchars($tipo['tipoeventonome']) ?>
            </option>
          <?php endforeach; ?>
        </select> -->
          <button type="submit"
            class="bg-white bg-opacity-20 hover:bg-opacity-40 text-white font-medium rounded-md px-4 py-2 flex items-center justify-center space-x-2">
            <i class="fas fa-search"></i>
            <span>Pesquisar</span>
          </button>
      </form>
  </nav>

  <form class="hidden sm:flex mt-6 sm:mt-8 flex-col sm:flex-row justify-center gap-4 sm:gap-6 px-4 max-w-6xl mx-auto"
    aria-label="Formulário de filtro de eventos">
    <form method="GET" action="index.php" class="hidden sm:flex mt-6 sm:mt-8 flex-col sm:flex-row justify-center gap-4 sm:gap-6 px-4 max-w-6xl mx-auto">
      <?php
      $cidadeSelecionada = $_GET['cidade'] ?? '';
      $dataInicialSelecionada = $_GET['data_inicial'] ?? '';
      $dataFinalSelecionada = $_GET['data_final'] ?? '';
      $tipoEventoSelecionado = $_GET['tipo_evento'] ?? '';
      ?>
      <select name="cidade" aria-label="Cidade do evento"
        class="rounded-md border border-gray-300 px-4 py-2 text-base text-black min-w-[220px]">
  <option value=""<?= $cidadeSelecionada === '' ? ' selected' : '' ?>>Todas as cidades</option>
        <?php foreach ($cidades as $cidade): ?>
          <option value="<?= htmlspecialchars($cidade['cidadeid']) ?>"<?= $cidadeSelecionada == $cidade['cidadeid'] ? ' selected' : '' ?>>
            <?= htmlspecialchars($cidade['cidadenome']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <input type="date" name="data_inicial" aria-label="Data inicial"
        class="rounded-md border border-gray-300 px-4 py-2 text-base text-black min-w-[200px]" value="<?= htmlspecialchars($dataInicialSelecionada) ?>" />
      <input type="date" name="data_final" aria-label="Data final"
        class="rounded-md border border-gray-300 px-4 py-2 text-base text-black min-w-[200px]" value="<?= htmlspecialchars($dataFinalSelecionada) ?>" />
      <!-- <select name="tipo_evento" aria-label="Tipo de Evento"
        class="rounded-md border border-gray-300 px-4 py-2 text-base text-black min-w-[220px]">
        <option selected disabled value="">Tipo de evento</option>
        <?php foreach ($tipoEvento as $tipo): ?>
          <option value="<?= htmlspecialchars($tipo['tipoeventoid']) ?>">
            <?= htmlspecialchars($tipo['tipoeventonome']) ?>
          </option>
        <?php endforeach; ?>
      </select> -->
      <div class="flex gap-2">
        <button type="submit"
          class="bg-white bg-opacity-20 hover:bg-opacity-40 text-white font-medium rounded-md px-6 py-2  flex items-center justify-center space-x-2 shrink-0">
          <i class="fas fa-search"></i>
          <span>Pesquisar</span>
        </button>
      </div>
    </form>

</header>

<!-- Icones -->
<nav class="bg-[#f0f0f0] py-3 sm:py-4 md:py-6">
  <ul class="max-w-5xl mx-auto flex flex-wrap justify-center gap-x-6 gap-y-4 md:gap-x-10 md:gap-y-6 px-4 text-center">
    <?php
    $cidadeSelecionada = $_GET['cidade'] ?? '';
    $dataInicialSelecionada = $_GET['data_inicial'] ?? '';
    $dataFinalSelecionada = $_GET['data_final'] ?? '';
    foreach ($tipoEvento as $tipo):
      $url = 'index.php?';
      if ($cidadeSelecionada !== '') $url .= 'cidade=' . urlencode($cidadeSelecionada) . '&';
      if ($dataInicialSelecionada !== '') $url .= 'data_inicial=' . urlencode($dataInicialSelecionada) . '&';
      if ($dataFinalSelecionada !== '') $url .= 'data_final=' . urlencode($dataFinalSelecionada) . '&';
      $url .= 'tipo_evento=' . urlencode($tipo['tipoeventoid']);
    ?>
      <li>
        <a href="<?= $url ?>"
          class="flex flex-col items-center text-gray-700 text-sm md:text-base font-semibold hover:text-[#1B3B57] hover:scale-105 hover:cursor-pointer transition-transform duration-200 w-20 sm:w-24 md:w-auto"
          title="Filtrar por <?= htmlspecialchars($tipo['tipoeventonome']) ?>">
          <?php
          $img = $tipo['tipoeventoimage'] ?? '';
          $imgInvalida = empty($img) || $img === null || substr($img, 0, 1) === '[';
          if (!$imgInvalida && file_exists(__DIR__ . '/../uploads/' . $img)) {
            echo '<img src="uploads/' . htmlspecialchars($img) . '" class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 mb-1 object-contain" />';
          } else {
            // Imagem padrão para tipos sem imagem
            echo '<img src="image/favicon.svg" class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 mb-1 object-contain opacity-60" alt="Tipo de evento" />';
          }
          ?>
          <span class="truncate"><?= htmlspecialchars($tipo['tipoeventonome']) ?></span>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
</nav>


<script>
  function toggleMenu() {
    const menu = document.getElementById('mobile-menu');
    menu.classList.toggle('hidden');
    const btn = document.getElementById('menu-button');
    const expanded = btn.getAttribute('aria-expanded') === 'true';
    btn.setAttribute('aria-expanded', !expanded);
  }
</script>