<?php
class AtracaoModel
{
    private $conexao;

    public function __construct($conexao)
    {
        $this->conexao = $conexao;
    }

    public function criar($dados)
    {
        try {
            $stmt = $this->conexao->prepare("INSERT INTO atracao (
            publicacaoid, enderecoid, classificacaoid, tipopublicoid,
            segmentoid, categoriaid, atracaotelefone, atracaotelefonewz,
            atracaowebsite, atracaoinstagram, atracaotictoc
        ) VALUES (
            :publicacao_id, :endereco_id, :classificacao_id, :tipo_publico_id,
            :segmento_id, :categoria_id, :telefone, :whatsapp,
            :website, :instagram, :tiktok
        )");

            $stmt->bindParam(':publicacao_id', $dados['publicacao_id']);
            $stmt->bindParam(':endereco_id', $dados['endereco_id']);
            $stmt->bindParam(':classificacao_id', $dados['classificacao_id']);
            $stmt->bindParam(':tipo_publico_id', $dados['tipo_publico_id']);
            $stmt->bindParam(':segmento_id', $dados['segmento_id']);
            $stmt->bindParam(':categoria_id', $dados['categoria_id']);
            $stmt->bindParam(':telefone', $dados['telefone']);
            $stmt->bindParam(':whatsapp', $dados['whatsapp']);
            $stmt->bindParam(':website', $dados['website']);
            $stmt->bindParam(':instagram', $dados['instagram']);
            $stmt->bindParam(':tiktok', $dados['tiktok']);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao criar atração: " . $e->getMessage());
        }
    }

    public function atualizar($dados) {
    try {
        $stmt = $this->conexao->prepare("
            UPDATE atracao SET
                classificacaoid = :classificacao_id,
                tipopublicoid = :tipo_publico_id,
                segmentoid = :segmento_id,
                categoriaid = :categoria_id,
                atracaotelefone = :telefone,
                atracaotelefonewz = :whatsapp,
                atracaowebsite = :website,
                atracaoinstagram = :instagram,
                atracaotictoc = :tiktok
            WHERE publicacaoid = :publicacao_id
        ");

        $stmt->bindParam(':publicacao_id', $dados['publicacao_id']);
        $stmt->bindParam(':classificacao_id', $dados['classificacao_id']);
        $stmt->bindParam(':tipo_publico_id', $dados['tipo_publico_id']);
        $stmt->bindParam(':segmento_id', $dados['segmento_id']);
        $stmt->bindParam(':categoria_id', $dados['categoria_id']);
        $stmt->bindParam(':telefone', $dados['telefone']);
        $stmt->bindParam(':whatsapp', $dados['whatsapp']);
        $stmt->bindParam(':website', $dados['website']);
        $stmt->bindParam(':instagram', $dados['instagram']);
        $stmt->bindParam(':tiktok', $dados['tiktok']);

        return $stmt->execute();
    } catch (PDOException $e) {
        throw new Exception("Erro ao atualizar atração: " . $e->getMessage());
    }
}

public function buscarPorPublicacaoId($publicacaoId) {
    $stmt = $this->conexao->prepare("SELECT * FROM atracao WHERE publicacaoid = :publicacao_id");
    $stmt->bindParam(':publicacao_id', $publicacaoId);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
    
}
