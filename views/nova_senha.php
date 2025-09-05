<?php
require_once '../conexao.php';
require_once '../models/usuario_model.php';

// Verifica se tem token
$token = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING);
if (!$token) {
    header('Location: login.php');
    exit;
}

// Verifica se o token é válido
try {
    $pdo = Conexao::getInstance();
    $stmt = $pdo->prepare("
        SELECT r.usuario_id, u.email 
        FROM recuperacao_senha r 
        JOIN usuarios u ON u.id = r.usuario_id 
        WHERE r.token = ? AND r.usado = 0 AND r.expira > NOW()
    ");
    $stmt->execute([$token]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$resultado) {
        header('Location: login.php?error=token_invalido');
        exit;
    }
} catch (Exception $e) {
    header('Location: login.php?error=erro_sistema');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Senha - Vamos Sair Hoje</title>
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
            text-align: center;
        }

        .login-header h1 {
            font-size: 2.2rem;
            font-weight: 600;
            margin: 0 0 10px 0;
        }

        .login-header p {
            font-size: 1.1rem;
            opacity: 0.9;
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

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--secondary-color);
            cursor: pointer;
            width: 20px;
            height: 20px;
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

        .error-message {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: block;
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
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Nova Senha</h1>
            <p>Digite sua nova senha</p>
        </div>
        
        <form class="login-form" method="POST" action="nova_senha_process.php" id="novaSenhaForm">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            
            <div class="form-group">
                <label for="senha">Nova Senha</label>
                <div class="input-group">
                    <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <input type="password" id="senha" name="senha" placeholder="Digite sua nova senha" required>
                    <button type="button" class="password-toggle" onclick="togglePassword('senha', this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label for="confirma_senha">Confirmar Nova Senha</label>
                <div class="input-group">
                    <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <input type="password" id="confirma_senha" name="confirma_senha" placeholder="Digite a senha novamente" required>
                    <button type="button" class="password-toggle" onclick="togglePassword('confirma_senha', this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
                <small class="error-message" id="senha-error"></small>
            </div>
            
            <button type="submit" class="login-btn">Salvar Nova Senha</button>
        </form>
    </div>

    <script>
        function togglePassword(inputId, button) {
            const passwordInput = document.getElementById(inputId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                button.innerHTML = `
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                        <line x1="1" y1="1" x2="23" y2="23"></line>
                    </svg>
                `;
            } else {
                passwordInput.type = 'password';
                button.innerHTML = `
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
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
            const form = document.getElementById('novaSenhaForm');
            
            // Event listeners para validação de senha
            document.getElementById('senha').addEventListener('input', validatePasswords);
            document.getElementById('confirma_senha').addEventListener('input', validatePasswords);

            // Validação do formulário antes do envio
            form.addEventListener('submit', function(e) {
                if (!validatePasswords()) {
                    e.preventDefault();
                    document.getElementById('confirma_senha').focus();
                }
            });

            // Adiciona foco automático no campo de senha
            document.getElementById('senha').focus();
        });
    </script>
</body>
</html>
