<?php
require_once __DIR__ . '/../middleware/apply_middleware.php';

// Aplica o middleware de guest (usuário não autenticado)
applyMiddleware(['guestMiddleware']);

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
        :root {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --secondary-color: #64748b;
            --text-color: #1e293b;
            --light-text: #94a3b8;
            --background: #f8fafc;
            --white: #ffffff;
            --border-color: #e2e8f0;
            --shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%, #dee2e6 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: var(--white);
            border-radius: 20px;
            box-shadow: var(--shadow);
            width: 100%;
            max-width: 440px;
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
        }

        .login-header {
            background: var(--primary-color);
            color: var(--white);
            padding: 40px 30px;
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .login-header h1 {
            font-size: 2.2rem;
            font-weight: 600;
            margin: 0;
        }

        .login-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            text-align: center;
        }

        .login-form {
            padding: 40px 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-color);
            font-size: 0.95rem;
        }

        .input-group {
            position: relative;
        }

        .input-group input {
            width: 100%;
            padding: 15px 15px 15px 48px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 1rem;
            transition: var(--transition);
            outline: none;
        }

        .input-group input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--secondary-color);
            width: 20px;
            height: 20px;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            text-decoration: none;
            transition: var(--transition);
        }

        .back-button svg {
            width: 28px;
            height: 28px;
            opacity: 0.9;
        }

        .back-button:hover {
            transform: translateX(-3px);
        }

        .back-button:hover svg {
            opacity: 1;
        }

        .back-button:active {
            transform: translateX(0);
        }

        .login-btn {
            width: 100%;
            padding: 16px;
            background: var(--primary-color);
            color: var(--white);
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .login-btn:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .signup-link {
            text-align: center;
            color: var(--secondary-color);
            font-size: 0.95rem;
            margin-top: 30px;
        }

        .signup-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        .success-message {
            color: #059669;
            background-color: #d1fae5;
            border: 1px solid #34d399;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }

        .error-message {
            color: #dc2626;
            background-color: #fee2e2;
            border: 1px solid #ef4444;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 480px) {
            .login-container {
                margin: 10px;
            }
            
            .login-header {
                padding: 30px 20px;
            }
            
            .login-form {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body class="h-full flex items-center justify-center px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-xl shadow-lg">
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
