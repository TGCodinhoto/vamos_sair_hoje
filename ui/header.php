<?php
require_once __DIR__ . '/../conexao.php';
require_once __DIR__ . '/../models/tipo_evento_model.php';
require_once __DIR__ . '/../models/cidade_model.php';
$tipoEventoModel = new TipoEventoModel($conexao);
$tipoEvento = $tipoEventoModel->listar();
$cidadeModel = new CidadeModel($conexao);
$cidades = $cidadeModel->listar();
?>

<!-- Header -->

<header class="bg-[#1B3B57] text-white py-5 sm:py-6 relative z-50">
  <div class="max-w-5xl mx-auto px-4 flex items-center justify-between sm:justify-center sm:space-x-6">

    <div class="flex items-center space-x-2 sm:space-x-4">
      <img src="./image/LogoVSHoje.png" alt="Logo Vamos Sair Hoje" class="w-16 sm:w-28 ">
      <h1 class="font-bold text-xl md:text-4xl text-white drop-shadow-lg tracking-wide uppercase select-none">
        Vamos Sair Hoje!
      </h1>
    </div>

    <button id="menu-button" aria-label="Abrir menu" aria-expanded="false" aria-controls="mobile-menu"
      class="sm:hidden text-white text-2xl focus:outline-none" onclick="toggleMenu()">
      <i class="fas fa-bars"></i>
    </button>

    <a href="views/navegacao_forms.php"
      class="hidden sm:inline-flex border border-white bg-white bg-opacity-20 hover:bg-opacity-40 text-white px-4 py-2 rounded text-sm font-medium transition items-center whitespace-nowrap backdrop-blur-sm">
      <i class="fas fa-cog mr-1"></i>Administração
    </a>
  </div>

  <!-- Mobile -->
  <nav id="mobile-menu"
    class="sm:hidden hidden absolute top-full left-0 right-0 py-1 px-4 max-w-5xl mx-auto bg-[#1B3B57] shadow-lg z-50 rounded-b-md">
    <a href="views/navegacao_forms.php"
      class="block border border-white bg-white bg-opacity-20 hover:bg-opacity-40 text-white rounded text-sm font-medium transition items-center whitespace-nowrap backdrop-blur-sm px-4 py-2 mt-2">
      <i class="fas fa-cog mr-2"></i>Administração
    </a>
    <form class="mt-4 flex flex-col gap-4 pb-4" aria-label="Formulário de filtro de eventos">
      <select aria-label="Cidade do evento"
        class="rounded-md border border-gray-300 px-4 py-2 text-base text-black min-w-full">
        <option selected disabled>Cidade do evento</option>
        <?php foreach ($cidades as $cidade): ?>
          <option value="<?= htmlspecialchars($cidade['cidadeid']) ?>">
            <?= htmlspecialchars($cidade['cidadenome']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <input type="date" aria-label="Data inicial"
        class="rounded-md border border-gray-300 px-4 py-2 text-base text-black min-w-full" />
      <input type="date" aria-label="Data final"
        class="rounded-md border border-gray-300 px-4 py-2 text-base text-black min-w-full" />
      <!-- <select aria-label="Tipo de Evento"
          class="rounded-md border border-gray-300 px-4 py-2 text-base text-black min-w-full">
          <option selected disabled>Tipo de evento</option>
          <option>Shows</option>
          <option>Bailes</option>
          <option>Teatro</option>
          <option>Esporte</option>
          <option>Exposições</option>
          <option>Festivais</option>
        </select> -->
      <button type="submit"
        class="bg-gray-600 hover:bg-blue-700 text-white font-medium rounded-md px-4 py-2 flex items-center justify-center space-x-2">
        <i class="fas fa-search"></i>
        <span>Pesquisar</span>
      </button>
    </form>
  </nav>

  <form class="hidden sm:flex mt-6 sm:mt-8 flex-col sm:flex-row justify-center gap-4 sm:gap-6 px-4 max-w-6xl mx-auto"
    aria-label="Formulário de filtro de eventos">
    <select aria-label="Cidade do evento"
      class="rounded-md border border-gray-300 px-4 py-2 text-base text-black min-w-[220px]">
      <option selected disabled>Cidade do evento</option>
      <?php foreach ($cidades as $cidade): ?>
        <option value="<?= htmlspecialchars($cidade['cidadeid']) ?>">
          <?= htmlspecialchars($cidade['cidadenome']) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <input type="date" aria-label="Data inicial"
      class="rounded-md border border-gray-300 px-4 py-2 text-base text-black min-w-[200px]" />
    <input type="date" aria-label="Data final"
      class="rounded-md border border-gray-300 px-4 py-2 text-base text-black min-w-[200px]" />
    <!-- <select aria-label="Tipo de Evento"
        class="rounded-md border border-gray-300 px-4 py-2 text-base text-black min-w-[220px]">
        <option selected disabled>Tipo de evento</option>
        <option>Shows</option>
        <option>Bailes</option>
        <option>Teatro</option>
        <option>Esporte</option>
        <option>Exposições</option>
        <option>Festivais</option>
      </select> -->
    <button type="submit"
      class="bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-md px-6 py-2  flex items-center justify-center space-x-2 shrink-0">
      <i class="fas fa-search"></i>
      <span>Pesquisar</span>
    </button>
  </form>
  
</header>

<!-- Icones -->
<nav class="bg-[#f0f0f0] py-3 sm:py-4 md:py-6">
  <ul class="max-w-5xl mx-auto flex flex-wrap justify-center gap-x-6 gap-y-4 px-4 text-center">
    <?php foreach ($tipoEvento as $tipo): ?>
      <li
        class="flex flex-col items-center text-gray-700 text-xs sm:text-sm md:text-base font-semibold hover:text-[#1B3B57] hover:scale-105 hover:cursor-pointer transition-transform duration-200 w-20 sm:w-24 md:w-auto">
        <?php
          $img = $tipo['tipoeventoimage'] ?? '';
          $imgInvalida = empty($img) || $img === null || substr($img, 0, 1) === '[';
          if (!$imgInvalida && file_exists(__DIR__ . '/../uploads/' . $img)) {
            echo '<img src="uploads/' . htmlspecialchars($img) . '" class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 mb-1 object-contain" />';
            echo '<span class="truncate">' . htmlspecialchars($tipo['tipoeventonome']) . '</span>';
          }
        ?>
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