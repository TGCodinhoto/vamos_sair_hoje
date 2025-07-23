<?php
require_once '../conexao.php';

class EstadoModel {
    private $conexao;
    public function __construct($conexao) {
        $this->conexao = $conexao;
    }
    public function criar($nome, $sigla) {
        try {
            $stmt = $this->conexao->prepare("INSERT INTO estado (estadonome, estadosigla) VALUES (:nome, :sigla)");
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':sigla', $sigla);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao criar estado: " . $e->getMessage());
        }
    }
    public function listar() {
        try {
            $stmt = $this->conexao->query("SELECT * FROM estado ORDER BY estadoid DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar estados: " . $e->getMessage());
        }
    }
    public function buscarPorId($id) {
        try {
            $stmt = $this->conexao->prepare("SELECT * FROM estado WHERE estadoid = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar estado: " . $e->getMessage());
        }
    }
    public function atualizar($id, $nome, $sigla) {
        try {
            $stmt = $this->conexao->prepare("UPDATE estado SET estadonome = :nome, estadosigla = :sigla WHERE estadoid = :id");
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':sigla', $sigla);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar estado: " . $e->getMessage());
        }
    }
    public function excluir($id) {
        try {
            $stmt = $this->conexao->prepare("DELETE FROM estado WHERE estadoid = :id");
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao excluir estado: " . $e->getMessage());
        }
    }
}