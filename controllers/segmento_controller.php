<?php
require_once '../conexao.php';
require_once '../models/segmento_model.php';

$segmentoModel = new SegmentoModel($conexao);

if (isset($_GET['delete'])) {
    try {
        $resultado = $segmentoModel->excluir(intval($_GET['delete']));
        if ($resultado) {
            header("Location: ../views/form_segmento.php?msg=deleted");
        } else {
            header("Location: ../views/form_segmento.php?msg=error");
        }
    } catch (Exception $e) {
        error_log("Erro ao excluir segmento: " . $e->getMessage());
        $erro = "Ocorreu um erro ao processar sua solicitação. Tente novamente mais tarde.";
        header("Location: ../views/form_segmento.php?msg=error&erro=" . urlencode($erro));
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!empty($_POST['id'])) {
            $resultado = $segmentoModel->atualizar(intval($_POST['id']), $_POST['segmentonome']);
            if ($resultado) {
                header("Location: ../views/form_segmento.php?msg=updated");
            } else {
                header("Location: ../views/form_segmento.php?msg=error");
            }
        } else {
            $resultado = $segmentoModel->criar($_POST['segmentonome']);
            if ($resultado) {
                header("Location: ../views/form_segmento.php?msg=created");
            } else {
                header("Location: ../views/form_segmento.php?msg=error");
            }
        }
    } catch (Exception $e) {
        error_log("Erro ao cadastrar segmento: " . $e->getMessage());
        $erro = "Ocorreu um erro ao processar sua solicitação. Tente novamente mais tarde.";
        header("Location: ../views/form_segmento.php?msg=error&erro=" . urlencode($erro));
    }
    exit;
}

function listarSegmentos() {
    global $segmentoModel;
    return $segmentoModel->listar();
}

function buscarSegmentoPorId($id) {
    global $segmentoModel;
    return $segmentoModel->buscarPorId($id);
}
?> 