<?php
class PublicacaoModel
{
    private $conexao;

    public function __construct($conexao)
    {
        $this->conexao = $conexao;
    }

    public function criar($dados)
    {
        try {
            $stmt = $this->conexao->prepare("
                INSERT INTO publicacao (
                    userid, publicacaonome, publicacaovalidadein, publicacaovalidadeout,
                    publicacaoauditada, publicacaofoto01, publicacaofoto02, publicacaovideo, publicacaopaga
                ) VALUES (
                    :user_id, :nome, :validade_inicial, :validade_final,
                    :auditado, :foto1, :foto2, :video, :paga
                )
            ");

            $stmt->bindParam(':user_id', $dados['user_id']);
            $stmt->bindParam(':nome', $dados['nome']);
            $stmt->bindParam(':validade_inicial', $dados['validade_inicial']);
            $stmt->bindParam(':validade_final', $dados['validade_final']);
            $stmt->bindParam(':auditado', $dados['auditado']);
            $stmt->bindParam(':foto1', $dados['foto1']);
            $stmt->bindParam(':foto2', $dados['foto2']);
            $stmt->bindParam(':video', $dados['video']);
            $stmt->bindParam(':paga', $dados['paga']);

            $stmt->execute();

            return $this->conexao->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Erro ao criar publicação: " . $e->getMessage());
        }
    }

    public function listar()
    {
        $stmt = $this->conexao->query("SELECT * FROM publicacao ORDER BY publicacaoid DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function atualizar($dados) {
    $stmt = $this->conexao->prepare("
        UPDATE publicacao SET 
            publicacaonome = :nome,
            publicacaovalidadein = :validade_inicial,
            publicacaovalidadeout = :validade_final,
            publicacaoauditada = :auditado,
            publicacaofoto01 = :foto1,
            publicacaofoto02 = :foto2,
            publicacaovideo = :video,
            publicacaopaga = :paga
        WHERE publicacaoid = :publicacao_id
    ");
    return $stmt->execute($dados);
}

    public function buscarPorId($id)
    {
        $stmt = $this->conexao->prepare("SELECT * FROM publicacao WHERE publicacaoid = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
}
