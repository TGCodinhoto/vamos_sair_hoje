<?php
require_once '../conexao.php';
require_once '../models/tipo_local_model.php';

$tipoLocalModel = new TipoLocalModel($conexao);

if (isset($_GET['delete'])) {
    try {
        $resultado = $tipoLocalModel->excluir($_GET['delete']);
        if ($resultado) {
            header("Location: ../views/form_tipolocal.php?msg=deleted");
        } else {
            header("Location: ../views/form_tipolocal.php?msg=error");
        }
    } catch (Exception $e) {
        error_log("Erro ao excluir tipo de local: " . $e->getMessage());
        $erro = "Ocorreu um erro ao processar sua solicitação. Tente novamente mais tarde.";
        header("Location: ../views/form_tipolocal.php?msg=error&erro=" . urlencode($erro));
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!empty($_POST['id'])) {
            $resultado = $tipoLocalModel->atualizar($_POST['id'], $_POST['tipolocalnome']);
            if ($resultado) {
                header("Location: ../views/form_tipolocal.php?msg=updated");
            } else {
                header("Location: ../views/form_tipolocal.php?msg=error");
            }
        } else {
            $resultado = $tipoLocalModel->criar($_POST['tipolocalnome']);
            if ($resultado) {
                header("Location: ../views/form_tipolocal.php?msg=created");
            } else {
                header("Location: ../views/form_tipolocal.php?msg=error");
            }
        }
    } catch (Exception $e) {
        error_log("Erro ao cadastrar tipo de local: " . $e->getMessage());
        $erro = "Ocorreu um erro ao processar sua solicitação. Tente novamente mais tarde.";
        header("Location: ../views/form_tipolocal.php?msg=error&erro=" . urlencode($erro));
    }
    exit;
}

function listarTipoLocal() {
    global $tipoLocalModel;
    return $tipoLocalModel->listar();
}

function buscarTipoLocalPorId($id) {
    global $tipoLocalModel;
    return $tipoLocalModel->buscarPorId($id);
}
?>
