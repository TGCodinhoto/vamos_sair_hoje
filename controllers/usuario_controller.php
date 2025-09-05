<?php
require_once __DIR__ . '/../models/usuario_model.php';

class UsuarioController {
    private $usuarioModel;

    public function __construct($conn) {
        $this->usuarioModel = new UsuarioModel($conn);
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = trim($_POST['nome'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $senha = $_POST['senha'] ?? '';
            $cnpj = trim($_POST['cnpj'] ?? null);
            $celular = $_POST['celular'] ?? null;
            
            // Define o tipo com base no CNPJ
            $tipo = !empty($cnpj) ? 2 : 3; // 2 para CNPJ, 3 para usuário comum
            
            if (empty($nome) || empty($email) || empty($senha)) {
                return ['sucesso' => false, 'mensagem' => 'Todos os campos são obrigatórios'];
            }
            
            // Verificar se email já existe
            if ($this->usuarioModel->buscarPorEmail($email)) {
                return ['sucesso' => false, 'mensagem' => 'Email já cadastrado'];
            }
            
            // Verificar CNPJ se fornecido
            if ($cnpj && $this->usuarioModel->buscarPorCNPJ($cnpj)) {
                return ['sucesso' => false, 'mensagem' => 'CNPJ já cadastrado'];
            }
            
            if ($this->usuarioModel->criarUsuario($nome, $email, $senha, $cnpj, $tipo, $celular)) {
                return ['sucesso' => true, 'mensagem' => 'Usuário cadastrado com sucesso'];
            }
            
            return ['sucesso' => false, 'mensagem' => 'Erro ao cadastrar usuário'];
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['sucesso' => false, 'mensagem' => 'Método não permitido'];
        }

        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $senha = $_POST['senha'] ?? '';
        
        if (!$email || empty($senha)) {
            return ['sucesso' => false, 'mensagem' => 'Por favor, preencha seu email e senha'];
        }
        
        try {
            $usuario = $this->usuarioModel->verificarLogin($email, $senha);
            
            if ($usuario) {
                error_log('=== DEFININDO VARIÁVEIS DE SESSÃO ===');
                error_log('Dados do usuário: ' . print_r($usuario, true));
                
                // Forçar início da sessão se não estiver ativa
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                
                $_SESSION['userid'] = $usuario['userid'];
                $_SESSION['usertipo'] = intval($usuario['usertipo']); // Convertendo para inteiro
                $_SESSION['usernome'] = $usuario['usernome'];
                $_SESSION['useremail'] = $usuario['useremail'];
                
                error_log('Sessão após definição: ' . print_r($_SESSION, true));
                error_log('Status da sessão: ' . session_status());
                error_log('Session ID: ' . session_id());
                
                return ['sucesso' => true, 'tipo' => intval($usuario['usertipo'])];
            }
            
            return ['sucesso' => false, 'mensagem' => 'Email ou senha incorretos. Por favor, tente novamente.'];
        } catch (Exception $e) {
            error_log("Erro no login: " . $e->getMessage());
            return ['sucesso' => false, 'mensagem' => 'Ocorreu um erro ao tentar fazer login. Por favor, tente novamente.'];
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: /index.php');
        exit();
    }

    public function verificarPermissao($tipo_requerido) {
        session_start();
        if (!isset($_SESSION['userid'])) {
            return false;
        }
        
        // Admin (tipo 1) tem acesso a tudo
        if ($_SESSION['usertipo'] === 1) {
            return true;
        }
        
        // Verifica se o usuário tem o tipo requerido
        return $_SESSION['usertipo'] === $tipo_requerido;
    }
}
?>
