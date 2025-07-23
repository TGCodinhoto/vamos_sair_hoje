<?php

require_once '../conexao.php';
require_once '../models/tipo_evento_model.php';

$tipoEventoModel = new TipoEventoModel($conexao);

if (isset($_GET['delete'])) {
    try {
        $resultado = $tipoEventoModel->excluir(intval($_GET['delete']));
        if ($resultado) {
            header("Location: ../views/form_tipoevento.php?msg=deleted");
        } else {
            header("Location: ../views/form_tipoevento.php?msg=error");
        }
    } catch (Exception $e) {
        error_log("Erro ao excluir tipo de evento: " . $e->getMessage());
        $erro = "Ocorreu um erro ao processar sua solicitação. Tente novamente mais tarde.";
        header("Location: ../views/form_tipoevento.php?msg=error&erro=" . urlencode($erro));
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!empty($_POST['id'])) {
            $resultado = $tipoEventoModel->atualizar(intval($_POST['id']), $_POST['tipoeventonome']);
            if ($resultado) {
                header("Location: ../views/form_tipoevento.php?msg=updated");
            } else {
                header("Location: ../views/form_tipoevento.php?msg=error");
            }
        } else {
            $nome = $_POST['tipoeventonome'];
            $imagens = isset($_POST['tipoeventoimage']) ? $_POST['tipoeventoimage'] : '';
            $resultado = $tipoEventoModel->criar($nome, $imagens);
            if ($resultado) {
                header("Location: ../views/form_tipoevento.php?msg=created");
            } else {
                header("Location: ../views/form_tipoevento.php?msg=error");
            }
        }
    } catch (Exception $e) {
        error_log("Erro ao cadastrar tipo de evento: " . $e->getMessage());
        $erro = "Ocorreu um erro ao processar sua solicitação. Tente novamente mais tarde.";
        header("Location: ../views/form_tipoevento.php?msg=error&erro=" . urlencode($erro));
    }
    exit;
}

function listarTiposEvento() {
    global $tipoEventoModel;
    return $tipoEventoModel->listar();
}

function buscarTipoEventoPorId($id) {
    global $tipoEventoModel;
    return $tipoEventoModel->buscarPorId($id);
}
