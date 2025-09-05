<?php
class LocalModel
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
                INSERT INTO local (
                    publicacaoid, tipolocalid,
                    localfoto03, localfoto04, localfoto05
                ) VALUES (
                    :publicacao_id, :tipo_local_id,
                    :foto03, :foto04, :foto05
                )
            ");

            $stmt->bindParam(':publicacao_id', $dados['publicacao_id']);
            $stmt->bindParam(':tipo_local_id', $dados['tipo_local_id']);
            $stmt->bindParam(':foto03', $dados['foto03']);
            $stmt->bindParam(':foto04', $dados['foto04']);
            $stmt->bindParam(':foto05', $dados['foto05']);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao criar local: " . $e->getMessage());
        }
    }

    public function buscarPorPublicacaoId($publicacaoId)
    {
        $stmt = $this->conexao->prepare("
            SELECT l.*, tl.tipolocalnome 
            FROM local l
            LEFT JOIN tipolocal tl ON l.tipolocalid = tl.tipolocalid
            WHERE l.publicacaoid = :publicacaoId
        ");
        $stmt->bindParam(':publicacaoId', $publicacaoId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function excluirPorPublicacaoId($publicacaoId)
    {
        try {
            $stmt = $this->conexao->prepare("SELECT publicacaoid FROM publicacao WHERE publicacaoid = :publicacao_id");
            $stmt->bindParam(':publicacao_id', $publicacaoId);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                throw new Exception("Publicação não encontrada.");
            }

            $this->conexao->beginTransaction();

            // Buscar endereco_id antes de excluir atracao
            $stmt = $this->conexao->prepare("SELECT enderecoid FROM atracao WHERE publicacaoid = :publicacao_id");
            $stmt->bindParam(':publicacao_id', $publicacaoId);
            $stmt->execute();
            $endereco = $stmt->fetch(PDO::FETCH_ASSOC);

            // Excluir local
            $stmt = $this->conexao->prepare("DELETE FROM local WHERE publicacaoid = :publicacao_id");
            $stmt->bindParam(':publicacao_id', $publicacaoId);
            $stmt->execute();

            // Excluir atracao
            $stmt = $this->conexao->prepare("DELETE FROM atracao WHERE publicacaoid = :publicacao_id");
            $stmt->bindParam(':publicacao_id', $publicacaoId);
            $stmt->execute();

            // Excluir endereco se existir
            if ($endereco && $endereco['enderecoid']) {
                $stmt = $this->conexao->prepare("DELETE FROM endereco WHERE enderecoid = :endereco_id");
                $stmt->bindParam(':endereco_id', $endereco['enderecoid']);
                $stmt->execute();
            }

            // Excluir publicacao
            $stmt = $this->conexao->prepare("DELETE FROM publicacao WHERE publicacaoid = :publicacao_id");
            $stmt->bindParam(':publicacao_id', $publicacaoId);
            $stmt->execute();

            $this->conexao->commit();

            return true;
        } catch (PDOException $e) {
            if ($this->conexao->inTransaction()) {
                $this->conexao->rollBack();
            }
            throw new Exception("Erro ao excluir local: " . $e->getMessage());
        }
    }

    public function atualizarLocal($dados)
    {
        // Iniciar transação
        if (!$this->conexao->inTransaction()) {
            $this->conexao->beginTransaction();
        }

        try {
            // Atualizar publicacao
            $sqlPublicacao = "UPDATE publicacao SET 
                            publicacaonome = :nome,
                            publicacaovalidadein = :validadeinicial,
                            publicacaovalidadeout = :validadefinal,
                            publicacaoauditada = :auditado,
                            publicacaopaga = :publicacaopagamento";
            
            // Adicionar fotos na query apenas se foram enviadas
            $params = [
                ':nome' => $dados['nome'],
                ':validadeinicial' => $dados['validade-inicial'],
                ':validadefinal' => $dados['validade-final'],
                ':auditado' => isset($dados['auditado']) ? 1 : 0,
                ':publicacaopagamento' => isset($dados['publicacao-pagamento']) ? 1 : 0,
                ':publicacaoid' => $dados['publicacao_id']
            ];

            if (!empty($dados['foto01'])) {
                $sqlPublicacao .= ", publicacaofoto01 = :foto01";
                $params[':foto01'] = $dados['foto01'];
            }
            if (!empty($dados['foto02'])) {
                $sqlPublicacao .= ", publicacaofoto02 = :foto02";
                $params[':foto02'] = $dados['foto02'];
            }

            $sqlPublicacao .= " WHERE publicacaoid = :publicacaoid";
            
            $stmtPublicacao = $this->conexao->prepare($sqlPublicacao);
            $stmtPublicacao->execute($params);

            // Atualizar endereco se existir
            if (!empty($dados['endereco_id'])) {
                $sqlEndereco = "UPDATE endereco SET
                            enderecorua = :logradouro,
                            endereconumero = :numero,
                            enderecobairro = :bairro,
                            enderecocomplemento = :complemento,
                            enderecocep = :cep,
                            cidadeid = :cidadeid
                          WHERE enderecoid = :enderecoid";

                $stmtEndereco = $this->conexao->prepare($sqlEndereco);
                $stmtEndereco->execute([
                    ':logradouro' => $dados['logradouro'],
                    ':numero' => $dados['numero'],
                    ':bairro' => $dados['bairro'],
                    ':complemento' => $dados['complemento'],
                    ':cep' => $dados['cep'],
                    ':cidadeid' => $dados['cidade'],
                    ':enderecoid' => $dados['endereco_id']
                ]);
            }

            // Atualizar atracao
            $sqlAtracao = "UPDATE atracao SET
                        enderecoid = :enderecoid,
                        classificacaoid = :classificacaoid,
                        tipopublicoid = :tipopublicoid,
                        segmentoid = :segmentoid,
                        categoriaid = :categoriaid,
                        atracaotelefone = :telefone,
                        atracaotelefonewz = :whatsapp,
                        atracaowebsite = :site,
                        atracaoinstagram = :instagram,
                        atracaotictoc = :tiktok
                      WHERE publicacaoid = :publicacaoid";

            $stmtAtracao = $this->conexao->prepare($sqlAtracao);
            $stmtAtracao->execute([
                ':enderecoid' => $dados['endereco_id'],
                ':classificacaoid' => $dados['classificacao'],
                ':tipopublicoid' => $dados['tipo-publicacao'],
                ':segmentoid' => $dados['segmento'],
                ':categoriaid' => $dados['categoria'],
                ':telefone' => $dados['telefone'],
                ':whatsapp' => isset($dados['whatsapp']) ? 1 : 0,
                ':site' => $dados['site'],
                ':instagram' => $dados['instagram'],
                ':tiktok' => $dados['tiktok'],
                ':publicacaoid' => $dados['publicacao_id']
            ]);

            // Atualizar local
            $sqlLocal = "UPDATE local SET
                        tipolocalid = :tipolocalid";
            
            $paramsLocal = [
                ':tipolocalid' => $dados['tipo-local'],
                ':publicacaoid' => $dados['publicacao_id']
            ];

            if (!empty($dados['foto03'])) {
                $sqlLocal .= ", localfoto03 = :foto03";
                $paramsLocal[':foto03'] = $dados['foto03'];
            }
            if (!empty($dados['foto04'])) {
                $sqlLocal .= ", localfoto04 = :foto04";
                $paramsLocal[':foto04'] = $dados['foto04'];
            }
            if (!empty($dados['foto05'])) {
                $sqlLocal .= ", localfoto05 = :foto05";
                $paramsLocal[':foto05'] = $dados['foto05'];
            }

            $sqlLocal .= " WHERE publicacaoid = :publicacaoid";

            $stmtLocal = $this->conexao->prepare($sqlLocal);
            $stmtLocal->execute($paramsLocal);

            $this->conexao->commit();
            return true;
        } catch (Exception $e) {
            $this->conexao->rollBack();
            throw $e;
        }
    }

    public function buscarLocalPorId($publicacao_id)
    {
        $sql = "SELECT 
                p.*,
                a.*,
                l.*,
                en.enderecoid, en.enderecorua, en.endereconumero, en.enderecobairro, 
                en.enderecocep, en.enderecocomplemento, en.cidadeid,
                c.cidadenome, es.estadosigla, es.estadoid,
                ce.classificacaonome,
                tp.tipopubliconome,
                s.segmentonome,
                cat.categorianome,
                tl.tipolocalnome
            FROM publicacao p
            LEFT JOIN atracao a ON p.publicacaoid = a.publicacaoid
            LEFT JOIN local l ON p.publicacaoid = l.publicacaoid
            LEFT JOIN endereco en ON a.enderecoid = en.enderecoid
            LEFT JOIN cidade c ON en.cidadeid = c.cidadeid
            LEFT JOIN estado es ON c.estadoid = es.estadoid
            LEFT JOIN classificacaoetaria ce ON a.classificacaoid = ce.classificacaoid
            LEFT JOIN tipopublico tp ON a.tipopublicoid = tp.tipopublicoid
            LEFT JOIN segmento s ON a.segmentoid = s.segmentoid
            LEFT JOIN categoria cat ON a.categoriaid = cat.categoriaid
            LEFT JOIN tipolocal tl ON l.tipolocalid = tl.tipolocalid
            WHERE p.publicacaoid = :publicacao_id";

        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':publicacao_id', $publicacao_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listarLocaisCompletos($userId = null)
    {
        $sql = "
            SELECT 
                p.publicacaoid,
                p.publicacaonome,
                p.publicacaofoto01,
                p.publicacaofoto02,
                p.publicacaovalidadein,
                p.publicacaovalidadeout,
                p.publicacaovideo,
                p.publicacaoauditada,
                p.publicacaopaga,
                p.userid,
                
                a.atracaotelefone,
                a.atracaowebsite,
                a.atracaotelefonewz,
                a.atracaoinstagram,
                a.atracaotictoc,
                
                l.localfoto03,
                l.localfoto04,
                l.localfoto05,
                tl.tipolocalnome,
                
                en.enderecorua, 
                en.endereconumero, 
                en.enderecobairro, 
                en.enderecocep, 
                en.enderecocomplemento,
                c.cidadenome AS nome_cidade, 
                es.estadosigla,
                
                cl.classificacaonome,
                tp.tipopubliconome,
                s.segmentonome,
                cat.categorianome
                
            FROM 
                publicacao p
            JOIN local l ON p.publicacaoid = l.publicacaoid
            JOIN atracao a ON p.publicacaoid = a.publicacaoid
            LEFT JOIN tipolocal tl ON l.tipolocalid = tl.tipolocalid
            LEFT JOIN endereco en ON a.enderecoid = en.enderecoid
            LEFT JOIN cidade c ON en.cidadeid = c.cidadeid
            LEFT JOIN estado es ON c.estadoid = es.estadoid
            LEFT JOIN classificacaoetaria cl ON a.classificacaoid = cl.classificacaoid
            LEFT JOIN tipopublico tp ON a.tipopublicoid = tp.tipopublicoid
            LEFT JOIN segmento s ON a.segmentoid = s.segmentoid
            LEFT JOIN categoria cat ON a.categoriaid = cat.categoriaid
            WHERE 1=1";
        
        if ($userId !== null) {
            $sql .= " AND p.userid = :userid";
        }
        
        $sql .= " ORDER BY p.publicacaoid DESC";

        $stmt = $this->conexao->prepare($sql);
        
        if ($userId !== null) {
            $stmt->bindParam(':userid', $userId);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}