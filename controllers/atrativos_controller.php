<?php

require_once '../conexao.php';
require_once '../models/atrativos_model.php';

$atrativosModel = new AtrativosModel($conexao);

if (isset($_GET['delete'])) {
    try {
        $resultado = $atrativosModel->excluir(intval($_GET['delete']));
        if ($resultado) {
            header("Location: ../views/form_atrativos.php?msg=deleted");
        } else {
            header("Location: ../views/form_atrativos.php?msg=error");
        }
    } catch (Exception $e) {
        error_log("Erro ao excluir atrativo: " . $e->getMessage());
        $erro = "Ocorreu um erro ao processar sua solicitação. Tente novamente mais tarde.";
        header("Location: ../views/form_atrativos.php?msg=error&erro=" . urlencode($erro));
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!empty($_POST['id'])) {
            $resultado = $atrativosModel->atualizar(intval($_POST['id']), $_POST['atrativosnome']);
            if ($resultado) {
                header("Location: ../views/form_atrativos.php?msg=updated");
            } else {
                header("Location: ../views/form_atrativos.php?msg=error");
            }
        } else {
            $resultado = $atrativosModel->criar($_POST['atrativosnome']);
            if ($resultado) {
                header("Location: ../views/form_atrativos.php?msg=created");
            } else {
                header("Location: ../views/form_atrativos.php?msg=error");
            }
        }
    } catch (Exception $e) {
        error_log("Erro ao cadastrar atrativo: " . $e->getMessage());
        $erro = "Ocorreu um erro ao processar sua solicitação. Tente novamente mais tarde.";
        header("Location: ../views/form_atrativos.php?msg=error&erro=" . urlencode($erro));
    }
    exit;
}

function listarAtrativos() {
    global $atrativosModel;
    return $atrativosModel->listar();
}

function buscarAtrativoPorId($id) {
    global $atrativosModel;
    return $atrativosModel->buscarPorId($id);
}
?> 