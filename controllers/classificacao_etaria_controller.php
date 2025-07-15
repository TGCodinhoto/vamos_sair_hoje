<?php
require_once '../conexao.php';

function criarClassificacaoEtaria($nome) {
    global $conexao;
    try {
        $stmt = $conexao->prepare("INSERT INTO classificacaoetaria (classificacaonome) VALUES (:nome)");
        $stmt->bindParam(':nome', $nome);
        return $stmt->execute();
    } catch (PDOException $e) {
        return false;
    }
}

function listarClassificacaoEtaria() {
    global $conexao;
    $stmt = $conexao->query("SELECT * FROM classificacaoetaria ORDER BY classificacaoid DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function buscarClassificacaoEtariaPorId($id) {
    global $conexao;
    $stmt = $conexao->prepare("SELECT * FROM classificacaoetaria WHERE classificacaoid = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function atualizarClassificacaoEtaria($id, $nome) {
    global $conexao;
    $stmt = $conexao->prepare("UPDATE classificacaoetaria SET classificacaonome = :nome WHERE classificacaoid = :id");
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

function excluirClassificacaoEtaria($id) {
    global $conexao;
    $stmt = $conexao->prepare("DELETE FROM classificacaoetaria WHERE classificacaoid = :id");
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

if (isset($_GET['delete'])) {
    excluirClassificacaoEtaria($_GET['delete']);
    header("Location: ../views/form_classificacao_etaria.php");
    exit;
}
?>
