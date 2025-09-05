<?php
session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['erro'] = 'Você precisa fazer login para acessar esta página';
    header('Location: login.php');
    exit();
}

// Resto do código da página
?>
