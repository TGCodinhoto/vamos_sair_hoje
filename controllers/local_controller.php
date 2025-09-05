<?php
require_once '../conexao.php';
require_once 'base_controller.php';
require_once '../models/publicacao_model.php';
require_once '../models/atracao_model.php';
require_once '../models/endereco_model.php';
require_once '../models/local_model.php';

class LocalController extends BaseController {
    private $conexao;
    private $publicacaoModel;
    private $atracaoModel;
    private $enderecoModel;
    private $localModel;

    public function __construct() {
        $this->conexao = Conexao::getInstance();
        $this->publicacaoModel = new PublicacaoModel($this->conexao);
        $this->atracaoModel = new AtracaoModel($this->conexao);
        $this->enderecoModel = new EnderecoModel($this->conexao);
        $this->localModel = new LocalModel($this->conexao);
    }

    public function verificarPermissaoEstabelecimento() {
        $this->verificarSessao();
        
        // Verifica se o usuário é do tipo 1 (admin) ou 2 (estabelecimento)
        if ($_SESSION['usertipo'] != 1 && $_SESSION['usertipo'] != 2) {
            header("Location: ../views/login.php");
            exit();
        }
    }

    public function processarRequisicao() {
        $this->verificarSessao();
        
        // Permite acesso apenas para admin (1) ou estabelecimento (2)
        if ($_SESSION['usertipo'] != 1 && $_SESSION['usertipo'] != 2) {
            header("Location: ../views/login.php");
            exit();
        }

        $request_method = $_SERVER['REQUEST_METHOD'];
        if ($request_method === 'POST') {
            $acao = $_POST['acao'] ?? 'criar';

            switch ($acao) {
                case 'atualizar':
                    $this->atualizarLocal();
                    break;
                case 'excluir':
                    $this->excluirLocal();
                    break;
                case 'criar':
                default:
                    $this->criarLocal();
                    break;
            }
        }
    }

    private function atualizarLocal() {
        try {
            $dados = $_POST;
            
            $dados['foto01'] = $this->processarUpload($_FILES['foto1'] ?? null, 'uploads');
            $dados['foto02'] = $this->processarUpload($_FILES['foto2'] ?? null, 'uploads');
            $dados['foto03'] = $this->processarUpload($_FILES['foto3'] ?? null, 'uploads');
            $dados['foto04'] = $this->processarUpload($_FILES['foto4'] ?? null, 'uploads');
            $dados['foto05'] = $this->processarUpload($_FILES['foto5'] ?? null, 'uploads');
            
            $this->localModel->atualizarLocal($dados);
            
            header("Location: ../views/form_local.php?editar=true&publicacao_id=" . $_POST['publicacao_id'] . "&msg=success");
            exit;
        } catch (Exception $e) {
            error_log("Erro ao ATUALIZAR local: " . $e->getMessage());
            header("Location: ../views/form_local.php?editar=true&publicacao_id=" . $_POST['publicacao_id'] . "&msg=error&erro=" . urlencode($e->getMessage()));
            exit;
        }
    }

    private function excluirLocal() {
        try {
            $publicacaoId = $_POST['publicacao_id'];
            $this->localModel->excluirPorPublicacaoId($publicacaoId);
            
            header("Location: ../views/listar_eventos.php?msg=delete_success");
            exit;
        } catch (Exception $e) {
            error_log("Erro ao EXCLUIR local: " . $e->getMessage());
            header("Location: ../views/listar_eventos.php?msg=delete_error&erro=" . urlencode($e->getMessage()));
            exit;
        }
    }

    private function criarLocal() {
        try {
            $this->conexao->beginTransaction();

            error_log("Dados recebidos no local_controller:");
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
            $nomeFoto3 = $this->processarUpload($_FILES['foto3'] ?? null, 'uploads');
            $nomeFoto4 = $this->processarUpload($_FILES['foto4'] ?? null, 'uploads');
            $nomeFoto5 = $this->processarUpload($_FILES['foto5'] ?? null, 'uploads');

            error_log("Fotos processadas:");
            error_log("Foto1: " . ($nomeFoto1 ?: 'null'));
            error_log("Foto2: " . ($nomeFoto2 ?: 'null'));
            error_log("Foto3: " . ($nomeFoto3 ?: 'null'));
            error_log("Foto4: " . ($nomeFoto4 ?: 'null'));
            error_log("Foto5: " . ($nomeFoto5 ?: 'null'));

            $publicacaoId = $this->publicacaoModel->criar([
                'nome' => $_POST['nome'],
                'validade_inicial' => $_POST['validade-inicial'],
                'validade_final' => $_POST['validade-final'],
                'auditado' => isset($_POST['auditado']) ? 1 : 0,
                'foto1' => $nomeFoto1,
                'foto2' => $nomeFoto2,
                'video' => null,
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

            $this->localModel->criar([
                'publicacao_id' => $publicacaoId,
                'tipo_local_id' => $_POST['tipo-local'],
                'foto03' => $nomeFoto3,
                'foto04' => $nomeFoto4,
                'foto05' => $nomeFoto5
            ]);

            $this->conexao->commit();
            header("Location: ../views/form_local.php?msg=success");
            exit;
        } catch (Exception $e) {
            if ($this->conexao->inTransaction()) {
                $this->conexao->rollBack();
            }
            error_log("Erro ao CADASTRAR local: " . $e->getMessage());
            header("Location: ../views/form_local.php?msg=error&erro=" . urlencode($e->getMessage()));
            exit;
        }
    }

    private function processarUpload($arquivo, $pasta) {
        if (!isset($arquivo) || $arquivo['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));

        if (!in_array($extensao, $extensoesPermitidas)) {
            throw new Exception("Extensão de arquivo não permitida.");
        }

        $nomeUnico = uniqid() . '.' . $extensao;
        $caminhoDestino = "../$pasta/" . $nomeUnico;

        if (!move_uploaded_file($arquivo['tmp_name'], $caminhoDestino)) {
            throw new Exception("Falha ao mover o arquivo enviado.");
        }

        return $nomeUnico;
    }

    public function listarLocaisCompletos($userId = null) {
        if ($_SESSION['usertipo'] != 1) {
            $this->verificarPermissaoEstabelecimento();
        }
        return $this->localModel->listarLocaisCompletos($userId);
    }

    public function buscarLocalPorId($publicacao_id) {
        $this->verificarPermissaoEstabelecimento();
        return $this->localModel->buscarLocalPorId($publicacao_id);
    }
}

// Instancia o controller e processa a requisição
$controller = new LocalController();
$controller->processarRequisicao();