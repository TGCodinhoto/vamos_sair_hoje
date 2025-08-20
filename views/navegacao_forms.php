<html class="scroll-smooth" lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Navegação - Formulários</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

    <style>
        #botoes {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen px-2 sm:px-6 py-6 relative">

    <div class="max-w-4xl mx-auto">

        <div class="flex justify-center space-x-4 mb-6" id="botoes">
            <a href="../index.php"
                class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition flex items-center space-x-2">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
        </div>

        <div class="bg-white p-8 rounded shadow">
            <h1 class="text-4xl md:text-5xl font-bold mb-8 text-center text-blue-600">
                Gerenciamento de Dados
            </h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="listar_eventos.php"
                    class="block p-6 bg-gray-50 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    <h2 class="text-xl font-semibold text-blue-700 mb-2">Eventos Cadastrados</h2>
                    <p class="text-gray-600">Listar Eventos</p>
                </a>

                <a href="listar_locais.php"
                    class="block p-6 bg-gray-50 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    <h2 class="text-xl font-semibold text-blue-700 mb-2">Locais Cadastrados</h2>
                    <p class="text-gray-600">Listar Locais</p>
                </a>

                <a href="listar_anunciantes.php"
                    class="block p-6 bg-gray-50 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    <h2 class="text-xl font-semibold text-blue-700 mb-2">Anunciantes Cadastrados</h2>
                    <p class="text-gray-600">Listar Anunciantes</p>
                </a>

                <a href="form_segmento.php"
                    class="block p-6 bg-gray-50 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    <h2 class="text-xl font-semibold text-blue-700 mb-2">Segmento</h2>
                    <p class="text-gray-600">Gerenciar Segmentos</p>
                </a>

                <a href="form_atrativos.php"
                    class="block p-6 bg-gray-50 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    <h2 class="text-xl font-semibold text-blue-700 mb-2">Atrativos</h2>
                    <p class="text-gray-600">Gerenciar Atrativos</p>
                </a>

                <a href="form_estado.php"
                    class="block p-6 bg-gray-50 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    <h2 class="text-xl font-semibold text-blue-700 mb-2">Estado</h2>
                    <p class="text-gray-600">Gerenciar Estados</p>
                </a>

                <a href="form_cidade.php"
                    class="block p-6 bg-gray-50 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    <h2 class="text-xl font-semibold text-blue-700 mb-2">Cidades</h2>
                    <p class="text-gray-600">Gerenciar Cidades</p>
                </a>

                <a href="form_categoria.php"
                    class="block p-6 bg-gray-50 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    <h2 class="text-xl font-semibold text-blue-700 mb-2">Categoria</h2>
                    <p class="text-gray-600">Gerenciar Categorias</p>
                </a>

                <a href="form_formatoevento.php"
                    class="block p-6 bg-gray-50 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    <h2 class="text-xl font-semibold text-blue-700 mb-2">Formato do Evento</h2>
                    <p class="text-gray-600">Gerenciar Formatos de Evento</p>
                </a>

                <a href="form_classificacao_etaria.php"
                    class="block p-6 bg-gray-50 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    <h2 class="text-xl font-semibold text-blue-700 mb-2">Classificação Etária</h2>
                    <p class="text-gray-600">Gerenciar Classificação Etária</p>
                </a>

                <a href="form_tipoanuncio.php"
                    class="block p-6 bg-gray-50 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    <h2 class="text-xl font-semibold text-blue-700 mb-2">Tipo de Anúncio</h2>
                    <p class="text-gray-600">Gerenciar Tipos de Anúncio</p>
                </a>

                <a href="form_tipoevento.php"
                    class="block p-6 bg-gray-50 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    <h2 class="text-xl font-semibold text-blue-700 mb-2">Tipo de Evento</h2>
                    <p class="text-gray-600">Gerenciar Tipos de Evento</p>
                </a>

                <a href="form_tipopublico.php"
                    class="block p-6 bg-gray-50 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    <h2 class="text-xl font-semibold text-blue-700 mb-2">Tipo de Público</h2>
                    <p class="text-gray-600">Gerenciar Tipo de Público</p>
                </a>

                <a href="form_tipolocal.php"
                    class="block p-6 bg-gray-50 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    <h2 class="text-xl font-semibold text-blue-700 mb-2">Tipo do Local</h2>
                    <p class="text-gray-600">Gerenciar Tipo do Local</p>
                </a>

                <a href="listar_local.php"
                    class="block p-6 bg-gray-50 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    <h2 class="text-xl font-semibold text-blue-700 mb-2">Locais Cadastrados</h2>
                    <p class="text-gray-600">Listar Locais</p>
                </a>
            </div>
        </div>
    </div>

    <!-- Botão para subir a página (Mobile) -->
    <button id="backToTopBtn"
        class="fixed bottom-6 right-6 sm:hidden bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-full shadow-lg transition"
        aria-label="Voltar ao topo">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        const backToTopBtn = document.getElementById('backToTopBtn');

        backToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Optional: Show button only when scrolled down a bit
        window.addEventListener('scroll', () => {
            if (window.scrollY > 200) {
                backToTopBtn.classList.remove('hidden');
            } else {
                backToTopBtn.classList.add('hidden');
            }
        });

        // Initially hide the button
        backToTopBtn.classList.add('hidden');
    </script>

</body>

</html>