<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Vamos Sair Hoje!</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <!-- <link rel="stylesheet" href="index.css"> -->
</head>

<body>
    <!-- Header -->
    <?php require_once 'header.php' ?>

    <!-- Main -->
    <main class="max-w-7xl mx-auto px-4">

        <!-- Carrossel Principal -->
        <?php require_once 'CarrosselMain.php' ?>

        <!-- Cards -->
        <?php require_once 'cards.php' ?>

    </main>

    <!-- Footer -->
    <?php require_once 'footer.php' ?>

    <script src="js/index.js"></script>
</body>

</html>