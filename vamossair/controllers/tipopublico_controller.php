<?php
require_once '../conexao.php';

function criarTipoPublico($nome) {
    global $conexao;
    try {
        $stmt = $conexao->prepare("INSERT INTO tipopublico (tipopubliconome) VALUES (:nome)");
        $stmt->bindParam(':nome', $nome);
        return $stmt->execute();
    } catch (PDOException $e) {
        return false;
    }
}

function listarTipoPublico() {
    global $conexao;
    $stmt = $conexao->query("SELECT * FROM tipopublico ORDER BY tipopublicoid DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function buscarTipoPublicoPorId($id) {
    global $conexao;
    $stmt = $conexao->prepare("SELECT * FROM tipopublico WHERE tipopublicoid = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function atualizarTipoPublico($id, $nome) {
    global $conexao;
    $stmt = $conexao->prepare("UPDATE tipopublico SET tipopubliconome = :nome WHERE tipopublicoid = :id");
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

function excluirCategoria($id) {
    global $conexao;
    $stmt = $conexao->prepare("DELETE FROM tipopublico WHERE tipopublicoid = :id");
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

if (isset($_GET['delete'])) {
    excluirCategoria($_GET['delete']);
    header("Location: ../views/form_tipopublico.php");
    exit;
}
?>
