<script>
  function toggleMenu() {
    const menu = document.getElementById('mobile-menu');
    menu.classList.toggle('hidden');
    const btn = document.getElementById('menu-button');
    const expanded = btn.getAttribute('aria-expanded') === 'true';
    btn.setAttribute('aria-expanded', !expanded);
  }
</script>

<!-- Header -->
<header class="bg-[#363636] text-white py-5 sm:py-6 relative z-50">
  <div class="max-w-5xl mx-auto px-4 flex items-center justify-between sm:justify-center sm:space-x-6">
    <h1 class="font-extrabold text-3xl md:text-4xl text-white drop-shadow-lg tracking-wide uppercase select-none">
      Vamos Sair Hoje!
    </h1>
    <button id="menu-button" aria-label="Abrir menu" aria-expanded="false" aria-controls="mobile-menu"
      class="sm:hidden text-white text-2xl focus:outline-none" onclick="toggleMenu()">
      <i class="fas fa-bars"></i>
    </button>
    <a href="views/navegacao_forms.php"
      class="hidden sm:inline-flex border border-white bg-white bg-opacity-20 hover:bg-opacity-40 text-white px-4 py-2 rounded text-sm font-medium transition items-center whitespace-nowrap backdrop-blur-sm">
      <i class="fas fa-cog mr-1"></i>Administração
    </a>
  </div>

  <nav id="mobile-menu"
    class="sm:hidden hidden absolute top-full left-0 right-0 py-1 px-4 max-w-5xl mx-auto bg-[#2b2b2b]  shadow-lg z-50">
    <a href="views/navegacao_forms.php"
      class="block border border-white bg-white bg-opacity-20 hover:bg-opacity-40 text-white rounded text-sm font-medium transition items-center whitespace-nowrap backdrop-blur-sm px-4 py-2 mt-2">
      <i class="fas fa-cog mr-2"></i>Administração
    </a>
    <form class="mt-4 flex flex-col gap-4 pb-4" aria-label="Formulário de filtro de eventos">
      <select aria-label="Cidade do evento"
        class="rounded-md border border-gray-300 px-4 py-2 text-base text-black min-w-full">
        <option selected disabled>Cidade do evento</option>
        <option>São Paulo</option>
        <option>Rio de Janeiro</option>
        <option>Belo Horizonte</option>
        <option>Curitiba</option>
      </select>
      <input type="date" aria-label="Data inicial"
        class="rounded-md border border-gray-300 px-4 py-2 text-base text-black min-w-full" />
      <input type="date" aria-label="Data final"
        class="rounded-md border border-gray-300 px-4 py-2 text-base text-black min-w-full" />
      <select aria-label="Tipo de Evento"
        class="rounded-md border border-gray-300 px-4 py-2 text-base text-black min-w-full">
        <option selected disabled>Tipo de evento</option>
        <option>Shows</option>
        <option>Bailes</option>
        <option>Teatro</option>
        <option>Esporte</option>
        <option>Exposições</option>
        <option>Festivais</option>
      </select>
      <button type="submit"
        class="bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md px-5 py-2 w-full flex items-center justify-center space-x-2">
        <i class="fas fa-search"></i>
        <span>Pesquisar</span>
      </button>
    </form>
  </nav>

  <form class="hidden sm:flex mt-6 sm:mt-8 flex-col sm:flex-row justify-center gap-4 sm:gap-6 px-4 max-w-5xl mx-auto"
    aria-label="Formulário de filtro de eventos">
    <select aria-label="Cidade do evento"
      class="rounded-md border border-gray-300 px-4 py-2 text-base text-black min-w-[180px]">
      <option selected disabled>Cidade do evento</option>
      <option>São Paulo</option>
      <option>Rio de Janeiro</option>
      <option>Belo Horizonte</option>
      <option>Curitiba</option>
    </select>
    <input type="date" aria-label="Data inicial"
      class="rounded-md border border-gray-300 px-4 py-2 text-base text-black min-w-[160px]" />
    <input type="date" aria-label="Data final"
      class="rounded-md border border-gray-300 px-4 py-2 text-base text-black min-w-[160px]" />
    <select aria-label="Tipo de Evento"
      class="rounded-md border border-gray-300 px-4 py-2 text-base text-black min-w-[180px]">
      <option selected disabled>Tipo de evento</option>
      <option>Shows</option>
      <option>Bailes</option>
      <option>Teatro</option>
      <option>Esporte</option>
      <option>Exposições</option>
      <option>Festivais</option>
    </select>
    <button type="submit"
        class="bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md px-5 py-2 w-full flex items-center justify-center space-x-2">
        <i class="fas fa-search"></i>
        <span>Pesquisar</span>
      </button>
  </form>
</header>

<!-- Icones -->
<nav class="bg-[#f0f0f0] py-4">
    <ul
      class="max-w-5xl mx-auto flex flex-wrap justify-center gap-x-12 gap-y-3 text-center"
    >
      <li
        class="flex flex-col items-center text-gray-700 text-sm font-semibold hover:text-blue-600 hover:scale-110 hover:cursor-pointer transition-transform duration-200"
      >
        <i class="fas fa-music text-blue-600 text-2xl md:text-3xl mb-1"></i>
        Shows
      </li>
      <li
        class="flex flex-col items-center text-gray-700 text-sm font-semibold hover:text-blue-600 hover:scale-110 hover:cursor-pointer transition-transform duration-200"
      >
        <i class="fas fa-compact-disc text-blue-600 text-2xl md:text-3xl mb-1"></i>
        Bailes
      </li>
      <li
        class="flex flex-col items-center text-gray-700 text-sm font-semibold hover:text-blue-600 hover:scale-110 hover:cursor-pointer transition-transform duration-200"
      >
        <i class="fas fa-theater-masks text-blue-600 text-2xl md:text-3xl mb-1"></i>
        Teatro
      </li>
      <li
        class="flex flex-col items-center text-gray-700 text-sm font-semibold hover:text-blue-600 hover:scale-110 hover:cursor-pointer transition-transform duration-200"
      >
        <i class="fas fa-futbol text-blue-600 text-2xl md:text-3xl mb-1"></i>
        Esporte
      </li>
      <li
        class="flex flex-col items-center text-gray-700 text-sm font-semibold hover:text-blue-600 hover:scale-110 hover:cursor-pointer transition-transform duration-200"
      >
        <i class="fas fa-paint-brush text-blue-600 text-2xl md:text-3xl mb-1"></i>
        Exposições
      </li>
      <li
        class="flex flex-col items-center text-gray-700 text-sm font-semibold hover:text-blue-600 hover:scale-110 hover:cursor-pointer transition-transform duration-200"
      >
        <i class="fas fa-fire text-blue-600 text-2xl md:text-3xl mb-1"></i>
        Festivais
      </li>
    </ul>
  </nav>