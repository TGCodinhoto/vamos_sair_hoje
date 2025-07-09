<script>
	// JavaScript para o carrossel automático
	const carouselSlides = document.querySelectorAll('.carousel-slide');
	let currentSlide = 0;
	const slideIntervals = [3000, 5000, 4000]; // Tempos em milissegundos para cada slide

	function nextSlide() {
		carouselSlides[currentSlide].style.display = 'none';
		currentSlide = (currentSlide + 1) % carouselSlides.length;
		carouselSlides[currentSlide].style.display = 'block';
		setTimeout(nextSlide, slideIntervals[currentSlide]);
	}

	// Inicia o carrossel após o carregamento da página
	window.onload = function() {
		if (carouselSlides.length > 0) {
			carouselSlides[0].style.display = 'block'; // Mostra o primeiro slide
			setTimeout(nextSlide, slideIntervals[0]);
		}
	};
</script>