<?php
require_once '../conexao.php';

class FormatoEventoModel {
    private $conexao;
    public function __construct($conexao) {
        $this->conexao = $conexao;
    }
    public function criar($nome, $descricao) {
        try {
            $stmt = $this->conexao->prepare("INSERT INTO formatoevento (formatonome, formatodescricao) VALUES (:nome, :descricao)");
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':descricao', $descricao);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao criar formato de evento: " . $e->getMessage());
        }
    }
    public function listar() {
        try {
            $stmt = $this->conexao->query("SELECT * FROM formatoevento ORDER BY formatoid DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar formatos de evento: " . $e->getMessage());
        }
    }
    public function buscarPorId($id) {
        try {
            $stmt = $this->conexao->prepare("SELECT * FROM formatoevento WHERE formatoid = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar formato de evento: " . $e->getMessage());
        }
    }
    public function atualizar($id, $nome, $descricao) {
        try {
            $stmt = $this->conexao->prepare("UPDATE formatoevento SET formatonome = :nome, formatodescricao = :descricao WHERE formatoid = :id");
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar formato de evento: " . $e->getMessage());
        }
    }
    public function excluir($id) {
        try {
            $stmt = $this->conexao->prepare("DELETE FROM formatoevento WHERE formatoid = :id");
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao excluir formato de evento: " . $e->getMessage());
        }
    }
}