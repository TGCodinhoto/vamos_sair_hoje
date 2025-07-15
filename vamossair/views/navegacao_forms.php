<?php #REMOVER DEPOIS DE TESTAR - TEMP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <title>Navegação - Formulários</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen p-6">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded shadow">
        <h1 class="text-3xl font-bold mb-8 text-center">Gerenciamento de Dados</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="form_tipoanuncio.php" 
               class="block p-6 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 transition">
                <h2 class="text-xl font-semibold text-indigo-800 mb-2">Tipo de Anúncio</h2>
                <p class="text-indigo-600">Gerenciar tipos de anúncio</p>
            </a>

            <a href="form_segmento.php" 
               class="block p-6 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition">
                <h2 class="text-xl font-semibold text-green-800 mb-2">Segmento</h2>
                <p class="text-green-600">Gerenciar segmentos</p>
            </a>

            <a href="form_atrativos.php" 
               class="block p-6 bg-yellow-50 border border-yellow-200 rounded-lg hover:bg-yellow-100 transition">
                <h2 class="text-xl font-semibold text-yellow-800 mb-2">Atrativos</h2>
                <p class="text-yellow-600">Gerenciar atrativos</p>
            </a>

            <a href="form_formatoevento.php" 
               class="block p-6 bg-purple-50 border border-purple-200 rounded-lg hover:bg-purple-100 transition">
                <h2 class="text-xl font-semibold text-purple-800 mb-2">Formato do Evento</h2>
                <p class="text-purple-600">Gerenciar formatos de evento</p>
            </a>

            <a href="form_tipoevento.php" 
               class="block p-6 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition">
                <h2 class="text-xl font-semibold text-blue-800 mb-2">Tipo de Evento</h2>
                <p class="text-blue-600">Gerenciar tipos de evento</p>
            </a>

            <a href="form_categoria.php" 
               class="block p-6 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition">
                <h2 class="text-xl font-semibold text-red-800 mb-2">Categoria</h2>
                <p class="text-red-600">Gerenciar categorias</p>
            </a>
        </div>

        <div class="mt-8 text-center">
            <a href="../index.php" 
               class="inline-block bg-gray-600 text-white px-6 py-2 rounded hover:bg-gray-700 transition">
                Voltar ao Início
            </a>
        </div>
    </div>
</body>

</html> 