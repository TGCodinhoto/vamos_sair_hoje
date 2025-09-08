<?php

session_start();
require_once '../conexao.php';
require_once '../controllers/usuario_controller.php';
require_once '../utils/logger.php';
require_once '../config_env.php';

try {
    // Log dos dados recebidos
    Logger::info("=== Iniciando Novo Cadastro ===");
    Logger::debug($_POST);

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
    
    $pdo = Conexao::getInstance();
    if (!$pdo) {
        Logger::error("Falha ao conectar ao banco de dados");
        throw new Exception("Erro ao conectar ao banco de dados");
    }
    Logger::info("Conexão com o banco estabelecida");
    
    $controller = new UsuarioController($pdo);
    $resultado = $controller->registrar();
    
    // Log do resultado
    Logger::debug($resultado);

    if ($resultado['sucesso']) {
        $_SESSION['mensagem'] = "Usuário cadastrado com sucesso!";
        header('Location: login.php');
        exit();
    } else {
        // Log the failure reason
        Logger::error("Falha no cadastro: " . $resultado['mensagem']);
        $_SESSION['erro'] = $resultado['mensagem'];
        // Preserve form data
        $_SESSION['form_data'] = [
            'nome' => $_POST['nome'],
            'email' => $_POST['email'],
            'celular' => $_POST['celular'],
            'cnpj' => $_POST['cnpj']
        ];
        header('Location: cadastro.php');
        exit();
    }

} catch (Exception $e) {
    Logger::error("Erro durante o cadastro: " . $e->getMessage());
    $_SESSION['erro'] = $e->getMessage();
    // Preserve form data
    $_SESSION['form_data'] = [
        'nome' => $_POST['nome'],
        'email' => $_POST['email'],
        'celular' => $_POST['celular'],
        'cnpj' => $_POST['cnpj']
    ];
    header('Location: cadastro.php');
    exit();
}
?>
