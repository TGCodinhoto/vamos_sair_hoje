<?php
require_once '../conexao.php';

class TipoLocalModel {
    private $conexao;
    public function __construct($conexao) {
        $this->conexao = $conexao;
    }
    public function criar($nome) {
        try {
            $stmt = $this->conexao->prepare("INSERT INTO tipolocal (tipolocalnome) VALUES (:nome)");
            $stmt->bindParam(':nome', $nome);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao criar tipo de local: " . $e->getMessage());
        }
    }
    public function listar() {
        try {
            $stmt = $this->conexao->query("SELECT * FROM tipolocal ORDER BY tipolocalid DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar tipos de local: " . $e->getMessage());
        }
    }
    public function buscarPorId($id) {
        try {
            $stmt = $this->conexao->prepare("SELECT * FROM tipolocal WHERE tipolocalid = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar tipo de local: " . $e->getMessage());
        }
    }
    public function atualizar($id, $nome) {
        try {
            $stmt = $this->conexao->prepare("UPDATE tipolocal SET tipolocalnome = :nome WHERE tipolocalid = :id");
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar tipo de local: " . $e->getMessage());
        }
    }
    public function excluir($id) {
        try {
            $stmt = $this->conexao->prepare("DELETE FROM tipolocal WHERE tipolocalid = :id");
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao excluir tipo de local: " . $e->getMessage());
        }
    }
}