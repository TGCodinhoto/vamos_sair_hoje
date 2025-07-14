<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../conexao.php';

function listarTiposEvento() {
    global $conexao;
    $stmt = $conexao->query("SELECT * FROM tipoevento ORDER BY tipoeventoid DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function buscarTipoEventoPorId($id) {
    global $conexao;
    $stmt = $conexao->prepare("SELECT * FROM tipoevento WHERE tipoeventoid = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function atualizarTipoEvento($id, $nome) {
    global $conexao;
    $stmt = $conexao->prepare("UPDATE tipoevento SET tipoeventonome = :nome WHERE tipoeventoid = :id");
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

function excluirTipoEvento($id) {
    global $conexao;

    $stmt = $conexao->prepare("SELECT tipoeventoimage FROM tipoevento WHERE tipoeventoid = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $evento = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($evento) {
        $imagens = json_decode($evento['tipoeventoimage'], true);
        foreach ($imagens as $img) {
            $caminho = '../uploads/' . $img;
            if (file_exists($caminho)) {
                unlink($caminho);
            }
        }

        $stmt = $conexao->prepare("DELETE FROM tipoevento WHERE tipoeventoid = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}

if (isset($_GET['delete'])) {
    excluirTipoEvento(intval($_GET['delete']));
    header("Location: ../views/form_tipoevento.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['id'])) {
        atualizarTipoEvento(intval($_POST['id']), $_POST['tipoeventonome']);
        header("Location: ../views/form_tipoevento.php?msg=updated");
        exit;
    } else {
        $name = $_POST['tipoeventonome'];
        $totalFiles = count($_FILES['fileImg']['name']);
        $filesArray = [];
        $uploadDir = '../uploads/';

        if (!is_dir($uploadDir) || !is_writable($uploadDir)) {
            die("Erro: A pasta uploads não existe ou não é gravável.");
        }

        for ($i = 0; $i < $totalFiles; $i++) {
            $imageName = $_FILES['fileImg']['name'][$i];
            $imageTmpName = $_FILES['fileImg']['tmp_name'][$i];
            $imageExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
            $newImageName = uniqid() . '.' . $imageExtension;

            if (move_uploaded_file($imageTmpName, $uploadDir . $newImageName)) {
                $filesArray[] = $newImageName;
            } else {
                die("Erro ao mover o arquivo: $imageName");
            }
        }

        $filesArrayJson = json_encode($filesArray);

        $stmt = $conexao->prepare("INSERT INTO tipoevento (tipoeventonome, tipoeventoimage) VALUES (:nome, :imagens)");
        $stmt->bindParam(':nome', $name);
        $stmt->bindParam(':imagens', $filesArrayJson);
        $stmt->execute();

        header("Location: ../views/form_tipoevento.php?msg=created");
        exit;
    }
}
