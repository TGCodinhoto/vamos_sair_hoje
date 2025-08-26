<?php
require_once  __DIR__ . '/../conexao.php';

class CidadeModel {
    private $conexao;
    public function __construct($conexao) {
        $this->conexao = $conexao;
    }
    public function criar($nome, $estadoid) {
        try {
            $stmt = $this->conexao->prepare("INSERT INTO cidade (cidadenome, estadoid) VALUES (:nome, :estadoid)");
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':estadoid', $estadoid);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    public function listar() {
        $stmt = $this->conexao->query("SELECT c.cidadeid, c.cidadenome, e.estadonome, e.estadosigla FROM cidade c JOIN estado e ON c.estadoid = e.estadoid ORDER BY c.cidadeid DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function buscarPorId($id) {
        $stmt = $this->conexao->prepare("SELECT * FROM cidade WHERE cidadeid = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function atualizar($id, $nome, $estadoid) {
        $stmt = $this->conexao->prepare("UPDATE cidade SET cidadenome = :nome, estadoid = :estadoid WHERE cidadeid = :id");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':estadoid', $estadoid);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    public function excluir($id) {
        $stmt = $this->conexao->prepare("DELETE FROM cidade WHERE cidadeid = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}