<?php
require_once '../conexao.php';

class AtrativosModel {
    private $conexao;
    public function __construct($conexao) {
        $this->conexao = $conexao;
    }
    public function listar() {
        $stmt = $this->conexao->query("SELECT * FROM atrativos ORDER BY atrativosid DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function buscarPorId($id) {
        $stmt = $this->conexao->prepare("SELECT * FROM atrativos WHERE atrativosid = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function atualizar($id, $nome) {
        $stmt = $this->conexao->prepare("UPDATE atrativos SET atrativosnome = :nome WHERE atrativosid = :id");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    public function excluir($id) {
        $stmt = $this->conexao->prepare("DELETE FROM atrativos WHERE atrativosid = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    public function criar($nome) {
        $stmt = $this->conexao->prepare("INSERT INTO atrativos (atrativosnome) VALUES (:nome)");
        $stmt->bindParam(':nome', $nome);
        return $stmt->execute();
    }
}