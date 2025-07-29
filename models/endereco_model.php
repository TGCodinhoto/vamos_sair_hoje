<?php
class EnderecoModel
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
                INSERT INTO endereco (
                    cidadeid, enderecorua, endereconumero, enderecobairro,
                    enderecocep, enderecocomplemento
                ) VALUES (
                    :cidade_id, :logradouro, :numero, :bairro,
                    :cep, :complemento
                )
            ");

            $stmt->bindParam(':cidade_id', $dados['cidade']);
            $stmt->bindParam(':logradouro', $dados['logradouro']);
            $stmt->bindParam(':numero', $dados['numero']);
            $stmt->bindParam(':bairro', $dados['bairro']);
            $stmt->bindParam(':cep', $dados['cep']);
            $stmt->bindParam(':complemento', $dados['complemento']);

            $stmt->execute();

            return $this->conexao->lastInsertId();

        } catch (PDOException $e) {
            throw new Exception("Erro ao criar endereço no banco: " . $e->getMessage());
        }
    }

    public function atualizar($dados)
    {
        try {
            $stmt = $this->conexao->prepare("
                UPDATE endereco SET 
                    enderecorua = :logradouro,
                    endereconumero = :numero,
                    enderecobairro = :bairro,
                    enderecocep = :cep,
                    enderecocomplemento = :complemento,
                    cidadeid = :cidade
                WHERE enderecoid = :endereco_id
            ");

            return $stmt->execute([
                ':logradouro' => $dados['logradouro'],
                ':numero' => $dados['numero'],
                ':bairro' => $dados['bairro'],
                ':cep' => $dados['cep'],
                ':complemento' => $dados['complemento'],
                ':cidade' => $dados['cidade'],
                ':endereco_id' => $dados['endereco_id']
            ]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar endereço: " . $e->getMessage());
        }
    }

    public function buscarPorId($enderecoId)
    {
        $stmt = $this->conexao->prepare("
            SELECT e.*, c.cidadenome, es.estadosigla, c.estadoid
            FROM endereco e
            LEFT JOIN cidade c ON e.cidadeid = c.cidadeid
            LEFT JOIN estado es ON c.estadoid = es.estadoid
            WHERE e.enderecoid = :enderecoId
        ");
        $stmt->bindParam(':enderecoId', $enderecoId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
