<?php

session_start();
require_once '../conexao.php';
require_once '../controllers/usuario_controller.php';
require_once '../utils/logger.php';

try {
    // Log dos dados recebidos
    Logger::info("=== Iniciando Novo Cadastro ===");
    Logger::debug($_POST);
    
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
