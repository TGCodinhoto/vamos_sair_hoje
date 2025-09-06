<?php
session_start();

// Verifica se o usuário já está autenticado
if (isset($_SESSION['usuario_id'])) {
    header('Location: perfil.php');
    exit();
}

$erro = isset($_SESSION['erro']) ? $_SESSION['erro'] : '';
$mensagem = isset($_SESSION['mensagem']) ? $_SESSION['mensagem'] : '';
unset($_SESSION['erro']);
unset($_SESSION['mensagem']);
?>
<!DOCTYPE html>
<html class="h-full bg-[#1B3B57]" lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha - VS Hoje</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="../image/favicon.svg" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    
    <style>
        body{
            font-family: 'Inter', sans-serif;  
        }
    </style>
</head>
<body class="h-full flex items-center justify-center px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-xl shadow-lg relative">
        <a href="login.php" class="absolute left-4 top-4 text-gray-600 hover:text-gray-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <div class="flex justify-center">
                <a href="../index.php">
                    <img alt="Logo VS Hoje" class="h-20 w-20" src="../image/favicon.svg" />
                </a>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Recuperar Senha
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Digite seu e-mail para recuperar sua senha
            </p>
        </div>

        <?php if ($erro): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <?php echo htmlspecialchars($erro); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($mensagem): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <?php echo htmlspecialchars($mensagem); ?>
            </div>
        <?php endif; ?>

        <form class="mt-8 space-y-6" method="POST" action="recuperar_senha_process.php" id="recuperarForm">
            <div>
                <label class="sr-only" for="email">Endereço de email</label>
                <input autocomplete="email" 
                       class="appearance-none rounded-md relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                       id="email" 
                       name="email" 
                       placeholder="Endereço de email" 
                       required 
                       type="email">
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-semibold rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Enviar Link de Recuperação
                </button>
            </div>

            <p class="mt-6 text-center text-sm text-gray-600">
                Lembrou sua senha?
                <a class="font-medium text-blue-600 hover:text-blue-500" href="login.php">
                    Voltar ao login
                </a>
            </p>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('recuperarForm');
            
            form.addEventListener('submit', function(e) {
                const button = form.querySelector('button[type="submit"]');
                const originalText = button.textContent;
                button.disabled = true;
                button.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Enviando...
                `;
            });

            // Adiciona foco automático no campo de email
            document.getElementById('email').focus();
        });
    </script>
</body>
</html>
