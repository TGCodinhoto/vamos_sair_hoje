<?php
session_start();

// Verifica se o usuário já está autenticado
if (isset($_SESSION['usuario_id'])) {
    header('Location: perfil.php');
    exit();
}

$erro = isset($_SESSION['erro']) ? $_SESSION['erro'] : '';
$formData = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];
unset($_SESSION['erro']);
unset($_SESSION['form_data']);
?>
<!DOCTYPE html>
<html class="h-full bg-[#1B3B57]" lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - VS Hoje</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="../image/favicon.svg" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
    
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
                Criar Conta
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Preencha seus dados para começar
            </p>
        </div>

        <form class="mt-8 space-y-6" method="POST" action="../views/cadastro_process.php" id="registroForm">
            <?php if ($erro): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <?php echo htmlspecialchars($erro); ?>
                </div>
            <?php endif; ?>

            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label class="sr-only" for="nome">Nome completo</label>
                    <input class="appearance-none rounded-t-md relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                           id="nome" 
                           name="nome" 
                           placeholder="Nome completo" 
                           required 
                           type="text"
                           value="<?php echo htmlspecialchars($formData['nome'] ?? ''); ?>">
                </div>

                <div>
                    <label class="sr-only" for="email">Endereço de email</label>
                    <input class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                           id="email" 
                           name="email" 
                           placeholder="Endereço de email" 
                           required 
                           type="email"
                           value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>">
                </div>

                <div class="relative">
                    <label class="sr-only" for="senha">Senha</label>
                    <input class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                           id="senha" 
                           name="senha" 
                           placeholder="Senha" 
                           required 
                           type="password">
                    <button type="button" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-500"
                            onclick="togglePassword('senha', this)">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>

                <div class="relative">
                    <label class="sr-only" for="confirma_senha">Confirmar senha</label>
                    <input class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                           id="confirma_senha" 
                           name="confirma_senha" 
                           placeholder="Confirmar senha" 
                           required 
                           type="password">
                    <button type="button" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-500"
                            onclick="togglePassword('confirma_senha', this)">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>

                <div>
                    <label class="sr-only" for="cnpj">CNPJ (opcional)</label>
                    <input class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                           id="cnpj" 
                           name="cnpj" 
                           placeholder="CNPJ (opcional)"
                           type="text"
                           value="<?php echo htmlspecialchars($formData['cnpj'] ?? ''); ?>">
                </div>

                <div>
                    <label class="sr-only" for="celular">Celular</label>
                    <input class="appearance-none rounded-b-md relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                           id="celular" 
                           name="celular" 
                           placeholder="Celular com DDD"
                           type="tel"
                           value="<?php echo htmlspecialchars($formData['celular'] ?? ''); ?>">
                </div>
            </div>

            <div id="senha-error" class="text-sm text-red-600 mt-1"></div>
            
            <div class="flex justify-center my-4">
                <div class="h-captcha" data-sitekey="<?php echo getenv('HCAPTCHA_SITEKEY'); ?>"></div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-semibold rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Criar Conta
                </button>
            </div>

            <p class="mt-6 text-center text-sm text-gray-600">
                Já tem uma conta?
                <a class="font-medium text-blue-600 hover:text-blue-500" href="login.php">
                    Fazer login
                </a>
            </p>
        </form>
    </div>

    <script>
        function togglePassword(inputId, button) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = button.querySelector('svg');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                    <line x1="1" y1="1" x2="23" y2="23"></line>
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                `;
            }
        }

        function validatePasswords() {
            const senha = document.getElementById('senha').value;
            const confirmaSenha = document.getElementById('confirma_senha').value;
            const errorElement = document.getElementById('senha-error');
            
            if (confirmaSenha === '') {
                errorElement.textContent = '';
                return false;
            }
            
            if (senha !== confirmaSenha) {
                errorElement.textContent = 'As senhas não correspondem';
                return false;
            }
            
            errorElement.textContent = '';
            return true;
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Máscara para CNPJ
            document.getElementById('cnpj').addEventListener('input', function(e) {
                let x = e.target.value.replace(/\D/g, '').match(/(\d{0,2})(\d{0,3})(\d{0,3})(\d{0,4})(\d{0,2})/);
                e.target.value = !x[2] ? x[1] : x[1] + '.' + x[2] + (x[3] ? '.' + x[3] : '') + (x[4] ? '/' + x[4] : '') + (x[5] ? '-' + x[5] : '');
            });

            // Máscara para Celular
            document.getElementById('celular').addEventListener('input', function(e) {
                let x = e.target.value.replace(/\D/g, '').match(/(\d{0,2})(\d{0,5})(\d{0,4})/);
                e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
            });

            // Validação de senhas
            document.getElementById('senha').addEventListener('input', validatePasswords);
            document.getElementById('confirma_senha').addEventListener('input', validatePasswords);

            // Validação do formulário
            document.getElementById('registroForm').addEventListener('submit', function(e) {
                if (!validatePasswords()) {
                    e.preventDefault();
                    document.getElementById('confirma_senha').focus();
                    return;
                }

                const button = this.querySelector('button[type="submit"]');
                const originalText = button.textContent;
                button.disabled = true;
                button.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Criando conta...
                `;
            });

            // Foco inicial no campo de nome
            document.getElementById('nome').focus();
        });
    </script>
</body>
</html>
