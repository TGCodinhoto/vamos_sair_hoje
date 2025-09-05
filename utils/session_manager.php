<?php
class SessionManager {
    // Tempo de expiração da sessão em segundos (3 horas = 10800 segundos)
    const SESSION_EXPIRY = 10800;

    /**
     * Verifica se a sessão expirou
     * @return bool
     */
    public static function verificarSessaoExpirada() {
        if (!isset($_SESSION['LAST_ACTIVITY'])) {
            return true;
        }

        $tempoInativo = time() - $_SESSION['LAST_ACTIVITY'];
        if ($tempoInativo >= self::SESSION_EXPIRY) {
            // Se expirou, destruir a sessão
            session_unset();
            session_destroy();
            return true;
        }

        // Atualiza o timestamp da última atividade
        $_SESSION['LAST_ACTIVITY'] = time();
        return false;
    }

    /**
     * Inicializa ou renova a sessão
     */
    public static function iniciarSessao() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['LAST_ACTIVITY'] = time();
    }
}
?>
