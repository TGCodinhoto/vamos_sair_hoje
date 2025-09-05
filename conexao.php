<?php
class Conexao {
    private static $instance = null;
    private static $servername = "br58.hostgator.com.br";
    private static $username = "gilma841_root";
    private static $password = "root@2025";
    private static $dbname = "gilma841_evento";

    private function __construct() {} // Impede instanciação direta

    public static function getInstance() {
        if (self::$instance === null) {
            try {
                $options = [
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ];
                
                self::$instance = new PDO(
                    "mysql:host=".self::$servername.";dbname=".self::$dbname.";charset=utf8mb4",
                    self::$username,
                    self::$password,
                    $options
                );
            } catch (PDOException $e) {
                error_log("Erro na conexão com o banco de dados: " . $e->getMessage());
                throw new Exception("Erro ao conectar ao banco de dados. Por favor, tente novamente mais tarde.");
            }
        }
        return self::$instance;
    }
}
?>
