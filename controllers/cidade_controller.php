<?php
require_once 'base_controller.php';
require_once __DIR__ . '/../models/cidade_model.php';

class CidadeController extends BaseController {
    private $cidadeModel;
    private $conexao;

    public function __construct() {
        $this->conexao = Conexao::getInstance();
        $this->cidadeModel = new CidadeModel($this->conexao);
    }

    public function excluir($id) {
        $this->verificarSessao();
        try {
            $resultado = $this->cidadeModel->excluir($id);
            if ($resultado) {
                header("Location: ../views/form_cidade.php?msg=deleted");
            } else {
                header("Location: ../views/form_cidade.php?msg=error");
            }
        } catch (Exception $e) {
            error_log("Erro ao excluir cidade: " . $e->getMessage());
            $erro = "Ocorreu um erro ao processar sua solicitação. Tente novamente mais tarde.";
            header("Location: ../views/form_cidade.php?msg=error&erro=" . urlencode($erro));
        }
        exit;
    }

    public function processar() {
        $this->verificarSessao();
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!empty($_POST['id'])) {
                    $resultado = $this->cidadeModel->atualizar($_POST['id'], $_POST['cidadenome'], $_POST['estadoid']);
                    if ($resultado) {
                        header("Location: ../views/form_cidade.php?msg=updated");
                    } else {
                        header("Location: ../views/form_cidade.php?msg=error");
                    }
                } else {
                    $resultado = $this->cidadeModel->criar($_POST['cidadenome'], $_POST['estadoid']);
                    if ($resultado) {
                        header("Location: ../views/form_cidade.php?msg=created");
                    } else {
                        header("Location: ../views/form_cidade.php?msg=error");
                    }
                }
            }
        } catch (Exception $e) {
            error_log("Erro ao cadastrar cidade: " . $e->getMessage());
            $erro = "Ocorreu um erro ao processar sua solicitação. Tente novamente mais tarde.";
            header("Location: ../views/form_cidade.php?msg=error&erro=" . urlencode($erro));
        }
        exit;
    }

    public function listarCidades() {
        $this->verificarSessao();
        try {
            $stmt = $this->conexao->query("
                SELECT 
                    c.cidadeid, 
                    c.cidadenome, 
                    c.estadoid, 
                    e.estadonome, 
                    e.estadosigla 
                FROM cidade c
                JOIN estado e ON c.estadoid = e.estadoid
                ORDER BY c.cidadenome
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar cidades com estados: " . $e->getMessage());
            return [];
        }
    }

    public function buscarCidadePorId($id) {
        $this->verificarSessao();
        return $this->cidadeModel->buscarPorId($id);
    }
}

// Instanciar o controller e processar a requisição
$controller = new CidadeController();

if (isset($_GET['delete'])) {
    $controller->excluir($_GET['delete']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->processar();
}
