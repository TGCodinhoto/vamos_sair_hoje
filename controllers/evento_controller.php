<?php
ob_start(); // Inicia o buffer de saída

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../conexao.php';
require_once __DIR__ . '/base_controller.php';
require_once ROOT_PATH . '/models/publicacao_model.php';
require_once ROOT_PATH . '/models/atracao_model.php';
require_once ROOT_PATH . '/models/endereco_model.php';
require_once ROOT_PATH . '/models/evento_model.php';

class EventoController extends BaseController {
    // Função para busca dinâmica de eventos
    public function buscarEventosFiltrados($cidade = null, $dataInicial = null, $dataFinal = null, $tipoEvento = null) {
        $sql = "SELECT 
                    p.publicacaoid,
                    p.publicacaonome,
                    p.publicacaofoto01,
                    p.publicacaofoto02,
                    p.publicacaovalidadein,
                    p.publicacaovalidadeout,
                    p.publicacaovideo,
                    p.publicacaoauditada,
                    p.publicacaopaga,

                    e.eventodia,
                    e.eventohora,
                    e.eventoduracao,
                    e.eventoexpectativa,
                    e.eventoinformacao,
                    e.eventolinkingresso,
                
                    a.atracaotelefone,
                    a.atracaowebsite,
                    a.atracaotelefonewz,
                    a.atracaoinstagram,
                    a.atracaotictoc,
                
                    te.tipoeventonome,
                    fe.formatonome,
                
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
                JOIN evento e ON p.publicacaoid = e.publicacaoid
                JOIN atracao a ON p.publicacaoid = a.publicacaoid
                LEFT JOIN tipoevento te ON e.tipoeventoid = te.tipoeventoid
                LEFT JOIN formatoevento fe ON e.formatoid = fe.formatoid
                LEFT JOIN endereco en ON a.enderecoid = en.enderecoid
                LEFT JOIN cidade c ON en.cidadeid = c.cidadeid
                LEFT JOIN estado es ON c.estadoid = es.estadoid
                LEFT JOIN classificacaoetaria cl ON a.classificacaoid = cl.classificacaoid
                LEFT JOIN tipopublico tp ON a.tipopublicoid = tp.tipopublicoid
                LEFT JOIN segmento s ON a.segmentoid = s.segmentoid
                LEFT JOIN categoria cat ON a.categoriaid = cat.categoriaid
                WHERE 1=1";
        $params = [];

        if ($cidade) {
            $sql .= " AND c.cidadeid = :cidade";
            $params[':cidade'] = $cidade;
        }
        if ($dataInicial) {
            $sql .= " AND e.eventodia >= :data_inicial";
            $params[':data_inicial'] = $dataInicial;
        }
        if ($dataFinal) {
            $sql .= " AND e.eventodia <= :data_final";
            $params[':data_final'] = $dataFinal;
        }
        if ($tipoEvento) {
            $sql .= " AND e.tipoeventoid = :tipo_evento";
            $params[':tipo_evento'] = $tipoEvento;
        }
        $sql .= " ORDER BY p.publicacaoid DESC";

        $stmt = $this->conexao->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    private $conexao;
    private $publicacaoModel;
    private $atracaoModel;
    private $enderecoModel;
    private $eventoModel;

    public function __construct() {
        $this->conexao = Conexao::getInstance();
        $this->publicacaoModel = new PublicacaoModel($this->conexao);
        $this->atracaoModel = new AtracaoModel($this->conexao);
        $this->enderecoModel = new EnderecoModel($this->conexao);
        $this->eventoModel = new EventoModel($this->conexao);
    }

    public function processarRequisicao() {
        if (!$this->verificarSessao(true)) { // true = requer login
            ob_clean(); // Limpa qualquer saída anterior
            header("Location: " . $this->getLoginPath());
            exit();
        }
        $request_method = $_SERVER['REQUEST_METHOD'];

        if ($request_method === 'POST') {
            $acao = $_POST['acao'] ?? 'criar';

            if ($acao === 'atualizar') {
                try {
                    $dados = $_POST;
                    $foto1 = $this->processarUpload($_FILES['foto1'] ?? null, 'uploads');
                    $foto2 = $this->processarUpload($_FILES['foto2'] ?? null, 'uploads');
                    $video = $this->processarUpload($_FILES['video'] ?? null, 'uploads');

                    if ($foto1) $dados['foto1'] = $foto1;
                    if ($foto2) $dados['foto2'] = $foto2;
                    if ($video) $dados['video'] = $video;

                    $this->eventoModel->atualizarEvento($dados);

                    header("Location: ../views/form_evento.php?editar=true&publicacao_id=" . $_POST['publicacao_id'] . "&msg=success");
                    exit;
                } catch (Exception $e) {
                    error_log("Erro ao ATUALIZAR evento: " . $e->getMessage());
                    header("Location: ../views/form_evento.php?editar=true&publicacao_id=" . $_POST['publicacao_id'] . "&msg=error&erro=" . urlencode($e->getMessage()));
                    exit;
                }
            } elseif ($acao === 'excluir') {
                try {
                    $publicacaoId = $_POST['publicacao_id'];
                    $this->eventoModel->excluirPorPublicacaoId($publicacaoId);
                    
                    header("Location: ../views/listar_eventos.php?msg=delete_success");
                    exit;
                } catch (Exception $e) {
                    error_log("Erro ao EXCLUIR evento: " . $e->getMessage());
                    header("Location: ../views/listar_eventos.php?msg=delete_error&erro=" . urlencode($e->getMessage()));
                    exit;
                }
            } else {
                try {
                    $this->conexao->beginTransaction();


            error_log("Dados recebidos no evento_controller:");
            error_log("Cidade: " . ($_POST['cidade'] ?? 'não definido'));
            error_log("Estado: " . ($_POST['estado'] ?? 'não definido'));


            if (empty($_POST['cidade'])) {
                throw new Exception("Cidade não foi selecionada.");
            }

            $stmt = $this->conexao->prepare("SELECT cidadeid FROM cidade WHERE cidadeid = :cidade_id");
            $stmt->bindParam(':cidade_id', $_POST['cidade']);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                throw new Exception("Cidade selecionada não existe no banco de dados. ID: " . $_POST['cidade']);
            }


            $nomeFoto1 = $this->processarUpload($_FILES['foto1'] ?? null, 'uploads');
            $nomeFoto2 = $this->processarUpload($_FILES['foto2'] ?? null, 'uploads');
            $nomeVideo = $this->processarUpload($_FILES['video'] ?? null, 'uploads');


            error_log("Arquivos processados:");
            error_log("Foto1: " . ($nomeFoto1 ?: 'null'));
            error_log("Foto2: " . ($nomeFoto2 ?: 'null'));
            error_log("Video: " . ($nomeVideo ?: 'null'));


            $publicacaoId = $this->publicacaoModel->criar([
                'nome' => $_POST['nome'],
                'validade_inicial' => $_POST['validade-inicial'],
                'validade_final' => $_POST['validade-final'],
                'auditado' => isset($_POST['auditado']) ? 1 : 0,
                'foto1' => $nomeFoto1,
                'foto2' => $nomeFoto2,
                'video' => $nomeVideo,
                'paga' => isset($_POST['publicacao-pagamento']) ? 1 : 0,
                'user_id' => $_SESSION['userid']
            ]);


            $enderecoId = $this->enderecoModel->criar([
                'logradouro' => $_POST['logradouro'],
                'numero' => $_POST['numero'],
                'bairro' => $_POST['bairro'],
                'cidade' => $_POST['cidade'],
                'estado' => $_POST['estado'],
                'cep' => $_POST['cep'],
                'complemento' => $_POST['complemento'] ?? null
            ]);


            $this->atracaoModel->criar([
                'publicacao_id' => $publicacaoId,
                'endereco_id' => $enderecoId,
                'classificacao_id' => $_POST['classificacao'],
                'tipo_publico_id' => $_POST['tipo-publicacao'],
                'segmento_id' => $_POST['segmento'],
                'categoria_id' => $_POST['categoria'],
                'telefone' => $_POST['telefone'],
                'whatsapp' => isset($_POST['whatsapp']) ? 1 : 0,
                'website' => $_POST['site'] ?? null,
                'instagram' => $_POST['instagram'] ?? null,
                'tiktok' => $_POST['tiktok'] ?? null
            ]);


            $this->eventoModel->criar([
                'publicacao_id' => $publicacaoId,
                'tipo_evento_id' => $_POST['tipo-evento'],
                'formato_id' => $_POST['formato-evento'],
                'local_realizacao_id' => $_POST['realizacao-evento'] ?? null,
                'expectativa' => $_POST['expectativa'],
                'dia' => $_POST['dia-evento'],
                'hora' => $_POST['hora-evento'],
                'informacoes' => $_POST['infos-gerais'],
                'link_ingresso' => $_POST['link-compra'] ?? null,
                'duracao' => $_POST['duracao-evento'] ?? null
            ]);

                    $this->conexao->commit();

                    header("Location: ../views/form_evento.php?msg=success");
                    exit;
                } catch (Exception $e) {
                    if ($this->conexao->inTransaction()) {
                        $this->conexao->rollBack();
                    }
                    error_log("Erro ao CADASTRAR evento: " . $e->getMessage());
                    header("Location: ../views/form_evento.php?msg=error&erro=" . urlencode($e->getMessage()));
                    exit;
                }
            }
        }
    }

    private function processarUpload($arquivo, $pasta) {
        if (!isset($arquivo) || $arquivo['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'mp4', 'mov', 'avi', 'mkv'];
        $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));

        if (!in_array($extensao, $extensoesPermitidas)) {
            throw new Exception("Extensão de arquivo não permitida: " . $extensao);
        }

        $nomeUnico = uniqid() . '.' . $extensao;
        $caminhoDestino = "../$pasta/" . $nomeUnico;

        if (!move_uploaded_file($arquivo['tmp_name'], $caminhoDestino)) {
            throw new Exception("Falha ao mover o arquivo enviado.");
        }

        return $nomeUnico;
    }

    public function listarEventosCompletos() {
        $this->verificarSessao(false); // false = não requer login

        $stmt = $this->conexao->query("
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

            e.eventodia,
            e.eventohora,
            e.eventoduracao,
            e.eventoexpectativa,
            e.eventoinformacao,
            e.eventolinkingresso,
            
            a.atracaotelefone,
            a.atracaowebsite,
            a.atracaotelefonewz,
            a.atracaoinstagram,
            a.atracaotictoc,
            
            te.tipoeventonome,
            fe.formatonome,
            
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
        JOIN evento e ON p.publicacaoid = e.publicacaoid
        JOIN atracao a ON p.publicacaoid = a.publicacaoid
        LEFT JOIN tipoevento te ON e.tipoeventoid = te.tipoeventoid
        LEFT JOIN formatoevento fe ON e.formatoid = fe.formatoid
        LEFT JOIN endereco en ON a.enderecoid = en.enderecoid
        LEFT JOIN cidade c ON en.cidadeid = c.cidadeid
        LEFT JOIN estado es ON c.estadoid = es.estadoid
        LEFT JOIN classificacaoetaria cl ON a.classificacaoid = cl.classificacaoid
        LEFT JOIN tipopublico tp ON a.tipopublicoid = tp.tipopublicoid
        LEFT JOIN segmento s ON a.segmentoid = s.segmentoid
        LEFT JOIN categoria cat ON a.categoriaid = cat.categoriaid
    ORDER BY e.eventodia ASC, e.eventohora ASC
    ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarEventoPorId($publicacao_id) {
        if (!$this->verificarSessao(true)) { // true = requer login
            return null;
        }
        return $this->eventoModel->buscarEventoPorId($publicacao_id);
    }
}

// Executar processarRequisicao somente se este arquivo for chamado diretamente
if (basename($_SERVER['PHP_SELF']) === 'evento_controller.php') {
    $controller = new EventoController();
    $controller->processarRequisicao();
    ob_end_flush();
}


