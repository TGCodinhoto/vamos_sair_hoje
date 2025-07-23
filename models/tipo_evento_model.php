<?php
require_once '../conexao.php';

class TipoEventoModel {
    private $conexao;
    public function __construct($conexao) {
        $this->conexao = $conexao;
    }
    public function criar($nome, $imagens) {
        try {
            $stmt = $this->conexao->prepare("INSERT INTO tipoevento (tipoeventonome, tipoeventoimage) VALUES (:nome, :imagens)");
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':imagens', $imagens);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao criar tipo de evento: " . $e->getMessage());
        }
    }
    public function listar() {
        try {
            $stmt = $this->conexao->query("SELECT * FROM tipoevento ORDER BY tipoeventoid DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar tipos de evento: " . $e->getMessage());
        }
    }
    public function buscarPorId($id) {
        try {
            $stmt = $this->conexao->prepare("SELECT * FROM tipoevento WHERE tipoeventoid = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar tipo de evento: " . $e->getMessage());
        }
    }
    public function atualizar($id, $nome) {
        try {
            $stmt = $this->conexao->prepare("UPDATE tipoevento SET tipoeventonome = :nome WHERE tipoeventoid = :id");
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar tipo de evento: " . $e->getMessage());
        }
    }
    public function excluir($id) {
        try {
            $stmt = $this->conexao->prepare("SELECT tipoeventoimage FROM tipoevento WHERE tipoeventoid = :id");
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
                $stmt = $this->conexao->prepare("DELETE FROM tipoevento WHERE tipoeventoid = :id");
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                return true;
            }
            return false;
        } catch (PDOException $e) {
            throw new Exception("Erro ao excluir tipo de evento: " . $e->getMessage());
        }
    }
}