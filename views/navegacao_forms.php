<?php #REMOVER DEPOIS DE TESTAR - TEMP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Título Site -->
    <title>Navegação - Formulários</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font MontSerrat -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet" />

</head>

<body class="bg-gray-100 min-h-screen px-2 sm:px-6 py-6">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded shadow">
        <a href="../index.php" class="inline-block mb-6 px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition">&larr; Voltar</a>
        <h1 class="text-4xl md:text-5xl font-bold mb-8 text-center text-blue-600">
            Gerenciamento de Dados
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="form_tipoanuncio.php"
                class="block p-6 bg-gray-50 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                <h2 class="text-xl font-semibold text-blue-700 mb-2">Tipo de Anúncio</h2>
                <p class="text-gray-600">Gerenciar tipos de anúncio</p>
            </a>

            <a href="form_segmento.php"
                class="block p-6 bg-gray-50 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                <h2 class="text-xl font-semibold text-blue-700 mb-2">Segmento</h2>
                <p class="text-gray-600">Gerenciar segmentos</p>
            </a>

            <a href="form_atrativos.php"
                class="block p-6 bg-gray-50 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                <h2 class="text-xl font-semibold text-blue-700 mb-2">Atrativos</h2>
                <p class="text-gray-600">Gerenciar atrativos</p>
            </a>

            <a href="form_formatoevento.php"
                class="block p-6 bg-gray-50 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                <h2 class="text-xl font-semibold text-blue-700 mb-2">Formato do Evento</h2>
                <p class="text-gray-600">Gerenciar formatos de evento</p>
            </a>

            <a href="form_tipoevento.php"
                class="block p-6 bg-gray-50 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                <h2 class="text-xl font-semibold text-blue-700 mb-2">Tipo de Evento</h2>
                <p class="text-gray-600">Gerenciar tipos de evento</p>
            </a>

            <a href="form_categoria.php"
                class="block p-6 bg-gray-50 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                <h2 class="text-xl font-semibold text-blue-700 mb-2">Categoria</h2>
                <p class="text-gray-600">Gerenciar categorias</p>
            </a>

            <a href="form_tipopublico.php"
                class="block p-6 bg-gray-50 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                <h2 class="text-xl font-semibold text-blue-700 mb-2">Tipo de Público</h2>
                <p class="text-gray-600">Gerenciar Tipo de Público</p>
            </a>

            <a href="form_classificacao_etaria.php"
                class="block p-6 bg-gray-50 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                <h2 class="text-xl font-semibold text-blue-700 mb-2">Classificação Etária</h2>
                <p class="text-gray-600">Gerenciar Classificação Etária</p>
            </a>

            <a href="form_tipolocal.php"
                class="block p-6 bg-gray-50 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                <h2 class="text-xl font-semibold text-blue-700 mb-2">Tipo do Local </h2>
                <p class="text-gray-600">Gerenciar Tipo do Local</p>
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
        </div>

        <!-- <div class="mt-8 text-center">
            <a href="../index.php"
                class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md px-5 py-2 transition">
                Voltar ao Início
            </a>
        </div> -->
    </div>
</body>

</html>