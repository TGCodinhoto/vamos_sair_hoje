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

        <meta charset="utf-8" />
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <title>Tela de Login</title>
        <link rel="shortcut icon" href="../image/favicon.svg" type="image/x-icon">
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />

        <style>
            body{
                font-family: 'Inter', sans-serif;
            }
        </style>
    </head>

    <body class="h-full flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-xl shadow-lg relative">
            <a href="../index.php" class="absolute left-4 top-4 text-gray-600 hover:text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div class="flex justify-center">
                <a href="../index.php">
                    <img alt="Logo Vamos Sair Hoje" class="h-20 w-20" src="../image/favicon.svg" />
                </a>
            </div>
            <h2 class="mt-6 text-center text-3xl font-semibold text-gray-900">
                Entrar na sua conta
            </h2>

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

            <form class="mt-8 space-y-6" method="POST" action="login_process.php" id="loginForm">
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label class="sr-only" for="email">Endereço de email</label>
                        <input autocomplete="email"
                            class="appearance-none rounded-t-md relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                            id="email"
                            name="email"
                            placeholder="Endereço de email"
                            required=""
                            type="email"
                            value="<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>">
                        <?php unset($_SESSION['email']); ?>
                    </div>
                    <div>
                        <label class="sr-only" for="senha">Senha</label>
                        <input autocomplete="current-password"
                            class="appearance-none rounded-b-md relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                            id="senha"
                            name="senha"
                            placeholder="Senha"
                            required=""
                            type="password">
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            id="remember"
                            name="remember"
                            type="checkbox">
                        <label class="ml-2 block text-sm text-gray-900" for="remember">Lembrar-me</label>
                    </div>
                    <div class="text-sm">
                        <a class="font-medium text-blue-600 hover:text-blue-500" href="recuperar_senha.php">
                            Esqueceu a senha?
                        </a>
                    </div>
                </div>
                <div>
                    <button type="submit"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-semibold rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Entrar
                    </button>
                </div>
                <p class="mt-6 text-center text-sm text-gray-600">
                    Não tem uma conta?
                    <a class="font-medium text-blue-600 hover:text-blue-500" href="cadastro.php">
                        Crie uma agora
                    </a>
                </p>
            </form>
        </div>

        <script>
            function togglePassword() {
                const passwordInput = document.getElementById('senha');
                const toggleButton = document.querySelector('.password-toggle');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    toggleButton.innerHTML = `
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                        <line x1="1" y1="1" x2="23" y2="23"></line>
                    </svg>
                `;
                } else {
                    passwordInput.type = 'password';
                    toggleButton.innerHTML = `
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                `;
                }
            }

            // Adiciona foco automático no campo de email
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('email').focus();
            });
        </script>
    </body>

    </html>