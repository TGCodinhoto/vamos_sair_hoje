<?php
require_once '../conexao.php';

function criarCategoria($nome) {
    global $conexao;
    try {
        $stmt = $conexao->prepare("INSERT INTO categoria (categorianome) VALUES (:nome)");
        $stmt->bindParam(':nome', $nome);
        return $stmt->execute();
    } catch (PDOException $e) {
        return false;
    }
}

function listarCategorias() {
    global $conexao;
    $stmt = $conexao->query("SELECT * FROM categoria ORDER BY categoriaid DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function buscarCategoriaPorId($id) {
    global $conexao;
    $stmt = $conexao->prepare("SELECT * FROM categoria WHERE categoriaid = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function atualizarCategoria($id, $nome) {
    global $conexao;
    $stmt = $conexao->prepare("UPDATE categoria SET categorianome = :nome WHERE categoriaid = :id");
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

function excluirCategoria($id) {
    global $conexao;
    $stmt = $conexao->prepare("DELETE FROM categoria WHERE categoriaid = :id");
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

if (isset($_GET['delete'])) {
    excluirCategoria($_GET['delete']);
    header("Location: ../views/form_categoria.php");
    exit;
}
?>
