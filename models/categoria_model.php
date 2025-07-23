<?php
require_once '../conexao.php';

class CategoriaModel {
    private $conexao;
    public function __construct($conexao) {
        $this->conexao = $conexao;
    }
    public function criar($nome) {
        try {
            $stmt = $this->conexao->prepare("INSERT INTO categoria (categorianome) VALUES (:nome)");
            $stmt->bindParam(':nome', $nome);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao criar categoria: " . $e->getMessage());
        }
    }
    public function listar() {
        try {
            $stmt = $this->conexao->query("SELECT * FROM categoria ORDER BY categoriaid DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar categorias: " . $e->getMessage());
        }
    }
    public function buscarPorId($id) {
        try {
            $stmt = $this->conexao->prepare("SELECT * FROM categoria WHERE categoriaid = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar categoria: " . $e->getMessage());
        }
    }
    public function atualizar($id, $nome) {
        try {
            $stmt = $this->conexao->prepare("UPDATE categoria SET categorianome = :nome WHERE categoriaid = :id");
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar categoria: " . $e->getMessage());
        }
    }
    public function excluir($id) {
        try {
            $stmt = $this->conexao->prepare("DELETE FROM categoria WHERE categoriaid = :id");
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao excluir categoria: " . $e->getMessage());
        }
    }
}