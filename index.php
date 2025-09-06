<?php
session_start();
?>
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

    // Buscar tipos de evento válidos para a cidade selecionada
    require_once 'models/tipo_evento_model.php';
    $conexao = Conexao::getInstance();
    $tipoEventoModel = new TipoEventoModel($conexao);

    if ($cidade) {
        // Buscar tipos de evento que existem para eventos nessa cidade
        $stmt = $conexao->prepare('
            SELECT DISTINCT te.tipoeventoid, te.tipoeventonome, te.tipoeventoimage
            FROM tipoevento te
            JOIN evento e ON te.tipoeventoid = e.tipoeventoid
            JOIN atracao a ON e.publicacaoid = a.publicacaoid
            JOIN endereco en ON a.enderecoid = en.enderecoid
            WHERE en.cidadeid = :cidade
        ');
        $stmt->bindValue(':cidade', $cidade);
        $stmt->execute();
        $tipoEventoArray = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Se não houver cidade, mostra todos os tipos de evento
        $tipoEventoArray = $tipoEventoModel->listar();
    }

    // Só filtra se o tipo_evento for um dos exibidos nos ícones (válidos para a cidade)
    $tipoEventoIdsValidos = array_map('strval', array_column($tipoEventoArray, 'tipoeventoid'));
    $tipoEventoStr = strval($tipoEvento);
    if ($tipoEventoStr !== '' && !in_array($tipoEventoStr, $tipoEventoIdsValidos)) {
        // Tipo de evento não existe para a cidade, retorna vazio
        $eventos = [];
    }

    // Cria uma instância do EventoController
    $eventoController = new EventoController();

        // Busca eventos conforme filtros
        if ($tipoEvento !== null && $tipoEvento !== '' && !in_array($tipoEvento, $tipoEventoIdsValidos)) {
            // Tipo de evento não existe para a cidade, retorna vazio
            $eventos = [];
        } elseif ($cidade || $dataInicial || $dataFinal || $tipoEvento) {
            $eventos = $eventoController->buscarEventosFiltrados($cidade, $dataInicial, $dataFinal, $tipoEvento);
        } else {
            $eventos = $eventoController->listarEventosCompletos();
        }

    // Passa os tipos de evento válidos para o header
    $tipoEvento = $tipoEventoArray;
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