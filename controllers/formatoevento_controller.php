<?php
require_once '../conexao.php';
require_once '../models/formato_evento_model.php';

$conexao = Conexao::getInstance();
$formatoEventoModel = new FormatoEventoModel($conexao);

if (isset($_GET['delete'])) {
    try {
        $resultado = $formatoEventoModel->excluir(intval($_GET['delete']));
        if ($resultado) {
            header("Location: ../views/form_formatoevento.php?msg=deleted");
        } else {
            header("Location: ../views/form_formatoevento.php?msg=error");
        }
    } catch (Exception $e) {
        error_log("Erro ao excluir formato de evento: " . $e->getMessage());
        $erro = "Ocorreu um erro ao processar sua solicitação. Tente novamente mais tarde.";
        header("Location: ../views/form_formatoevento.php?msg=error&erro=" . urlencode($erro));
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!empty($_POST['id'])) {
            $resultado = $formatoEventoModel->atualizar(intval($_POST['id']), $_POST['formatonome'], $_POST['formatodescricao']);
            if ($resultado) {
                header("Location: ../views/form_formatoevento.php?msg=updated");
            } else {
                header("Location: ../views/form_formatoevento.php?msg=error");
            }
        } else {
            $resultado = $formatoEventoModel->criar($_POST['formatonome'], $_POST['formatodescricao']);
            if ($resultado) {
                header("Location: ../views/form_formatoevento.php?msg=created");
            } else {
                header("Location: ../views/form_formatoevento.php?msg=error");
            }
        }
    } catch (Exception $e) {
        error_log("Erro ao cadastrar formato de evento: " . $e->getMessage());
        $erro = "Ocorreu um erro ao processar sua solicitação. Tente novamente mais tarde.";
        header("Location: ../views/form_formatoevento.php?msg=error&erro=" . urlencode($erro));
    }
    exit;
}

function listarFormatosEvento() {
    global $formatoEventoModel;
    return $formatoEventoModel->listar();
}

function buscarFormatoEventoPorId($id) {
    global $formatoEventoModel;
    return $formatoEventoModel->buscarPorId($id);
} 