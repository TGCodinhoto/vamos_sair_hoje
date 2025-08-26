<?php
require_once  __DIR__ . '/../conexao.php';
require_once  __DIR__ . '/../models/cidade_model.php';

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

function listarCidades()
{
    global $conexao;
    try {
        $stmt = $conexao->query("
            SELECT 
                c.cidadeid, 
                c.cidadenome, 
                c.estadoid, 
                e.estadonome, 
                e.estadosigla 
            FROM cidade c
            JOIN estado e ON c.estadoid = e.estadoid
            ORDER BY c.cidadenome
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erro ao listar cidades com estados: " . $e->getMessage());
        return [];
    }
}

function buscarCidadePorId($id) {
global $cidadeModel;
return $cidadeModel->buscarPorId($id);
}
