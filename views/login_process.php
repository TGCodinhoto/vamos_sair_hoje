<?php
require_once '../utils/session_manager.php';
require_once '../conexao.php';
require_once '../controllers/usuario_controller.php';
require_once '../config_env.php';

SessionManager::iniciarSessao();

try {

    // Verifica se os campos foram enviados
    if (!isset($_POST['email']) || !isset($_POST['senha'])) {
        throw new Exception('Por favor, preencha seu email e senha');
    }

    // Verifica se o captcha foi preenchido
    if (!isset($_POST['h-captcha-response'])) {
        throw new Exception('Por favor, complete o captcha');
    }

    // Verifica o captcha com o hCaptcha
    $secret = getenv('HCAPTCHA_SECRET');
    if (!$secret) {
        throw new Exception('Chave secreta do hCaptcha não configurada');
    }
    $verify = curl_init();
    curl_setopt($verify, CURLOPT_URL, "https://api.hcaptcha.com/siteverify");
    curl_setopt($verify, CURLOPT_POST, true);
    curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query([
        'secret' => $secret,
        'response' => $_POST['h-captcha-response']
    ]));
    curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
    $response = json_decode(curl_exec($verify));
    curl_close($verify);

    if (!$response->success) {
        throw new Exception('Verificação do captcha falhou. Por favor, tente novamente.');
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
        
        // Redireciona com base no tipo de usuário
        if ($resultado['tipo'] == 2) { // Estabelecimento
            header('Location: navegacao_forms.php');
        } else if ($resultado['tipo'] == 1) { // Administrador
            header('Location: navegacao_forms.php');
        } else { // Usuário comum
            header('Location: ../index.php');
        }
    } else {
        $_SESSION['erro'] = $resultado['mensagem'] ?? 'Email ou senha incorretos';
        $_SESSION['email'] = $email; // Preserva o email para conveniência
        header('Location: login.php');
    }
} catch (Exception $e) {
    $_SESSION['erro'] = $e->getMessage();
    $_SESSION['email'] = $email ?? ''; // Preserva o email mesmo em caso de erro
    header('Location: login.php');
}
exit();
?>
