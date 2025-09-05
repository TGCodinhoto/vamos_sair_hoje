<?php
require_once __DIR__ . '/../utils/session_manager.php';

class BaseController {
    protected function verificarSessao($requireLogin = false) {
        SessionManager::iniciarSessao();
        
        if (!$requireLogin) {
            return true;
        }

        if (SessionManager::verificarSessaoExpirada()) {
            return false;
        }

        if (!isset($_SESSION['userid'])) {
            return false;
        }

        return true;
    }

    protected function getLoginPath() {
        // Se a URL atual contém /views/, então estamos dentro da pasta views
        $currentUrl = $_SERVER['REQUEST_URI'];
        if (strpos($currentUrl, '/views/') !== false) {
            return 'login.php';
        }
        // Caso contrário, estamos na raiz
        return 'views/login.php';
    }
}
?>
