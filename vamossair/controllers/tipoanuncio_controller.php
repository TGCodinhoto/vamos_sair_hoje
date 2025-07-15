<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../conexao.php';

function listarTiposAnuncio() {
    global $conexao;
    $stmt = $conexao->query("SELECT * FROM tipoanuncio ORDER BY tipoanuncioid DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function buscarTipoAnuncioPorId($id) {
    global $conexao;
    $stmt = $conexao->prepare("SELECT * FROM tipoanuncio WHERE tipoanuncioid = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function atualizarTipoAnuncio($id, $nome) {
    global $conexao;
    $stmt = $conexao->prepare("UPDATE tipoanuncio SET tipoanuncionome = :nome WHERE tipoanuncioid = :id");
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

function excluirTipoAnuncio($id) {
    global $conexao;
    $stmt = $conexao->prepare("DELETE FROM tipoanuncio WHERE tipoanuncioid = :id");
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

if (isset($_GET['delete'])) {
    excluirTipoAnuncio(intval($_GET['delete']));
    header("Location: ../views/form_tipoanuncio.php?msg=deleted");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['id'])) {
        atualizarTipoAnuncio(intval($_POST['id']), $_POST['tipoanuncionome']);
        header("Location: ../views/form_tipoanuncio.php?msg=updated");
        exit;
    } else {
        $nome = $_POST['tipoanuncionome'];
        
        $stmt = $conexao->prepare("INSERT INTO tipoanuncio (tipoanuncionome) VALUES (:nome)");
        $stmt->bindParam(':nome', $nome);
        $stmt->execute();

        header("Location: ../views/form_tipoanuncio.php?msg=created");
        exit;
    }
}
?> 