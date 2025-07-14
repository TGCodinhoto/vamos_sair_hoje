<?php
require_once '../conexao.php';

function criarEstado($nome) {
    global $conexao;
    try {
        $stmt = $conexao->prepare("INSERT INTO estado (estadonome) VALUES (:nome)");
        $stmt->bindParam(':nome', $nome);
        return $stmt->execute();
    } catch (PDOException $e) {
        return false;
    }
}

function listarEstados() {
    global $conexao;
    $stmt = $conexao->query("SELECT * FROM estado ORDER BY estadoid DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function buscarEstadoPorId($id) {
    global $conexao;
    $stmt = $conexao->prepare("SELECT * FROM estado WHERE estadoid = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function atualizarEstado($id, $nome) {
    global $conexao;
    $stmt = $conexao->prepare("UPDATE estado SET estadonome = :nome WHERE estadoid = :id");
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

function excluirEstado($id) {
    global $conexao;
    $stmt = $conexao->prepare("DELETE FROM estado WHERE estadoid = :id");
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

if (isset($_GET['delete'])) {
    excluirEstado($_GET['delete']);
    header("Location: ../views/form_estado.php");
    exit;
}
?>
