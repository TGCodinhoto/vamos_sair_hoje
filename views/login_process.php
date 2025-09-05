<?php
require_once '../utils/session_manager.php';
require_once '../conexao.php';
require_once '../controllers/usuario_controller.php';
require_once '../utils/logger.php';

SessionManager::iniciarSessao();

try {
    Logger::info("=== Iniciando tentativa de login ===");
    Logger::debug("Email: " . ($_POST['email'] ?? 'não fornecido'));

    // Verifica se os campos foram enviados
    if (!isset($_POST['email']) || !isset($_POST['senha'])) {
        throw new Exception('Por favor, preencha seu email e senha');
    }

    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'];

    if (!$email) {
        throw new Exception('O email fornecido não é válido');
    }

    if (empty($senha)) {
        throw new Exception('Por favor, digite sua senha');
    }

    $pdo = Conexao::getInstance();
    $controller = new UsuarioController($pdo);
    $resultado = $controller->login();

    if (!isset($resultado['sucesso'])) {
        throw new Exception('Resposta inválida do controlador');
    }

    if ($resultado['sucesso']) {
        Logger::info("Login bem-sucedido para o usuário: " . $email);
        Logger::info("Tipo de usuário: " . $resultado['tipo']);
        Logger::info("Sessão após login: " . print_r($_SESSION, true));
        
        // Redireciona com base no tipo de usuário
        if ($resultado['tipo'] == 2) { // Estabelecimento
            Logger::info("Redirecionando para formulários (tipo 2)");
            header('Location: navegacao_forms.php');
        } else if ($resultado['tipo'] == 1) { // Administrador
            Logger::info("Redirecionando para formulários (tipo 1)");
            header('Location: navegacao_forms.php');
        } else { // Usuário comum
            Logger::info("Redirecionando para index (tipo 3)");
            header('Location: ../index.php');
        }
    } else {
        Logger::error("Falha no login: " . ($resultado['mensagem'] ?? 'Motivo desconhecido'));
        $_SESSION['erro'] = $resultado['mensagem'] ?? 'Email ou senha incorretos';
        $_SESSION['email'] = $email; // Preserva o email para conveniência
        header('Location: login.php');
    }
} catch (Exception $e) {
    Logger::error("Erro no login: " . $e->getMessage());
    $_SESSION['erro'] = $e->getMessage();
    $_SESSION['email'] = $email ?? ''; // Preserva o email mesmo em caso de erro
    header('Location: login.php');
}
exit();
?>
