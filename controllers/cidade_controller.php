<?php
require_once '../conexao.php';

function criarCidade($nome, $estadoid) {
    global $conexao;
    try {
        $stmt = $conexao->prepare("INSERT INTO cidade (cidadenome, estadoid) VALUES (:nome, :estadoid)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':estadoid', $estadoid);
        return $stmt->execute();
    } catch (PDOException $e) {
        return false;
    }
}

function listarCidades() {
    global $conexao;
    $stmt = $conexao->query("
        SELECT c.cidadeid, c.cidadenome, e.estadonome, e.estadosigla
        FROM cidade c
        JOIN estado e ON c.estadoid = e.estadoid
        ORDER BY c.cidadeid DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function buscarCidadePorId($id) {
    global $conexao;
    $stmt = $conexao->prepare("SELECT * FROM cidade WHERE cidadeid = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function atualizarCidade($id, $nome, $estadoid) {
    global $conexao;
    $stmt = $conexao->prepare("UPDATE cidade SET cidadenome = :nome, estadoid = :estadoid WHERE cidadeid = :id");
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':estadoid', $estadoid);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

function excluirCidade($id) {
    global $conexao;
    $stmt = $conexao->prepare("DELETE FROM cidade WHERE cidadeid = :id");
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

if (isset($_GET['delete'])) {
    excluirCidade($_GET['delete']);
    header("Location: ../views/form_cidade.php");
    exit;
}
