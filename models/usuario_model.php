<?php
class UsuarioModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->verificarEstrutura();
    }

    private function verificarEstrutura() {
        try {
            error_log("=== VERIFICANDO ESTRUTURA DA TABELA ===");
            // Verifica se a tabela existe
            $sql = "SHOW TABLES LIKE 'user'";
            $stmt = $this->conn->query($sql);
            
            if ($stmt->rowCount() == 0) {
                error_log("Tabela 'user' não encontrada. Criando...");
                
                // Cria a tabela com os nomes corretos das colunas
                $sql = "CREATE TABLE IF NOT EXISTS user (
                    userid INT AUTO_INCREMENT PRIMARY KEY,
                    usernome VARCHAR(255) NOT NULL,
                    useremail VARCHAR(255) NOT NULL UNIQUE,
                    userpass CHAR(32) NOT NULL,
                    usercnpj VARCHAR(18) NULL,
                    usertipo INT NOT NULL DEFAULT 3,
                    usercell VARCHAR(20) NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )";
                
                $this->conn->exec($sql);
                error_log("Tabela 'user' criada com sucesso");
            } else {
                // Verificar estrutura atual da tabela
                $describeStmt = $this->conn->query("DESCRIBE user");
                $columns = $describeStmt->fetchAll(PDO::FETCH_ASSOC);
                error_log("Estrutura atual da tabela:");
                foreach ($columns as $column) {
                    error_log(print_r($column, true));
                }
            }
        } catch (PDOException $e) {
            error_log("Erro ao verificar/criar tabela: " . $e->getMessage());
            throw new Exception("Erro ao configurar banco de dados");
        }
    }

    public function criarUsuario($nome, $email, $senha, $cnpj = null, $tipo = 3, $celular = null) {
        try {
            // Validações
            if (empty($nome) || empty($email) || empty($senha)) {
                error_log("Erro: Campos obrigatórios faltando");
                throw new Exception("Todos os campos obrigatórios devem ser preenchidos");
            }

            // Verificar CNPJ duplicado primeiro
            if (!empty($cnpj)) {
                error_log("Verificando CNPJ: " . $cnpj);
                if ($this->buscarPorCNPJ($cnpj)) {
                    error_log("Erro: CNPJ já cadastrado - " . $cnpj);
                    throw new Exception("CNPJ já está cadastrado no sistema");
                }
            }

            // Log dos dados recebidos
            error_log("Tentando criar usuário:");
            error_log("Nome: " . $nome);
            error_log("Email: " . $email);
            error_log("CNPJ: " . ($cnpj ?? 'não informado'));
            error_log("Tipo: " . $tipo);
            error_log("Celular: " . ($celular ?? 'não informado'));
            
            // Gera o hash da senha
            $senha_hash = md5($senha);
            error_log("Hash da senha gerado");
            
            // Prepara a query
            $sql = "INSERT INTO user (usernome, useremail, userpass, usercnpj, usertipo, usercell) 
                   VALUES (:nome, :email, :senha, :cnpj, :tipo, :cell)";
            
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                error_log("Erro ao preparar a query: " . print_r($this->conn->errorInfo(), true));
                throw new Exception("Erro ao preparar a query");
            }
            
            // Executa a query com bind de parâmetros nomeados
            $params = [
                ':nome' => $nome,
                ':email' => $email,
                ':senha' => $senha_hash,
                ':cnpj' => $cnpj,
                ':tipo' => $tipo,
                ':cell' => $celular
            ];
            
            error_log("Executando query com parâmetros: " . print_r($params, true));
            
            $result = $stmt->execute($params);
            
            if (!$result) {
                $erro = $stmt->errorInfo();
                error_log("Erro ao executar a query: " . print_r($erro, true));
                throw new Exception("Erro ao criar usuário: " . $erro[2]);
            }
            
            error_log("Usuário criado com sucesso. ID: " . $this->conn->lastInsertId());
            return true;
        } catch (PDOException $e) {
            error_log("Erro ao criar usuário: " . $e->getMessage());
            throw new Exception("Erro ao criar usuário");
        }
    }

    public function buscarPorEmail($email) {
        try {
            error_log("=== INICIANDO BUSCA POR EMAIL ===");
            error_log("Buscando email: " . $email);
            
            $sql = "SELECT * FROM user WHERE useremail = ?";
            error_log("SQL Query: " . $sql);
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$email]);
            
            // Log detalhado do resultado
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            error_log("Resultado bruto da consulta: " . print_r($result, true));
            
            if ($result) {
                error_log("Usuário encontrado:");
                error_log("ID: " . ($result['userid'] ?? 'não definido'));
                error_log("Nome: " . ($result['usernome'] ?? 'não definido'));
                error_log("Email: " . ($result['useremail'] ?? 'não definido'));
                error_log("Tipo: " . ($result['usertipo'] ?? 'não definido'));
                error_log("CNPJ: " . ($result['usercnpj'] ?? 'não informado'));
            } else {
                error_log("Nenhum usuário encontrado com o email: " . $email);
            }
            
            // Log da query SQL real com valores
            $queryLog = $this->conn->prepare("SELECT CONCAT('Query executada: ', ?) as debug");
            $queryLog->execute([$sql]);
            error_log($queryLog->fetch(PDO::FETCH_COLUMN));
            
            return $result;
        } catch (PDOException $e) {
            error_log("ERRO ao buscar por email: " . $e->getMessage());
            error_log("SQL State: " . $e->errorInfo[0]);
            error_log("Error Code: " . $e->errorInfo[1]);
            error_log("Message: " . $e->errorInfo[2]);
            error_log("Stack trace: " . $e->getTraceAsString());
            throw $e;
        }
    }

    public function buscarPorCNPJ($cnpj) {
        $sql = "SELECT * FROM user WHERE usercnpj = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$cnpj]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function verificarLogin($email, $senha) {
        try {
            error_log("=== INICIANDO VERIFICAÇÃO DE LOGIN ===");
            error_log("Email informado: " . $email);
            
            // Verificar a estrutura da tabela
            error_log("Verificando estrutura da tabela user...");
            $describeTable = $this->conn->query("SHOW COLUMNS FROM user");
            $columns = $describeTable->fetchAll(PDO::FETCH_COLUMN);
            error_log("Colunas encontradas na tabela: " . implode(", ", $columns));
            
            $usuario = $this->buscarPorEmail($email);
            
            if (!$usuario) {
                error_log("ERRO: Usuário não encontrado");
                return false;
            }

            error_log("Usuário encontrado, verificando senha...");
            $senha_hash = md5($senha);
            error_log("Hash da senha fornecida: " . $senha_hash);
            error_log("Hash da senha no banco: " . $usuario['userpass']);
            
            // Log dos dados do usuário encontrado
            error_log("=== DADOS DO USUÁRIO ===");
            foreach ($usuario as $campo => $valor) {
                error_log($campo . ": " . $valor);
            }
            
            // Verificação da senha
            $senha_correta = ($senha_hash === $usuario['userpass']);
            error_log("Senha está " . ($senha_correta ? "correta" : "incorreta"));
            
            if ($senha_correta) {
                error_log("Login bem-sucedido para o usuário: " . $email);
                
                $result = [
                    'userid' => $usuario['userid'],
                    'usertipo' => $usuario['usertipo'],
                    'usernome' => $usuario['usernome'],
                    'useremail' => $usuario['useremail']
                ];
                
                error_log("Retornando dados: " . print_r($result, true));
                return $result;
            }

            error_log("ERRO: Senha incorreta para o usuário " . $email);
            return false;

        } catch (PDOException $e) {
            error_log("=== ERRO NO BANCO DE DADOS ===");
            error_log("Mensagem: " . $e->getMessage());
            error_log("Código: " . $e->getCode());
            error_log("SQL State: " . $e->errorInfo[0]);
            error_log("Stack trace: " . $e->getTraceAsString());
            throw new Exception("Erro ao verificar credenciais no banco de dados");
        } catch (Exception $e) {
            error_log("=== ERRO GERAL ===");
            error_log("Mensagem: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw $e;
        }
    }
}
