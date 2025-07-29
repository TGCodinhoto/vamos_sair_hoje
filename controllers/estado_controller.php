<?php
require_once '../conexao.php';
require_once '../models/estado_model.php';

$estadoModel = new EstadoModel($conexao);


if (isset($_GET['delete'])) {
    try {
        $resultado = $estadoModel->excluir($_GET['delete']);
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!empty($_POST['id'])) {
            $resultado = $estadoModel->atualizar($_POST['id'], $_POST['estadonome'], $_POST['estadosigla']);
            if ($resultado) {
                header("Location: ../views/form_estado.php?msg=updated");
            } else {
                header("Location: ../views/form_estado.php?msg=error");
            }
        } else {
            $resultado = $estadoModel->criar($_POST['estadonome'], $_POST['estadosigla']);
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


function buscarEstadoPorId($id) {
    global $estadoModel;
    return $estadoModel->buscarPorId($id);
}

function listarEstados() {
    global $conexao; // Pega a conexão global
    $estadoModel = new EstadoModel($conexao);
    try {
        // Chama o método listar() do seu EstadoModel
        return $estadoModel->listar(); 
    } catch (Exception $e) {
        error_log("Erro no controller ao chamar listarEstados: " . $e->getMessage());
        return []; // Retorna um array vazio em caso de falha
    }
}
?>
