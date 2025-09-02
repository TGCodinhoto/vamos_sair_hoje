<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">

<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Título Site -->
    <title>Vamos Sair Hoje!</title>

    <link rel="shortcut icon" href="image/favicon.svg" type="image/x-icon">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

    <!-- Font MontSerrat -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet" />
    
    <!-- CSS -->
    <link rel="stylesheet" href="index.css">
</head>

<body class="bg-white bg-cover bg-center bg-no-repeat">
    
    <?php
    require_once 'conexao.php';
    require_once 'controllers/evento_controller.php';

    // Captura dos filtros
    $cidade = $_GET['cidade'] ?? null;
    $dataInicial = $_GET['data_inicial'] ?? null;
    $dataFinal = $_GET['data_final'] ?? null;
    $tipoEvento = $_GET['tipo_evento'] ?? null;

    // Busca eventos conforme filtros
    if ($cidade || $dataInicial || $dataFinal || $tipoEvento) {
        $eventos = buscarEventosFiltrados($conexao, $cidade, $dataInicial, $dataFinal, $tipoEvento);
    } else {
        $eventos = listarEventosCompletos();
    }
    ?>

    <!-- Header! -->
    <?php require_once 'ui/header.php' ?>

    <!-- Main -->
    <main class="max-w-6xl mx-auto px-5">

        <!-- Carrossel de Propaganda -->
        

        <!-- Cards de Eventos -->
        <?php require_once 'ui/cardsEventos.php' ?>

    </main>

    <!-- Footer -->
    <?php require_once 'ui/footer.php' ?>

</body>

</html>