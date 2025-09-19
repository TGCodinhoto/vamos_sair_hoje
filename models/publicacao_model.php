<?php
class PublicacaoModel
{
    private $conexao;

    public function __construct($conexao)
    {
        $this->conexao = $conexao;
    }


    public function listarTudo()
    {
        try {
            $sql = "
                SELECT 
                    p.publicacaoid,
                    p.userid,
                    p.publicacaonome,
                    p.publicacaovalidadein,
                    p.publicacaovalidadeout,
                    p.publicacaoauditada,
                    p.publicacaofoto01,
                    p.publicacaofoto02,
                    p.publicacaovideo,
                    p.publicacaopaga,
                    a.anunciantebanner
                FROM 
                    publicacao p
                INNER JOIN 
                    anunciante a ON p.publicacaoid = a.publicacaoid
                ORDER BY 
                    p.publicacaoid DESC
            ";

            $stmt = $this->conexao->query($sql);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
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

    public function atualizar($dados)
    {
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

    public function excluir($publicacaoid)
    {
        try {
            $this->conexao->beginTransaction();
            
            // Buscar endereco_id antes de excluir atracao
            $stmt = $this->conexao->prepare("SELECT enderecoid FROM atracao WHERE publicacaoid = :publicacao_id");
            $stmt->bindParam(':publicacao_id', $publicacaoid);
            $stmt->execute();
            $endereco = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Excluir evento se existir
            $stmt = $this->conexao->prepare("DELETE FROM evento WHERE publicacaoid = :publicacao_id");
            $stmt->bindParam(':publicacao_id', $publicacaoid);
            $stmt->execute();
            
            // Excluir local se existir
            $stmt = $this->conexao->prepare("DELETE FROM local WHERE publicacaoid = :publicacao_id");
            $stmt->bindParam(':publicacao_id', $publicacaoid);
            $stmt->execute();
            
            // Excluir atracao
            $stmt = $this->conexao->prepare("DELETE FROM atracao WHERE publicacaoid = :publicacao_id");
            $stmt->bindParam(':publicacao_id', $publicacaoid);
            $stmt->execute();
            
            // Excluir endereco se existir
            if ($endereco && $endereco['enderecoid']) {
                $stmt = $this->conexao->prepare("DELETE FROM endereco WHERE enderecoid = :endereco_id");
                $stmt->bindParam(':endereco_id', $endereco['enderecoid']);
                $stmt->execute();
            }
            
            // Excluir publicacao
            $stmt = $this->conexao->prepare("DELETE FROM publicacao WHERE publicacaoid = :publicacao_id");
            $stmt->bindParam(':publicacao_id', $publicacaoid);
            $stmt->execute();
            
            $this->conexao->commit();
            return true;
        } catch (PDOException $e) {
            if ($this->conexao->inTransaction()) {
                $this->conexao->rollBack();
            }
            throw new Exception("Erro ao excluir publicação: " . $e->getMessage());
        }
    }
    
        // Retorna eventos e locais não auditados
        public function getNaoAuditados() {
            // Query para eventos
            $sqlEventos = "SELECT p.publicacaoid, p.publicacaonome, p.publicacaoauditada, 'Evento' as tipo
                          FROM publicacao p
                          INNER JOIN evento e ON p.publicacaoid = e.publicacaoid
                          WHERE (p.publicacaoauditada IS NULL OR p.publicacaoauditada = 0)";
            
            // Query para locais
            $sqlLocais = "SELECT p.publicacaoid, p.publicacaonome, p.publicacaoauditada, 'Local' as tipo
                         FROM publicacao p
                         INNER JOIN local l ON p.publicacaoid = l.publicacaoid
                         WHERE (p.publicacaoauditada IS NULL OR p.publicacaoauditada = 0)";
            
            // União das duas queries
            $sql = "($sqlEventos) UNION ($sqlLocais) ORDER BY publicacaoid DESC";
            
            $stmt = $this->conexao->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    
        // Atualiza status de auditoria
        public function auditar($publicacaoid, $status) {
            $stmt = $this->conexao->prepare("UPDATE publicacao SET publicacaoauditada = :status WHERE publicacaoid = :id");
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $publicacaoid, PDO::PARAM_INT);
            return $stmt->execute();
        }
}
