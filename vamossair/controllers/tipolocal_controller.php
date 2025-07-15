<?php
require_once '../conexao.php';

function criarTipoLocal($nome) {
    global $conexao;
    try {
        $stmt = $conexao->prepare("INSERT INTO tipolocal (tipolocalnome) VALUES (:nome)");
        $stmt->bindParam(':nome', $nome);
        return $stmt->execute();
    } catch (PDOException $e) {
        return false;
    }
}

function listarTipoLocal() {
    global $conexao;
    $stmt = $conexao->query("SELECT * FROM tipolocal ORDER BY tipolocalid DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function buscarTipoLocalPorId($id) {
    global $conexao;
    $stmt = $conexao->prepare("SELECT * FROM tipolocal WHERE tipolocalid = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function atualizarTipoLocal($id, $nome) {
    global $conexao;
    $stmt = $conexao->prepare("UPDATE tipolocal SET tipolocalnome = :nome WHERE tipolocalid = :id");
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

function excluirTipoLocal($id) {
    global $conexao;
    $stmt = $conexao->prepare("DELETE FROM tipolocal WHERE tipolocalid = :id");
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

if (isset($_GET['delete'])) {
    excluirTipoLocal($_GET['delete']);
    header("Location: ../views/form_tipolocal.php");
    exit;
}
?>
