<?php
require_once '../conexao.php';
require_once '../models/tipo_publico_model.php';

$conexao = Conexao::getInstance();
$tipoPublicoModel = new TipoPublicoModel($conexao);

if (isset($_GET['delete'])) {
    try {
        $resultado = $tipoPublicoModel->excluir($_GET['delete']);
        if ($resultado) {
            header("Location: ../views/form_tipopublico.php?msg=deleted");
        } else {
            header("Location: ../views/form_tipopublico.php?msg=error");
        }
    } catch (Exception $e) {
        error_log("Erro ao excluir tipo de público: " . $e->getMessage());
        $erro = "Ocorreu um erro ao processar sua solicitação. Tente novamente mais tarde.";
        header("Location: ../views/form_tipopublico.php?msg=error&erro=" . urlencode($erro));
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!empty($_POST['id'])) {
            $resultado = $tipoPublicoModel->atualizar($_POST['id'], $_POST['tipopubliconome']);
            if ($resultado) {
                header("Location: ../views/form_tipopublico.php?msg=updated");
            } else {
                header("Location: ../views/form_tipopublico.php?msg=error");
            }
        } else {
            $resultado = $tipoPublicoModel->criar($_POST['tipopubliconome']);
            if ($resultado) {
                header("Location: ../views/form_tipopublico.php?msg=created");
            } else {
                header("Location: ../views/form_tipopublico.php?msg=error");
            }
        }
    } catch (Exception $e) {
        error_log("Erro ao cadastrar tipo de público: " . $e->getMessage());
        $erro = "Ocorreu um erro ao processar sua solicitação. Tente novamente mais tarde.";
        header("Location: ../views/form_tipopublico.php?msg=error&erro=" . urlencode($erro));
    }
    exit;
}

function listarTipoPublico() {
    global $tipoPublicoModel;
    return $tipoPublicoModel->listar();
}

function buscarTipoPublicoPorId($id) {
    global $tipoPublicoModel;
    return $tipoPublicoModel->buscarPorId($id);
}
?>
