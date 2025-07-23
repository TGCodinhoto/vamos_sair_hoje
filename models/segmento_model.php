<?php
require_once '../conexao.php';

class SegmentoModel {
    private $conexao;
    public function __construct($conexao) {
        $this->conexao = $conexao;
    }
    public function criar($nome) {
        try {
            $stmt = $this->conexao->prepare("INSERT INTO segmento (segmentonome) VALUES (:nome)");
            $stmt->bindParam(':nome', $nome);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao criar segmento: " . $e->getMessage());
        }
    }
    public function listar() {
        try {
            $stmt = $this->conexao->query("SELECT * FROM segmento ORDER BY segmentoid DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar segmentos: " . $e->getMessage());
        }
    }
    public function buscarPorId($id) {
        try {
            $stmt = $this->conexao->prepare("SELECT * FROM segmento WHERE segmentoid = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar segmento: " . $e->getMessage());
        }
    }
    public function atualizar($id, $nome) {
        try {
            $stmt = $this->conexao->prepare("UPDATE segmento SET segmentonome = :nome WHERE segmentoid = :id");
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar segmento: " . $e->getMessage());
        }
    }
    public function excluir($id) {
        try {
            $stmt = $this->conexao->prepare("DELETE FROM segmento WHERE segmentoid = :id");
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao excluir segmento: " . $e->getMessage());
        }
    }
}