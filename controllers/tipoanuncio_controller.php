<?php

require_once '../conexao.php';
require_once '../models/tipo_anuncio_model.php';

$conexao = Conexao::getInstance();
$tipoAnuncioModel = new TipoAnuncioModel($conexao);

if (isset($_GET['delete'])) {
    try {
        $resultado = $tipoAnuncioModel->excluir(intval($_GET['delete']));
        if ($resultado) {
            header("Location: ../views/form_tipoanuncio.php?msg=deleted");
        } else {
            header("Location: ../views/form_tipoanuncio.php?msg=error");
        }
    } catch (Exception $e) {
        $erro = "Erro ao excluir: " . $e->getMessage();
        header("Location: ../views/form_tipoanuncio.php?msg=error&erro=" . urlencode($erro));
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!empty($_POST['id'])) {
            $resultado = $tipoAnuncioModel->atualizar(intval($_POST['id']), $_POST['tipoanuncionome']);
            if ($resultado) {
                header("Location: ../views/form_tipoanuncio.php?msg=updated");
            } else {
                header("Location: ../views/form_tipoanuncio.php?msg=error");
            }
        } else {
            $resultado = $tipoAnuncioModel->criar($_POST['tipoanuncionome']);
            if ($resultado) {
                header("Location: ../views/form_tipoanuncio.php?msg=created");
            } else {
                header("Location: ../views/form_tipoanuncio.php?msg=error");
            }
        }
    } catch (Exception $e) {
        error_log("Erro ao cadastrar tipo de anúncio: " . $e->getMessage());
        $erro = "Ocorreu um erro ao processar sua solicitação. Tente novamente mais tarde.";
        header("Location: ../views/form_tipoanuncio.php?msg=error&erro=" . urlencode($erro));
    }
    exit;
}

function listarTiposAnuncio() {
    global $tipoAnuncioModel;
    return $tipoAnuncioModel->listar();
}

function buscarTipoAnuncioPorId($id) {
    global $tipoAnuncioModel;
    return $tipoAnuncioModel->buscarPorId($id);
}
?> 