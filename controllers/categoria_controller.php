<?php
require_once '../conexao.php';
require_once '../models/categoria_model.php';

$conexao = Conexao::getInstance();
$categoriaModel = new CategoriaModel($conexao);

if (isset($_GET['delete'])) {
    try {
        $resultado = $categoriaModel->excluir($_GET['delete']);
        if ($resultado) {
            header("Location: ../views/form_categoria.php?msg=deleted");
        } else {
            header("Location: ../views/form_categoria.php?msg=error");
        }
    } catch (Exception $e) {
        error_log("Erro ao excluir categoria: " . $e->getMessage());
        $erro = "Ocorreu um erro ao processar sua solicitação. Tente novamente mais tarde.";
        header("Location: ../views/form_categoria.php?msg=error&erro=" . urlencode($erro));
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!empty($_POST['id'])) {
            $resultado = $categoriaModel->atualizar($_POST['id'], $_POST['categorianome']);
            if ($resultado) {
                header("Location: ../views/form_categoria.php?msg=updated");
            } else {
                header("Location: ../views/form_categoria.php?msg=error");
            }
        } else {
            $resultado = $categoriaModel->criar($_POST['categorianome']);
            if ($resultado) {
                header("Location: ../views/form_categoria.php?msg=created");
            } else {
                header("Location: ../views/form_categoria.php?msg=error");
            }
        }
    } catch (Exception $e) {
        error_log("Erro ao cadastrar categoria: " . $e->getMessage());
        $erro = "Ocorreu um erro ao processar sua solicitação. Tente novamente mais tarde.";
        header("Location: ../views/form_categoria.php?msg=error&erro=" . urlencode($erro));
    }
    exit;
}

function listarCategorias() {
    global $categoriaModel;
    return $categoriaModel->listar();
}

function buscarCategoriaPorId($id) {
    global $categoriaModel;
    return $categoriaModel->buscarPorId($id);
}
?>
