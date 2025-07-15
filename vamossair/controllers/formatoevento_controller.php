<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../conexao.php';

function listarFormatosEvento() {
    global $conexao;
    $stmt = $conexao->query("SELECT * FROM formatoevento ORDER BY formatoeventoid DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function buscarFormatoEventoPorId($id) {
    global $conexao;
    $stmt = $conexao->prepare("SELECT * FROM formatoevento WHERE formatoeventoid = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function atualizarFormatoEvento($id, $nome) {
    global $conexao;
    $stmt = $conexao->prepare("UPDATE formatoevento SET formatoeventonome = :nome WHERE formatoeventoid = :id");
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

function excluirFormatoEvento($id) {
    global $conexao;
    $stmt = $conexao->prepare("DELETE FROM formatoevento WHERE formatoeventoid = :id");
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

if (isset($_GET['delete'])) {
    excluirFormatoEvento(intval($_GET['delete']));
    header("Location: ../views/form_formatoevento.php?msg=deleted");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['id'])) {
        atualizarFormatoEvento(intval($_POST['id']), $_POST['formatoeventonome']);
        header("Location: ../views/form_formatoevento.php?msg=updated");
        exit;
    } else {
        $nome = $_POST['formatoeventonome'];
        
        $stmt = $conexao->prepare("INSERT INTO formatoevento (formatoeventonome) VALUES (:nome)");
        $stmt->bindParam(':nome', $nome);
        $stmt->execute();

        header("Location: ../views/form_formatoevento.php?msg=created");
        exit;
    }
}
?> 