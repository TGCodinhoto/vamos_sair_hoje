
<!-- Carrossel de Propaganda -->
<section class="bg-gray-200 text-center my-10 rounded-lg shadow-md overflow-hidden relative"> <!-- Removido o P-5 -->
    <div class="flex transition-transform duration-700 ease-in-out carousel-track">
        <img src="image/image01.webp" alt="Banner de Propaganda 1"
            class="min-w-full h-72 object-cover rounded-lg carousel-banner">
        <img src="image/image02.webp" alt="Banner de Propaganda 2"
            class="min-w-full h-72 object-cover rounded-lg carousel-banner">
        <img src="image/image03.webp" alt="Banner de Propaganda 3"
            class="min-w-full h-72 object-cover rounded-lg carousel-banner">
    </div>
    <div class="carousel-dots" ></div> <!-- Controle Carrossel -->
    <!-- <div class="flex justify-center mt-4 carousel-dots"></div> -->
</section>


<script>
    // JS Carrossel
document.addEventListener('DOMContentLoaded', () => {
    // --- Lógica do Carrossel de Propaganda ---
    const carouselTrack = document.querySelector('.carousel-track');
    const carouselBanners = document.querySelectorAll('.carousel-banner');
    const carouselDotsContainer = document.querySelector('.carousel-dots');
    const totalBanners = carouselBanners.length;
    let currentIndex = 0;
    let intervalId;
    const transitionTimes = [4000, 3000, 5000]; // Tempos em milissegundos para cada banner

    // Cria os indicadores (bolinhas)
    for (let i = 0; i < totalBanners; i++) {
        const dot = document.createElement('span');
        dot.classList.add('dot');
        dot.addEventListener('click', () => {
            clearInterval(intervalId); // Limpa o intervalo atual
            goToSlide(i);
            startCarousel(); // Reinicia o carrossel
        });
        carouselDotsContainer.appendChild(dot);
    }

    const dots = document.querySelectorAll('.dot');

    // Função para ir para um slide específico
    function goToSlide(index) {
        currentIndex = index;
        const offset = -currentIndex * 100; // Move o track 100% para cada slide
        carouselTrack.style.transform = `translateX(${offset}%)`;
        updateDots();
    }

    // Atualiza o estado dos indicadores
    function updateDots() {
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentIndex);
        });
    }

    // Função para avançar para o próximo slide
    function nextSlide() {
        currentIndex = (currentIndex + 1) % totalBanners;
        goToSlide(currentIndex);
    }

    // Inicia o carrossel com tempos variáveis
    function startCarousel() {
        if (intervalId) {
            clearInterval(intervalId); // Limpa qualquer intervalo anterior
        }
        intervalId = setTimeout(() => {
            nextSlide();
            startCarousel(); // Chama recursivamente para o próximo slide
        }, transitionTimes[currentIndex]);
    }

    // Inicializa o carrossel
    goToSlide(0); // Garante que o primeiro slide esteja visível
    startCarousel(); // Inicia a transição automática

    // --- Lógica da Pesquisa (apenas feedback visual/console) ---
    const searchButton = document.getElementById('search-button');
    const searchCity = document.getElementById('search-city');
    const searchStartDate = document.getElementById('search-start-date');
    const searchEndDate = document.getElementById('search-end-date');
    const searchType = document.getElementById('search-type');

    searchButton.addEventListener('click', () => {
        const city = searchCity.value;
        const startDate = searchStartDate.value;
        const endDate = searchEndDate.value;
        const type = searchType.value;

        // Aqui você enviaria esses dados para um backend real
        // Por enquanto, vamos apenas logar no console e dar um alerta
        console.log('Dados da pesquisa:');
        console.log('Cidade:', city);
        console.log('Data Inicial:', startDate);
        console.log('Data Final:', endDate);
        console.log('Tipo de Evento:', type === "" ? "Todos" : type);

        alert(`Pesquisa realizada!\nCidade: ${city || 'Não informada'}\nDe: ${startDate || 'Qualquer data'}\nAté: ${endDate || 'Qualquer data'}\nTipo: ${type || 'Todos'}\n\n(A funcionalidade de filtragem real de eventos requer um servidor e banco de dados.)`);
    });
});

</script>
