<?php
class AnuncianteModel
{
    private $conexao;

    public function __construct($conexao)
    {
        $this->conexao = $conexao;
    }

    public function buscarPorPublicacaoId($publicacaoid)
    {
        $stmt = $this->conexao->prepare("SELECT * FROM anunciante WHERE publicacaoid = :publicacaoid");
        $stmt->bindParam(':publicacaoid', $publicacaoid);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function criar($dados)
    {
        $stmt = $this->conexao->prepare("
        INSERT INTO anunciante (publicacaoid, anunciantebanner)
        VALUES (:publicacaoid, :banner)
    ");

        return $stmt->execute([
            ':publicacaoid' => $dados['publicacaoid'],
            ':banner' => $dados['banner']
        ]);
    }

    public function excluir($publicacaoid)
    {
        $stmt = $this->conexao->prepare("DELETE FROM anunciante WHERE publicacaoid = :publicacaoid");
        return $stmt->execute([':publicacaoid' => $publicacaoid]);
    }
}
