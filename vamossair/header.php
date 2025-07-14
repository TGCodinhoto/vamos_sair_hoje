
<!-- Header -->
<header class="bg-[#363636] text-white text-center py-4">

  <!-- Titulo Principal -->
  <h1 class="font-extrabold text-xl md:text-3xl">Vamos Sair Hoje!</h1>

  <!-- Formulário Filtro -->
  <form class="mt-4 flex flex-col sm:flex-row justify-center gap-4 px-4 max-w-5xl mx-auto">
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
      class="bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md px-5 py-2 min-w-[100px]">
      Pesquisar
    </button>
  </form>

</header>

<!-- Icones -->
<nav class="bg-[#f0f0f0] py-4">
  <ul class="max-w-5xl mx-auto flex flex-wrap justify-center gap-x-12 gap-y-3 text-center">
    <li
      class="flex flex-col items-center text-gray-700 text-sm font-semibold hover:text-blue-600 hover:scale-110 hover:cursor-pointer">
      <i class="fas fa-music text-blue-600 text-2xl mb-1"></i>
      Shows
    </li>
    <li
      class="flex flex-col items-center text-gray-700 text-sm font-semibold hover:text-blue-600 hover:scale-110 hover:cursor-pointer">
      <i class="fas fa-compact-disc text-blue-600 text-2xl mb-1"></i>
      Bailes
    </li>
    <li
      class="flex flex-col items-center text-gray-700 text-sm font-semibold hover:text-blue-600 hover:scale-110 hover:cursor-pointer">
      <i class="fas fa-theater-masks text-blue-600 text-2xl mb-1"></i>
      Teatro
    </li>
    <li
      class="flex flex-col items-center text-gray-700 text-sm font-semibold hover:text-blue-600 hover:scale-110 hover:cursor-pointer">
      <i class="fas fa-futbol text-blue-600 text-2xl mb-1"></i>
      Esporte
    </li>
    <li
      class="flex flex-col items-center text-gray-700 text-sm font-semibold hover:text-blue-600 hover:scale-110 hover:cursor-pointer">
      <i class="fas fa-paint-brush text-blue-600 text-2xl mb-1"></i>
      Exposições
    </li>
    <li
      class="flex flex-col items-center text-gray-700 text-sm font-semibold hover:text-blue-600 hover:scale-110 hover:cursor-pointer">
      <i class="fas fa-fire text-blue-600 text-2xl mb-1"></i>
      Festivais
    </li>
  </ul>
</nav>