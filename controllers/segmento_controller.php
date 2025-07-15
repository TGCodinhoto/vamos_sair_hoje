<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../conexao.php';

function listarSegmentos() {
    global $conexao;
    $stmt = $conexao->query("SELECT * FROM segmento ORDER BY segmentoid DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function buscarSegmentoPorId($id) {
    global $conexao;
    $stmt = $conexao->prepare("SELECT * FROM segmento WHERE segmentoid = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function atualizarSegmento($id, $nome) {
    global $conexao;
    $stmt = $conexao->prepare("UPDATE segmento SET segmentonome = :nome WHERE segmentoid = :id");
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

function excluirSegmento($id) {
    global $conexao;
    $stmt = $conexao->prepare("DELETE FROM segmento WHERE segmentoid = :id");
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

if (isset($_GET['delete'])) {
    excluirSegmento(intval($_GET['delete']));
    header("Location: ../views/form_segmento.php?msg=deleted");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['id'])) {
        atualizarSegmento(intval($_POST['id']), $_POST['segmentonome']);
        header("Location: ../views/form_segmento.php?msg=updated");
        exit;
    } else {
        $nome = $_POST['segmentonome'];
        
        $stmt = $conexao->prepare("INSERT INTO segmento (segmentonome) VALUES (:nome)");
        $stmt->bindParam(':nome', $nome);
        $stmt->execute();

        header("Location: ../views/form_segmento.php?msg=created");
        exit;
    }
}
?> 