<?php
require_once '../conexao.php';
require_once '../models/usuario_model.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
$senha = filter_input(INPUT_POST, 'senha', FILTER_UNSAFE_RAW);
$confirma_senha = filter_input(INPUT_POST, 'confirma_senha', FILTER_UNSAFE_RAW);

if (!$token || !$senha || !$confirma_senha) {
    header('Location: login.php?error=campos_invalidos');
    exit;
}

if ($senha !== $confirma_senha) {
    header('Location: nova_senha.php?token=' . urlencode($token) . '&error=senhas_diferentes');
    exit;
}

try {
    $pdo = Conexao::getInstance();
    
    // Verifica se o token é válido e não foi usado
    $stmt = $pdo->prepare("
        SELECT r.*, u.id as usuario_id 
        FROM recuperacao_senha r 
        JOIN usuarios u ON u.id = r.usuario_id 
        WHERE r.token = ? AND r.usado = 0 AND r.expira > NOW()
    ");
    $stmt->execute([$token]);
    $recuperacao = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$recuperacao) {
        header('Location: login.php?error=token_invalido');
        exit;
    }

    // Atualiza a senha do usuário
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
    $stmt->execute([$senha_hash, $recuperacao['usuario_id']]);

    // Marca o token como usado
    $stmt = $pdo->prepare("UPDATE recuperacao_senha SET usado = 1 WHERE token = ?");
    $stmt->execute([$token]);

    header('Location: login.php?success=senha_alterada');
    exit;

} catch (Exception $e) {
    error_log($e->getMessage());
    header('Location: login.php?error=erro_sistema');
    exit;
}
