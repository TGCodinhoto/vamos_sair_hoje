<?php

require_once __DIR__ . '/../conexao.php';
require_once __DIR__ . '/../models/tipo_evento_model.php';

$tipoEventoModel = new TipoEventoModel($conexao);

if (isset($_GET['delete'])) {
    try {
        $resultado = $tipoEventoModel->excluir(intval($_GET['delete']));
        if ($resultado) {
            header("Location: ../views/form_tipoevento.php?msg=deleted");
        } else {
            header("Location: ../views/form_tipoevento.php?msg=error");
        }
    } catch (Exception $e) {
        error_log("Erro ao excluir tipo de evento: " . $e->getMessage());
        $erro = "Ocorreu um erro ao processar sua solicitação. Tente novamente mais tarde.";
        header("Location: ../views/form_tipoevento.php?msg=error&erro=" . urlencode($erro));
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!empty($_POST['id'])) {
            $resultado = $tipoEventoModel->atualizar(intval($_POST['id']), $_POST['tipoeventonome']);
            if ($resultado) {
                header("Location: ../views/form_tipoevento.php?msg=updated");
            } else {
                header("Location: ../views/form_tipoevento.php?msg=error");
            }
        } else {
            $nome = $_POST['tipoeventonome'];
            $nomeImagem = '';

            // Tratamento do upload de imagem
            if (isset($_FILES['fileImg']) && $_FILES['fileImg']['error'][0] === UPLOAD_ERR_OK) {
                $extensoesPermitidas = ['jpg', 'jpeg', 'png'];
                $nomeOriginal = $_FILES['fileImg']['name'][0];
                $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));

                if (in_array($extensao, $extensoesPermitidas)) {
                    $nomeUnico = uniqid() . '.' . $extensao;
                    $caminhoDestino = '../uploads/' . $nomeUnico;

                    if (move_uploaded_file($_FILES['fileImg']['tmp_name'][0], $caminhoDestino)) {
                        $nomeImagem = $nomeUnico;
                    } else {
                        throw new Exception("Falha ao mover a imagem enviada.");
                    }
                } else {
                    throw new Exception("Extensão de arquivo não permitida.");
                }
            } else {
                throw new Exception("Nenhuma imagem foi enviada.");
            }

            $resultado = $tipoEventoModel->criar($nome, $nomeImagem);

            if ($resultado) {
                header("Location: ../views/form_tipoevento.php?msg=created");
            } else {
                header("Location: ../views/form_tipoevento.php?msg=error");
            }
        }
    } catch (Exception $e) {
        error_log("Erro ao cadastrar tipo de evento: " . $e->getMessage());
        $erro = "Ocorreu um erro ao processar sua solicitação. Tente novamente mais tarde.";
        header("Location: ../views/form_tipoevento.php?msg=error&erro=" . urlencode($erro));
    }
    exit;
}

function listarTiposEvento() {
    global $tipoEventoModel;
    return $tipoEventoModel->listar();
}

function buscarTipoEventoPorId($id) {
    global $tipoEventoModel;
    return $tipoEventoModel->buscarPorId($id);
}
