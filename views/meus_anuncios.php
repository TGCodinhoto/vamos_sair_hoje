<?php
session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['erro'] = 'Você precisa fazer login para acessar esta página';
    header('Location: login.php');
    exit();
}

// Verifica se o usuário é anunciante
if (!isset($_SESSION['is_anunciante']) || !$_SESSION['is_anunciante']) {
    $_SESSION['erro'] = 'Acesso restrito a anunciantes';
    header('Location: index.php');
    exit();
}

// Resto do código da página
?>
