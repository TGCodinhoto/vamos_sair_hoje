<!-- Carrossel Principal -->

 <section class="relative w-full my-10 rounded-lg shadow-sm overflow-hidden relative max-w-6xl mx-auto">
     <div class="overflow-hidden relative shadow-lg">
         <div class="relative w-full h-[400px] sm:h-80 md:h-96 lg:h-[36rem]" id="carousel">
             <img alt="Banner 1" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-700 opacity-0 z-0" data-index="3" height="600" src="image/image01.webp" width="1200" />
             <img alt="Banner 2" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-700 opacity-100 z-10" data-index="0" height="600" src="image/image02.webp" width="1200" />
             <img alt="Banner 3" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-700 opacity-0 z-0" data-index="1" height="600" src="image/image03.webp" width="1200" />
             <img alt="Banner 4" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-700 opacity-0 z-0" data-index="2" height="600" src="image/image04.webp" width="1200"/>
         </div>
         <button aria-label="Foto anterior" class="absolute top-1/2 left-2 sm:left-4 -translate-y-1/2 text-white p-2 sm:p-3 z-40" id="prevBtn">
             <i class="fas fa-chevron-left text-base sm:text-lg">
             </i>
         </button>
         <button aria-label="Próxima foto" class="absolute top-1/2 right-2 sm:right-4 -translate-y-1/2 text-white p-2 sm:p-3 z-40" id="nextBtn">
             <i class="fas fa-chevron-right text-base sm:text-lg">
             </i>
         </button>
         <div class="absolute inset-0 flex flex-col justify-center items-center px-4 text-center max-w-4xl mx-auto text-white drop-shadow-lg z-20">
            <img src="LogoVinceBranca.png" alt="" class="w-[200px] sm:w-[350px] md:w-[400px]">
             <!-- <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold leading-tight md:leading-relaxed [text-shadow:2px_2px_4px_rgba(0,0,0,0.5)]">
                 Organize Suas Finanças <br> Consulte o Crédito Ideal!
             </h1> -->
         </div>
         <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex space-x-1.5 z-30" id="indicators">
             <button aria-label="Ir para foto 1" class="w-2 h-2 rounded-full bg-white bg-opacity-80 hover:bg-opacity-100 focus:outline-none focus:ring-2 focus:ring-white" data-index="0" type="button"></button>
             <button aria-label="Ir para foto 2" class="w-2 h-2 rounded-full bg-white bg-opacity-50 hover:bg-opacity-80 focus:outline-none focus:ring-2 focus:ring-white" data-index="1" type="button"></button>
             <button aria-label="Ir para foto 3" class="w-2 h-2 rounded-full bg-white bg-opacity-50 hover:bg-opacity-80 focus:outline-none focus:ring-2 focus:ring-white" data-index="2" type="button"></button>
             <button aria-label="Ir para foto 4" class="w-2 h-2 rounded-full bg-white bg-opacity-50 hover:bg-opacity-80 focus:outline-none focus:ring-2 focus:ring-white" data-index="3" type="button"></button>
         </div>
     </div>
 </section>

 <script>
     (() => {
         const images = document.querySelectorAll('#carousel img');
         const indicators = document.querySelectorAll('#indicators button');
         const prevBtn = document.getElementById('prevBtn');
         const nextBtn = document.getElementById('nextBtn');
         let currentIndex = 0;
         const total = images.length;
         let interval;

         function showImage(index) {
             images.forEach((img, i) => {
                 if (i === index) {
                     img.classList.remove('opacity-0', 'z-0');
                     img.classList.add('opacity-100', 'z-10');
                 } else {
                     img.classList.remove('opacity-100', 'z-10');
                     img.classList.add('opacity-0', 'z-0');
                 }
             });
             indicators.forEach((btn, i) => {
                 if (i === index) {
                     btn.classList.remove('bg-opacity-50');
                     btn.classList.add('bg-opacity-80');
                 } else {
                     btn.classList.remove('bg-opacity-80');
                     btn.classList.add('bg-opacity-50');
                 }
             });
             currentIndex = index;
         }

         function nextImage() {
             let next = currentIndex + 1;
             if (next >= total) next = 0;
             showImage(next);
         }

         function prevImage() {
             let prev = currentIndex - 1;
             if (prev < 0) prev = total - 1;
             showImage(prev);
         }

         prevBtn.addEventListener('click', () => {
             prevImage();
             resetInterval();
         });

         nextBtn.addEventListener('click', () => {
             nextImage();
             resetInterval();
         });

         indicators.forEach((btn) => {
             btn.addEventListener('click', () => {
                 const index = Number(btn.getAttribute('data-index'));
                 showImage(index);
                 resetInterval();
             });
         });

         function resetInterval() {
             clearInterval(interval);
             interval = setInterval(nextImage, 5000);
         }

         showImage(0);
         interval = setInterval(nextImage, 5000);
         
     })();
 </script>