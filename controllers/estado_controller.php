<?php
require_once __DIR__ . '/../conexao.php';
require_once __DIR__ . '/../models/estado_model.php';
require_once __DIR__ . '/base_controller.php';

class EstadoController extends BaseController {
    private $conexao;
    private $estadoModel;

    public function __construct() {
        $this->conexao = Conexao::getInstance();
        $this->estadoModel = new EstadoModel($this->conexao);
    }

    public function excluir($id) {
        try {
            $resultado = $this->estadoModel->excluir($id);
            if ($resultado) {
                header("Location: ../views/form_estado.php?msg=deleted");
            } else {
                header("Location: ../views/form_estado.php?msg=error");
            }
        } catch (Exception $e) {
            error_log("Erro ao excluir estado: " . $e->getMessage());
            $erro = "Ocorreu um erro ao processar sua solicitação. Tente novamente mais tarde.";
            header("Location: ../views/form_estado.php?msg=error&erro=" . urlencode($erro));
        }
        exit;
    }

    public function processarRequisicao() {
        if (isset($_GET['delete'])) {
            $this->excluir($_GET['delete']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if (!empty($_POST['id'])) {
                    $resultado = $this->estadoModel->atualizar(
                        $_POST['id'],
                        $_POST['estadonome'],
                        $_POST['estadosigla']
                    );
                    if ($resultado) {
                        header("Location: ../views/form_estado.php?msg=updated");
                    } else {
                        header("Location: ../views/form_estado.php?msg=error");
                    }
                } else {
                    $resultado = $this->estadoModel->criar(
                        $_POST['estadonome'],
                        $_POST['estadosigla']
                    );
                    if ($resultado) {
                        header("Location: ../views/form_estado.php?msg=created");
                    } else {
                        header("Location: ../views/form_estado.php?msg=error");
                    }
                }
            } catch (Exception $e) {
                error_log("Erro ao cadastrar estado: " . $e->getMessage());
                $erro = "Ocorreu um erro ao processar sua solicitação. Tente novamente mais tarde.";
                header("Location: ../views/form_estado.php?msg=error&erro=" . urlencode($erro));
            }
            exit;
        }
    }

    public function buscarEstadoPorId($id) {
        return $this->estadoModel->buscarPorId($id);
    }

    public function listarEstados() {
        try {
            return $this->estadoModel->listar();
        } catch (Exception $e) {
            error_log("Erro no controller ao chamar listarEstados: " . $e->getMessage());
            return [];
        }
    }
}

// Instancia o controller e processa a requisição
if (basename($_SERVER['PHP_SELF']) === 'estado_controller.php') {
    $controller = new EstadoController();
    $controller->processarRequisicao();
}
?>
