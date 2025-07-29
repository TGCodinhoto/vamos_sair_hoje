<?php
require_once('../controllers/evento_controller.php');
require_once('../controllers/cidade_controller.php');
require_once('../controllers/estado_controller.php');
require_once('../controllers/classificacao_etaria_controller.php');
require_once('../controllers/tipopublico_controller.php');
require_once('../controllers/segmento_controller.php');
require_once('../controllers/categoria_controller.php');
require_once('../controllers/tipoevento_controller.php');
require_once('../controllers/formatoevento_controller.php');

// Obter dados para os dropdowns
$cidades = listarCidades();
$estados = listarEstados();
$classificacoes = listarClassificacaoEtaria();
$tiposPublico = listarTipoPublico();
$segmentos = listarSegmentos();
$categorias = listarCategorias();
$tiposEvento = listarTiposEvento();
$formatosEvento = listarFormatosEvento();

$mensagem = '';
$cor = 'green';

// Verificar se estamos editando um evento
$edicao = false;
$evento = null;

if (isset($_GET['editar']) && $_GET['editar'] == 'true' && isset($_GET['publicacao_id'])) {
  $publicacao_id = $_GET['publicacao_id'];
  $evento = buscarEventoPorId($publicacao_id); // Você precisará criar esta função no controller
  $edicao = true;
}

if (isset($_GET['msg'])) {
  if ($_GET['msg'] === 'success') {
    $mensagem = $edicao ? "Evento atualizado com sucesso!" : "Evento cadastrado com sucesso!";
  } elseif ($_GET['msg'] === 'error') {
    $cor = 'red';
    $mensagem = isset($_GET['erro']) ? $_GET['erro'] : "Erro ao processar a solicitação.";
  }
}
?>
<!DOCTYPE html>
<html class="scroll-smooth" lang="pt-BR">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <title>Cadastrar Evento</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  </link>

  <style>
    #botoes {
      font-family: 'Montserrat', sans-serif;
    }
  </style>
</head>

<body class="bg-gray-50 min-h-screen flex flex-col items-center justify-start p-4 sm:p-6 md:p-8">


  <!-- Botões Superiores Voltar e Home -->
  <div class="flex justify-center space-x-4 mb-6" id="botoes">
    <a href="navegacao_forms.php"
      class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition flex items-center space-x-2">
      <i class="fas fa-arrow-left"></i>
      <span>Voltar</span>
    </a>
    <a href="../index.php"
      class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition flex items-center space-x-2">
      <i class="fas fa-home"></i>
      <span>Home</span>
    </a>
    <a href="../views/listar_eventos.php"
      class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition flex items-center space-x-2">
      <i class="fas fa-list"></i>
      <span>Eventos Cadastrados</span>
    </a>
  </div>

  <section class="w-full max-w-4xl bg-white rounded-lg shadow-md p-4 sm:p-8 space-y-8">
    <h1 class="text-4xl md:text-5xl font-bold mb-8 text-center text-blue-600 font-montserrat">
      Cadastrar Evento
    </h1>

    <?php if (!empty($mensagem)): ?>
      <div class="mb-4 p-4 bg-<?= $cor ?>-100 border border-<?= $cor ?>-400 text-<?= $cor ?>-700 rounded">
        <?= htmlspecialchars($mensagem) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="../controllers/evento_controller.php" enctype="multipart/form-data" class="space-y-8" id="evento-form">
      <!-- <?php var_dump($evento); ?> -->

      <?php if ($edicao): ?>
        <input type="hidden" name="publicacao_id" value="<?= $evento['publicacaoid'] ?>">
        <input type="hidden" name="acao" value="atualizar">
      <?php endif; ?>

      <!-- ######################################### ######################################### -- ######################################### -->
      <!--                                                      PUBLICAÇÃO                                                                  -->
      <!-- ######################################### ######################################### -- ######################################### -->

      <fieldset class="border border-gray-300 rounded-lg p-4 sm:p-6 space-y-6">
        <legend class="text-lg sm:text-xl font-semibold text-gray-700 px-1 sm:px-2">
          Publicação
        </legend>
        <div class="flex flex-col w-full">
          <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="nome">Nome</label>
          <input autocomplete="off" id="nome" name="nome" placeholder="Nome do Evento" required type="text"
            value="<?= $edicao ? htmlspecialchars($evento['publicacaonome']) : '' ?>"
            class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" />

        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 sm:gap-6">
          <div class="flex flex-col w-full">
            <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="validade-inicial">Validade
              Inicial</label>
            <input id="validade-inicial" name="validade-inicial" required type="date"
              class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
              value="<?= $edicao ? htmlspecialchars($evento['publicacaovalidadein']) : '' ?>" />

          </div>
          <div class="flex flex-col w-full">
            <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="validade-final">Validade
              Final</label>
            <input id="validade-final" name="validade-final" required type="date"
              class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
              value="<?= $edicao ? htmlspecialchars($evento['publicacaovalidadeout']) : '' ?>" />
          </div>
        </div>
        <div class="flex items-center space-x-3">
          <input id="auditado" name="auditado" type="checkbox"
            class="h-6 w-6 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
            <?= $edicao && $evento['publicacaoauditada'] ? 'checked' : '' ?> />
          <label for="auditado"
            class="font-medium text-gray-700 text-base sm:text-lg cursor-pointer select-none">Auditado</label>
        </div>
        <div class="flex flex-col w-full space-y-2">
          <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="foto1">Foto 1</label>
          <div class="flex items-center space-x-4">
            <div
              class="w-20 h-20 border border-gray-300 rounded-md flex items-center justify-center overflow-hidden bg-gray-50 shrink-0">
              <img alt="Pré-visualização da Foto 1 do evento, espaço reservado para imagem carregada pelo usuário"
                height="80" width="80" id="preview-foto1" src="<?= $edicao ? htmlspecialchars('../uploads/' . $evento['publicacaofoto01']) : 'https://placehold.co/80x80' ?>"
                class="max-h-full max-w-full object-contain" />
            </div>
            <input id="foto1" name="foto1" type="file" accept="image/*"
              class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg file:mr-3 file:py-2 file:px-4 file:rounded file:border-0 file:text-base file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" />
          </div>
        </div>
        <div class="flex flex-col w-full space-y-2">
          <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="foto2">Foto 2</label>
          <div class="flex items-center space-x-4">
            <div
              class="w-20 h-20 border border-gray-300 rounded-md flex items-center justify-center overflow-hidden bg-gray-50 shrink-0">
              <img alt="Pré-visualização da Foto 2 do evento, espaço reservado para imagem carregada pelo usuário"
                height="80" width="80" id="preview-foto2" src="<?= $edicao ? htmlspecialchars('../uploads/' . $evento['publicacaofoto02']) : 'https://placehold.co/80x80' ?>"
                class="max-h-full max-w-full object-contain" />
            </div>
            <input id="foto2" name="foto2" type="file" accept="image/*"
              class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg file:mr-3 file:py-2 file:px-4 file:rounded file:border-0 file:text-base file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" />
          </div>
        </div>
        <div class="flex flex-col w-full">
          <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="video">Vídeo</label>
          <input id="video" name="video" type="file" accept="video/*"
            class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg file:mr-3 file:py-2 file:px-4 file:rounded file:border-0 file:text-base file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" />
        </div>
        <div class="flex flex-col w-full items-center space-y-3 sm:space-y-0 sm:flex-row sm:items-center sm:space-x-3">
          <input id="publicacao-pagamento" name="publicacao-pagamento" type="checkbox"
            class="h-6 w-6 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
            <?= $edicao && $evento['publicacaopaga'] ? 'checked' : '' ?> />
          <label for="publicacao-pagamento"
            class="font-medium text-gray-700 text-base sm:text-lg cursor-pointer select-none">Publicação
            Pagamento</label>
        </div>


      </fieldset>


      <!-- ######################################### ######################################### -- ######################################### -->
      <!--                                                      ATRAÇÃO                                                                  -->
      <!-- ######################################### ######################################### -- ######################################### -->
       
      <fieldset class="border border-gray-300 rounded-lg p-4 sm:p-6 space-y-6">
        <legend class="text-lg sm:text-xl font-semibold text-gray-700 px-1 sm:px-2">
          Atração
        </legend>
        <div class="flex flex-col w-full space-y-4">

          <!-- !!!!!  SUBSTITUIR DEPOIS PELO LOCAL DO EVENTO !!!!! -->
          <div class="flex flex-col w-full">
            <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg"
              for="realizacao-evento">Local de realização do Evento</label>
            <select id="realizacao-evento" name="realizacao-evento" required
              class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
              <option disabled selected value="">Selecione o local de realização do evento</option>
              <option value="publico">Clube P2</option>
              <option value="privado">Espaço Marina Park</option>
              <option value="parceria">Beach Park</option>
            </select>
          </div>


          <!-- ######################################### ######################################### -- ######################################### -->
          <!--                                                      ENDEREÇO                                                                  -->
          <!-- ######################################### ######################################### -- ######################################### -->
          <!-- Dados do Endereço -->
          <div>
            <?php if ($edicao && isset($evento['enderecoid'])): ?>
              <input type="hidden" name="endereco_id" value="<?= $evento['enderecoid'] ?>">
            <?php endif; ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div class="flex flex-col w-full">
                <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="numero">Número</label>
                <input autocomplete="off" id="numero" name="numero" placeholder="Número" required type="text"
                  value="<?= $edicao ? htmlspecialchars($evento['endereconumero'] ?? 'null') : '' ?>"
                  class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" />
              </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
              <div class="flex flex-col w-full">
                <!-- Rua -->
                <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="logradouro">Rua</label>
                <input autocomplete="off" id="logradouro" name="logradouro" placeholder="Rua" required type="text"
                  value="<?= $edicao ? htmlspecialchars($evento['enderecorua'] ?? 'Rua indisponível') : '' ?>"
                  class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" />
                <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="bairro">Bairro</label>
                <input autocomplete="off" id="bairro" name="bairro" placeholder="Bairro" required type="text"
                  value="<?= $edicao ? htmlspecialchars($evento['enderecobairro'] ?? 'Bairro indisponível') : '' ?>"
                  class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" />
              </div>
              <div class="flex flex-col w-full">
                <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="cidade">Cidade</label>
                <select id="cidade" name="cidade" required
                  class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
                  <option disabled selected value="">Selecione a cidade</option>
                  <?php foreach ($cidades as $cidade): ?>
                    <option
                      value="<?= $cidade['cidadeid'] ?>"
                      data-estadoid="<?= $cidade['estadoid'] ?>"
                      data-estadosigla="<?= htmlspecialchars($cidade['estadosigla']) ?>"
                      <?= ($edicao && isset($evento['cidadeid']) && $evento['cidadeid'] == $cidade['cidadeid']) ? 'selected' : '' ?>>
                      <?= htmlspecialchars($cidade['cidadenome']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="flex flex-col w-full">
                <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="estado">Estado</label>
                <input autocomplete="off" id="estado" name="estado_display" placeholder="Selecione uma cidade" required type="text"
                  value="<?= $edicao ? htmlspecialchars($evento['estadosigla']) : '' ?>"
                  class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full bg-gray-100" readonly />
                <input type="hidden" id="estado_id_hidden" name="estado" value="<?= $edicao ? $evento['estadoid'] : '' ?>">
              </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
              <div class="flex flex-col w-full">
                <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg"
                  for="complemento">Complemento</label>
                <input autocomplete="off" id="complemento" name="complemento" placeholder="Complemento"
                  type="text"
                  value="<?= $edicao ? htmlspecialchars($evento['enderecocomplemento'] ?? 'Complemento indisponível') : '' ?>"
                  class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" />
              </div>
              <div class="flex flex-col w-full">
                <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="cep">CEP</label>
                <input autocomplete="off" id="cep" name="cep" placeholder="CEP" required type="text"
                  value="<?= $edicao ? htmlspecialchars($evento['enderecocep'] ?? 'CEP indisponível') : '' ?>"
                  class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" />
              </div>
            </div>
          </div>

          <!-- Dropdowns com dados do banco -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="flex flex-col w-full">
              <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg"
                for="classificacao">Classificação</label>
              <select id="classificacao" name="classificacao" required
                class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
                <option disabled selected value="">Selecione a classificação</option>
                <?php foreach ($classificacoes as $classificacao): ?>
                  <option value="<?= $classificacao['classificacaoid'] ?>"
                    <?= $edicao && $evento['classificacaoid'] == $classificacao['classificacaoid'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($classificacao['classificacaonome']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="flex flex-col w-full">
              <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="tipo-publicacao">Tipo de Público</label>
              <select id="tipo-publicacao" name="tipo-publicacao" required
                class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
                <option disabled selected value="">Selecione o tipo</option>
                <?php foreach ($tiposPublico as $tipo): ?>
                  <option value="<?= $tipo['tipopublicoid'] ?>"
                    <?= $edicao && $evento['tipopublicoid'] == $tipo['tipopublicoid'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($tipo['tipopubliconome']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="flex flex-col w-full">
              <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="segmento">Segmento</label>
              <select id="segmento" name="segmento" required
                class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
                <option disabled selected value="">Selecione o segmento</option>
                <?php foreach ($segmentos as $segmento): ?>
                  <option value="<?= $segmento['segmentoid'] ?>"
                    <?= $edicao && $evento['segmentoid'] == $segmento['segmentoid'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($segmento['segmentonome']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="flex flex-col w-full">
              <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg"
                for="categoria">Categoria</label>
              <select id="categoria" name="categoria" required
                class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
                <option disabled selected value="">Selecione a categoria</option>
                <?php foreach ($categorias as $categoria): ?>
                  <option value="<?= $categoria['categoriaid'] ?>"
                    <?= $edicao && $evento['categoriaid'] == $categoria['categoriaid'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($categoria['categorianome']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="flex flex-col w-full space-y-2">
            <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="telefone">Telefone
              Celular</label>
            <div class="flex items-center space-x-4">
              <input autocomplete="off" id="telefone" name="telefone" placeholder="(XX) XXXXX-XXXX" type="tel"
                value="<?= $edicao ? htmlspecialchars($evento['atracaotelefone']) : '' ?>"
                class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" />
              <div class="flex items-center space-x-2">

                <input id="whatsapp" name="whatsapp" type="checkbox"
                  class="h-6 w-6 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                  <?= $edicao && $evento['atracaotelefonewz'] ? 'checked' : '' ?> />
                <label for="whatsapp"
                  class="font-medium text-gray-700 text-base sm:text-lg cursor-pointer select-none">WhatsApp</label>
              </div>
            </div>
          </div>
          <div class="flex flex-col w-full">
            <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="site">Site</label>
            <input autocomplete="off" id="site" name="site" placeholder="https://exemplo.com" type="url"
              value="<?= $edicao ? htmlspecialchars($evento['atracaowebsite']) : '' ?>"
              class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" />
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            <div class="flex flex-col w-full">
              <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg"
                for="instagram">Instagram</label>
              <input autocomplete="off" id="instagram" name="instagram" placeholder="@usuario" type="text"
                value="<?= $edicao ? htmlspecialchars($evento['atracaoinstagram']) : '' ?>"
                class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" />
            </div>
            <div class="flex flex-col w-full">
              <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="tiktok">Tiktok</label>
              <input autocomplete="off" id="tiktok" name="tiktok" placeholder="@usuario" type="text"
                value="<?= $edicao ? htmlspecialchars($evento['atracaotictoc']) : '' ?>"
                class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" />
            </div>
          </div>
        </div>
      </fieldset>


      <!-- ######################################### ######################################### -- ######################################### -->
      <!--                                                      EVENTOS                                                                  -->
      <!-- ######################################### ######################################### -- ######################################### -->

      <fieldset class="border border-gray-300 rounded-lg p-4 sm:p-6 space-y-6">
        <legend class="text-lg sm:text-xl font-semibold text-gray-700 px-1 sm:px-2">Eventos</legend>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <!-- Tipo de Evento -->
          <div class="flex flex-col w-full">
            <label for="tipo-evento" class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg">Tipo de
              Evento</label>
            <select id="tipo-evento" name="tipo-evento" required
              class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
              <option disabled selected value="">Selecione o tipo de evento</option>
              <?php foreach ($tiposEvento as $tipo): ?>
                <option value="<?= $tipo['tipoeventoid'] ?>"
                  <?= $edicao && $evento['tipoeventoid'] == $tipo['tipoeventoid'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($tipo['tipoeventonome']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Formato Evento -->
          <div class="flex flex-col w-full">
            <label for="formato-evento" class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg">Formato
              Evento</label>
            <select id="formato-evento" name="formato-evento" required
              class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
              <option disabled selected value="">Selecione o formato</option>
              <?php foreach ($formatosEvento as $formato): ?>
                <option value="<?= $formato['formatoid'] ?>"
                  <?= $edicao && $evento['formatoid'] == $formato['formatoid'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($formato['formatonome']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <!-- Restante dos campos (mantido igual) -->
        <div class="flex flex-col w-full">


          <label for="expectativa"
            class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg">Expectativa</label>
          <textarea id="expectativa" name="expectativa" rows="3" placeholder="Descreva a expectativa do evento"
            class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full resize-y"><?= $edicao ? htmlspecialchars($evento['eventoexpectativa']) : '' ?></textarea>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
          <div class="flex flex-col w-full">
            <label for="dia-evento" class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg">Dia do
              Evento</label>
            <input id="dia-evento" name="dia-evento" type="date" required
              class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
              value="<?= $edicao ? htmlspecialchars($evento['eventodia']) : '' ?>" />
          </div>
          <div class="flex flex-col w-full">
            <label for="hora-evento" class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg">Hora</label>
            <input id="hora-evento" name="hora-evento" type="time" required
              class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
              value="<?= $edicao ? htmlspecialchars($evento['eventohora']) : '' ?>" />
          </div>
          <div class="flex flex-col w-full">
            <label for="duracao-evento" class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg">Duração
              Evento</label>
            <input id="duracao-evento" name="duracao-evento" type="text" placeholder="Duração do evento"
              class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
              value="<?= $edicao ? htmlspecialchars($evento['eventoduracao']) : '' ?>" />
          </div>
        </div>
        <div class="flex flex-col w-full">
          <label for="infos-gerais" class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg">Informações
            Gerais</label>
          <textarea id="infos-gerais" name="infos-gerais" rows="4" placeholder="Informações gerais sobre o evento"
            class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full resize-y"><?= $edicao ? htmlspecialchars($evento['eventoinformacao']) : '' ?></textarea>
        </div>
        <div class="flex flex-col w-full">
          <label for="link-compra" class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg">Link Compra de
            Ingresso</label>
          <input id="link-compra" name="link-compra" type="url" placeholder="https://exemplo.com/ingresso"
            class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
            value="<?= $edicao ? htmlspecialchars($evento['eventolinkingresso']) : '' ?>" />
        </div>
      </fieldset>

      <div class="flex justify-center">
        <!-- A LINHA ABAIXO FOI REMOVIDA -->
        <!-- <input type="hidden" name="acao" value="<?= $edicao ? 'editar' : 'cadastrar' ?>"> -->

        <button type="submit"
          class="bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md px-10 py-2 transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
          <?= $edicao ? 'Editar' : 'Cadastrar' ?>
        </button>
      </div>


    </form>
  </section>
  <script>
    // Preencher estado automaticamente ao selecionar cidade
    document.getElementById('cidade').addEventListener('change', function() {
      const estadoInput = document.getElementById('estado');
      const selectedOption = this.options[this.selectedIndex];
      if (selectedOption.dataset.estado) {
        estadoInput.value = selectedOption.dataset.estado;
      }
    });

    // Manter a pré-visualização de imagens (seu código existente)
    const foto1Input = document.getElementById('foto1');
    const previewFoto1 = document.getElementById('preview-foto1');
    const foto2Input = document.getElementById('foto2');
    const previewFoto2 = document.getElementById('preview-foto2');

    foto1Input.addEventListener('change', () => {
      const file = foto1Input.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = e => {
          previewFoto1.src = e.target.result;
          previewFoto1.alt = `Imagem carregada para Foto 1: ${file.name}`;
        };
        reader.readAsDataURL(file);
      } else {
        previewFoto1.src = "https://placehold.co/80x80/png?text=Pré-visualização+Foto+1";
        previewFoto1.alt = "Pré-visualização da Foto 1 do evento, espaço reservado para imagem carregada pelo usuário";
      }
    });

    foto2Input.addEventListener('change', () => {
      const file = foto2Input.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = e => {
          previewFoto2.src = e.target.result;
          previewFoto2.alt = `Imagem carregada para Foto 2: ${file.name}`;
        };
        reader.readAsDataURL(file);
      } else {
        previewFoto2.src = "https://placehold.co/80x80/png?text=Pré-visualização+Foto+2";
        previewFoto2.alt = "Pré-visualização da Foto 2 do evento, espaço reservado para imagem carregada pelo usuário";
      }
    });

    document.addEventListener('DOMContentLoaded', function() {
      const cidadeSelect = document.getElementById('cidade');
      const estadoDisplayInput = document.getElementById('estado'); // O campo que o usuário vê
      const estadoHiddenInput = document.getElementById('estado_id_hidden'); // O campo que envia o ID

      // Função para atualizar o estado com base na cidade selecionada
      function atualizarEstado() {
        // Pega o <option> que está selecionado no momento
        const selectedOption = cidadeSelect.options[cidadeSelect.selectedIndex];

        if (selectedOption && selectedOption.value) {
          // Lê os atributos data-* do option selecionado
          const estadoId = selectedOption.getAttribute('data-estadoid');
          const estadoSigla = selectedOption.getAttribute('data-estadosigla');

          // Atualiza os campos de estado (o visível e o oculto)
          estadoDisplayInput.value = estadoSigla;
          estadoHiddenInput.value = estadoId;
        } else {
          // Limpa os campos se nenhuma cidade for selecionada
          estadoDisplayInput.value = '';
          estadoHiddenInput.value = '';
        }
      }

      // Adiciona o "escutador" de eventos. Toda vez que o usuário mudar a cidade, a função será chamada.
      cidadeSelect.addEventListener('change', atualizarEstado);

      // Chama a função uma vez quando a página carrega para preencher o estado
      // caso uma cidade já esteja selecionada (no modo de edição).
      atualizarEstado();
    });
  </script>


</body>

</html>