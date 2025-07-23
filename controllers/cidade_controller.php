<?php
require_once '../conexao.php';
require_once '../models/cidade_model.php';

$cidadeModel = new CidadeModel($conexao);

if (isset($_GET['delete'])) {
    try {
        $resultado = $cidadeModel->excluir($_GET['delete']);
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!empty($_POST['id'])) {
            $resultado = $cidadeModel->atualizar($_POST['id'], $_POST['cidadenome'], $_POST['estadoid']);
            if ($resultado) {
                header("Location: ../views/form_cidade.php?msg=updated");
            } else {
                header("Location: ../views/form_cidade.php?msg=error");
            }
        } else {
            $resultado = $cidadeModel->criar($_POST['cidadenome'], $_POST['estadoid']);
            if ($resultado) {
                header("Location: ../views/form_cidade.php?msg=created");
            } else {
                header("Location: ../views/form_cidade.php?msg=error");
            }
        }
    } catch (Exception $e) {
        error_log("Erro ao cadastrar cidade: " . $e->getMessage());
        $erro = "Ocorreu um erro ao processar sua solicitação. Tente novamente mais tarde.";
        header("Location: ../views/form_cidade.php?msg=error&erro=" . urlencode($erro));
    }
    exit;
}

function listarCidades() {
    global $cidadeModel;
    return $cidadeModel->listar();
}

function buscarCidadePorId($id) {
    global $cidadeModel;
    return $cidadeModel->buscarPorId($id);
}
