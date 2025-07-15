<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../conexao.php';

function listarAtrativos() {
    global $conexao;
    $stmt = $conexao->query("SELECT * FROM atrativos ORDER BY atrativosid DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function buscarAtrativoPorId($id) {
    global $conexao;
    $stmt = $conexao->prepare("SELECT * FROM atrativos WHERE atrativosid = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function atualizarAtrativo($id, $nome) {
    global $conexao;
    $stmt = $conexao->prepare("UPDATE atrativos SET atrativosnome = :nome WHERE atrativosid = :id");
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

function excluirAtrativo($id) {
    global $conexao;
    $stmt = $conexao->prepare("DELETE FROM atrativos WHERE atrativosid = :id");
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

if (isset($_GET['delete'])) {
    excluirAtrativo(intval($_GET['delete']));
    header("Location: ../views/form_atrativos.php?msg=deleted");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['id'])) {
        atualizarAtrativo(intval($_POST['id']), $_POST['atrativosnome']);
        header("Location: ../views/form_atrativos.php?msg=updated");
        exit;
    } else {
        $nome = $_POST['atrativosnome'];
        
        $stmt = $conexao->prepare("INSERT INTO atrativos (atrativosnome) VALUES (:nome)");
        $stmt->bindParam(':nome', $nome);
        $stmt->execute();

        header("Location: ../views/form_atrativos.php?msg=created");
        exit;
    }
}
?> 