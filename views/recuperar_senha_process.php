<?php
require_once '../conexao.php';
require_once '../models/usuario_model.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

if (!$email) {
    echo json_encode(['success' => false, 'message' => 'E-mail inválido']);
    exit;
}

try {
    $pdo = Conexao::getInstance();
    $usuario = new Usuario($pdo);
    
    // Verifica se o email existe
    $stmt = $pdo->prepare("SELECT userid, usernome FROM usuario WHERE useremail = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'Não existe uma conta com este e-mail']);
        exit;
    }

    // Gera token único
    $token = bin2hex(random_bytes(32));
    $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Salva o token no banco
    $stmt = $pdo->prepare("INSERT INTO recuperacao_senha (userid, token, expira) VALUES (?, ?, ?)");
    $stmt->execute([$user['userid'], $token, $expira]);

    // Configura o e-mail
    $to = $email;
    $subject = "Recuperação de Senha - Vamos Sair Hoje";
    
    $message = "Olá " . htmlspecialchars($user['usernome']) . ",\n\n";
    $message .= "Você solicitou a recuperação de senha. Clique no link abaixo para criar uma nova senha:\n\n";
    $message .= "http://" . $_SERVER['HTTP_HOST'] . "/views/nova_senha.php?token=" . $token . "\n\n";
    $message .= "Este link expira em 1 hora.\n\n";
    $message .= "Se você não solicitou esta recuperação de senha, ignore este e-mail.\n\n";
    $message .= "Atenciosamente,\nEquipe Vamos Sair Hoje";

    $headers = "From: naoresponder@vamossairhoje.com.br\r\n";
    $headers .= "Reply-To: naoresponder@vamossairhoje.com.br\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    // Envia o e-mail
    if(mail($to, $subject, $message, $headers)) {
        echo json_encode([
            'success' => true, 
            'message' => 'Link de recuperação enviado para seu e-mail. Verifique sua caixa de entrada e spam.'
        ]);
    } else {
        throw new Exception('Erro ao enviar e-mail');
    }

} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'Erro ao processar a solicitação. Por favor, tente novamente mais tarde.'
    ]);
}
