<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Vamos Sair Hoje!</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <!-- Header! -->
    <?php require_once 'ui/header.php' ?>

    <!-- Main -->
    <main class="max-w-6xl mx-auto px-5">
        
        <!-- Carrossel de Propaganda -->
        <?php require_once 'ui/carrosselPropaganda.php' ?>

        <!-- Cards de Eventos -->
        <?php require_once 'ui/cardsEventos.php' ?>
    
    </main>

    <!-- Footer -->
    <?php require_once 'ui/footer.php' ?>

    <script src="index.js"></script>
</body>

</html>