<?php
class EventoModel
{
    private $conexao;

    public function __construct($conexao)
    {
        $this->conexao = $conexao;
    }

    public function criar($dados)
    {
        try {
            // Debug: Log do local de realização selecionado
            if (isset($dados['local_realizacao_id'])) {
                error_log("Local de realização selecionado: " . $dados['local_realizacao_id']);
            }

            $stmt = $this->conexao->prepare("
                INSERT INTO evento (
                    publicacaoid, tipoeventoid, formatoid,
                    eventoexpectativa, eventodia, eventohora,
                    eventoinformacao, eventolinkingresso, eventoduracao
                ) VALUES (
                    :publicacao_id, :tipo_evento_id, :formato_id,
                    :expectativa, :dia, :hora,
                    :informacoes, :link_ingresso, :duracao
                )
            ");

            $stmt->bindParam(':publicacao_id', $dados['publicacao_id']);
            $stmt->bindParam(':tipo_evento_id', $dados['tipo_evento_id']);
            $stmt->bindParam(':formato_id', $dados['formato_id']);
            $stmt->bindParam(':expectativa', $dados['expectativa']);
            $stmt->bindParam(':dia', $dados['dia']);
            $stmt->bindParam(':hora', $dados['hora']);
            $stmt->bindParam(':informacoes', $dados['informacoes']);
            $stmt->bindParam(':link_ingresso', $dados['link_ingresso']);
            $stmt->bindParam(':duracao', $dados['duracao']);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao criar evento: " . $e->getMessage());
        }
    }

    public function buscarPorPublicacaoId($publicacaoId)
    {
        $stmt = $this->conexao->prepare("
            SELECT e.*, te.tipoeventonome, fe.formatonome 
            FROM evento e
            LEFT JOIN tipoevento te ON e.tipoeventoid = te.tipoeventoid
            LEFT JOIN formatoevento fe ON e.formatoid = fe.formatoid
            WHERE e.publicacaoid = :publicacaoId
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

            // Excluir evento
            $stmt = $this->conexao->prepare("DELETE FROM evento WHERE publicacaoid = :publicacao_id");
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
            throw new Exception("Erro ao excluir evento: " . $e->getMessage());
        }
    }

    public function atualizarEvento($dados)
    {
        // Iniciar transação
        $this->conexao->beginTransaction();

        try {
            // Atualizar publicacao
            $sqlPublicacao = "UPDATE publicacao SET 
                            publicacaonome = :nome,
                            publicacaovalidadein = :validadeinicial,
                            publicacaovalidadeout = :validadefinal,
                            publicacaoauditada = :auditado,
                            publicacaopaga = :publicacaopagamento";
            
            // Adicionar fotos e vídeo na query apenas se foram enviados
            $params = [
                ':nome' => $dados['nome'],
                ':validadeinicial' => $dados['validade-inicial'],
                ':validadefinal' => $dados['validade-final'],
                ':auditado' => isset($dados['auditado']) ? 1 : 0,
                ':publicacaopagamento' => isset($dados['publicacao-pagamento']) ? 1 : 0,
                ':publicacaoid' => $dados['publicacao_id']
            ];

            if (!empty($dados['foto1'])) {
                $sqlPublicacao .= ", publicacaofoto01 = :foto1";
                $params[':foto1'] = $dados['foto1'];
            }
            if (!empty($dados['foto2'])) {
                $sqlPublicacao .= ", publicacaofoto02 = :foto2";
                $params[':foto2'] = $dados['foto2'];
            }
            if (!empty($dados['video'])) {
                $sqlPublicacao .= ", publicacaovideo = :video";
                $params[':video'] = $dados['video'];
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

            // Atualizar evento
            $sqlEvento = "UPDATE evento SET
                        tipoeventoid = :tipoeventoid,
                        loc_publicacaoid = :loc_publicacaoid,
                        formatoid = :formatoid,
                        eventoexpectativa = :expectativa,
                        eventodia = :diaevento,
                        eventohora = :horaevento,
                        eventoduracao = :duracao,
                        eventoinformacao = :infosgerais,
                        eventolinkingresso = :linkcompra
                      WHERE publicacaoid = :publicacaoid";

            $stmtEvento = $this->conexao->prepare($sqlEvento);
            $stmtEvento->execute([
                ':tipoeventoid' => $dados['tipo-evento'],
                ':loc_publicacaoid' => $dados['loc_publicacaoid'],
                ':formatoid' => $dados['formato-evento'],
                ':expectativa' => $dados['expectativa'],
                ':diaevento' => $dados['dia-evento'],
                ':horaevento' => $dados['hora-evento'],
                ':duracao' => $dados['duracao-evento'],
                ':infosgerais' => $dados['infos-gerais'],
                ':linkcompra' => $dados['link-compra'],
                ':publicacaoid' => $dados['publicacao_id']
            ]);

            $this->conexao->commit();
            return true;
        } catch (Exception $e) {
            $this->conexao->rollBack();
            throw $e;
        }
    }

    public function buscarEventoPorId($publicacao_id)
    {
        $sql = "SELECT 
                p.*,
                a.*,
                e.*,
                en.enderecoid, en.enderecorua, en.endereconumero, en.enderecobairro, 
                en.enderecocep, en.enderecocomplemento, en.cidadeid,
                c.cidadenome, es.estadosigla, es.estadoid,
                ce.classificacaonome,
                tp.tipopubliconome,
                s.segmentonome,
                cat.categorianome,
                te.tipoeventonome,
                fe.formatonome
            FROM publicacao p
            LEFT JOIN atracao a ON p.publicacaoid = a.publicacaoid
            LEFT JOIN evento e ON p.publicacaoid = e.publicacaoid
            LEFT JOIN endereco en ON a.enderecoid = en.enderecoid
            LEFT JOIN cidade c ON en.cidadeid = c.cidadeid
            LEFT JOIN estado es ON c.estadoid = es.estadoid
            LEFT JOIN classificacaoetaria ce ON a.classificacaoid = ce.classificacaoid
            LEFT JOIN tipopublico tp ON a.tipopublicoid = tp.tipopublicoid
            LEFT JOIN segmento s ON a.segmentoid = s.segmentoid
            LEFT JOIN categoria cat ON a.categoriaid = cat.categoriaid
            LEFT JOIN tipoevento te ON e.tipoeventoid = te.tipoeventoid
            LEFT JOIN formatoevento fe ON e.formatoid = fe.formatoid
            WHERE p.publicacaoid = :publicacao_id";

        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':publicacao_id', $publicacao_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
