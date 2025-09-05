<?php
require_once '../conexao.php';
require_once '../models/classificacao_etaria_model.php';

$conexao = Conexao::getInstance();
$classificacaoEtariaModel = new ClassificacaoEtariaModel($conexao);

if (isset($_GET['delete'])) {
    try {
        $resultado = $classificacaoEtariaModel->excluir($_GET['delete']);
        if ($resultado) {
            header("Location: ../views/form_classificacao_etaria.php?msg=deleted");
        } else {
            header("Location: ../views/form_classificacao_etaria.php?msg=error");
        }
    } catch (Exception $e) {
        error_log("Erro ao excluir classificação etária: " . $e->getMessage());
        $erro = "Ocorreu um erro ao processar sua solicitação. Tente novamente mais tarde.";
        header("Location: ../views/form_classificacao_etaria.php?msg=error&erro=" . urlencode($erro));
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!empty($_POST['id'])) {
            $resultado = $classificacaoEtariaModel->atualizar($_POST['id'], $_POST['classificacaonome']);
            if ($resultado) {
                $mensagem = "Classificação Etária atualizada com sucesso!";
                header("Location: form_classificacao_etaria.php?mensagem=" . urlencode($mensagem));
            } else {
                header("Location: form_classificacao_etaria.php?msg=error");
            }
        } else {
            $resultado = $classificacaoEtariaModel->criar($_POST['classificacaonome']);
            if ($resultado) {
                $mensagem = "Classificação Etária cadastrada com sucesso!";
                header("Location: form_classificacao_etaria.php?mensagem=" . urlencode($mensagem));
            } else {
                header("Location: form_classificacao_etaria.php?msg=error");
            }
        }
    } catch (Exception $e) {
        error_log("Erro ao cadastrar classificação etária: " . $e->getMessage());
        $erro = "Ocorreu um erro ao processar sua solicitação. Tente novamente mais tarde.";
        header("Location: form_classificacao_etaria.php?msg=error&erro=" . urlencode($erro));
    }
    exit;
}

function listarClassificacaoEtaria() {
    global $classificacaoEtariaModel;
    return $classificacaoEtariaModel->listar();
}

function buscarClassificacaoEtariaPorId($id) {
    global $classificacaoEtariaModel;
    return $classificacaoEtariaModel->buscarPorId($id);
}

?>
