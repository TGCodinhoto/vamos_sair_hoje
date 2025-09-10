<?php
require_once '../models/publicacao_model.php';
require_once '../utils/session_manager.php';
SessionManager::iniciarSessao();

if (!isset($_SESSION['userid']) || intval($_SESSION['usertipo']) !== 1) {
    header("Location: ../index.php");
    exit();
}

$publicacaoModel = new PublicacaoModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['aprovar'])) {
        $id = intval($_POST['aprovar']);
        $publicacaoModel->auditar($id, 1);
    } elseif (isset($_POST['desaprovar'])) {
        $id = intval($_POST['desaprovar']);
        $publicacaoModel->auditar($id, 0);
    }
}
header('Location: ../views/form_auditoria.php');
exit();
