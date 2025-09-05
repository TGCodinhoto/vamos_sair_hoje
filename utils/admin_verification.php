<?php
require_once 'session_manager.php';

function isAdmin() {
    SessionManager::iniciarSessao();
    return isset($_SESSION['userid']) && $_SESSION['usertipo'] == 1;
}

function verificarPermissaoAdmin() {
    SessionManager::iniciarSessao();
    
    // Verifica se a sessão expirou
    if (SessionManager::verificarSessaoExpirada()) {
        header("Location: ../views/login.php");
        exit();
    }

    // Verifica se o usuário está logado
    if (!isset($_SESSION['userid'])) {
        header("Location: ../views/login.php");
        exit();
    }

    // Verifica se o usuário é admin (tipo 1)
    if ($_SESSION['usertipo'] != 1) {
        header("Location: ../views/navegacao_forms.php");
        exit();
    }
}
