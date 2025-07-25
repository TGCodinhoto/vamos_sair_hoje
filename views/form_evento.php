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
  </div>

  <section class="w-full max-w-4xl bg-white rounded-lg shadow-md p-4 sm:p-8 space-y-8">
    <h1 class="text-4xl md:text-5xl font-bold mb-8 text-center text-blue-600 font-montserrat">
      Cadastrar Evento
    </h1>

    <form aria-label="Formulário de Eventos" class="space-y-8" id="evento-form" novalidate>

      <!-- Publicação -->
      <fieldset class="border border-gray-300 rounded-lg p-4 sm:p-6 space-y-6">
        <legend class="text-lg sm:text-xl font-semibold text-gray-700 px-1 sm:px-2">
          Publicação
        </legend>
        <div class="flex flex-col w-full">
          <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="nome">Nome</label>
          <input autocomplete="off" id="nome" name="nome" placeholder="Nome do Evento" required type="text"
            class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" />
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 sm:gap-6">
          <div class="flex flex-col w-full">
            <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="validade-inicial">Validade
              Inicial</label>
            <input id="validade-inicial" name="validade-inicial" required type="date"
              class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" />
          </div>
          <div class="flex flex-col w-full">
            <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="validade-final">Validade
              Final</label>
            <input id="validade-final" name="validade-final" required type="date"
              class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" />
          </div>
        </div>
        <div class="flex items-center space-x-3">
          <input id="auditado" name="auditado" type="checkbox"
            class="h-6 w-6 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" />
          <label for="auditado"
            class="font-medium text-gray-700 text-base sm:text-lg cursor-pointer select-none">Auditado</label>
        </div>
        <div class="flex flex-col w-full space-y-2">
          <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="foto1">Foto 1</label>
          <div class="flex items-center space-x-4">
            <div
              class="w-20 h-20 border border-gray-300 rounded-md flex items-center justify-center overflow-hidden bg-gray-50 shrink-0">
              <img alt="Pré-visualização da Foto 1 do evento, espaço reservado para imagem carregada pelo usuário"
                height="80" width="80" id="preview-foto1" src="https://placehold.co/80x80"
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
                height="80" width="80" id="preview-foto2" src="https://placehold.co/80x80"
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
            class="h-6 w-6 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" />
          <label for="publicacao-pagamento"
            class="font-medium text-gray-700 text-base sm:text-lg cursor-pointer select-none">Publicação
            Pagamento</label>
        </div>
      </fieldset>

      <!-- Atração -->
      <fieldset class="border border-gray-300 rounded-lg p-4 sm:p-6 space-y-6">
        <legend class="text-lg sm:text-xl font-semibold text-gray-700 px-1 sm:px-2">
          Atração
        </legend>
        <div class="flex flex-col w-full space-y-4">
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
          <div>
            <!-- <label class="mb-8 font-medium text-gray-700 text-base sm:text-lg" for="dados-endereco">Dados do
              Endereço</label>
            <hr class="mb-10" /> -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div class="flex flex-col w-full">
                <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg"
                  for="logradouro">Logradouro</label>
                <input autocomplete="off" id="logradouro" name="logradouro" placeholder="Rua, Avenida, etc." required
                  type="text"
                  class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" />
              </div>
              <div class="flex flex-col w-full">
                <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="numero">Número</label>
                <input autocomplete="off" id="numero" name="numero" placeholder="Número" required type="text"
                  class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" />
              </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
              <div class="flex flex-col w-full">
                <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="bairro">Bairro</label>
                <input autocomplete="off" id="bairro" name="bairro" placeholder="Bairro" required type="text"
                  class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" />
              </div>
              <div class="flex flex-col w-full">
                <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="cidade">Cidade</label>
                <input autocomplete="off" id="cidade" name="cidade" placeholder="Cidade" required type="text"
                  class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" />
              </div>
              <div class="flex flex-col w-full">
                <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="estado">Estado</label>
                <input autocomplete="off" id="estado" name="estado" placeholder="Estado" required type="text"
                  class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" />
              </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
              <div class="flex flex-col w-full">
                <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg"
                  for="complemento">Complemento</label>
                <input autocomplete="off" id="complemento" name="complemento" placeholder="Apartamento, bloco, etc."
                  type="text"
                  class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" />
              </div>
              <div class="flex flex-col w-full">
                <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="cep">CEP</label>
                <input autocomplete="off" id="cep" name="cep" placeholder="CEP" required type="text"
                  class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" />
              </div>
            </div>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="flex flex-col w-full">
              <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg"
                for="classificacao">Classificação</label>
              <select id="classificacao" name="classificacao" required
                class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
                <option disabled selected value="">Selecione a classificação</option>
                <option value="infantil">Infantil</option>
                <option value="adulto">Adulto</option>
                <option value="geral">Geral</option>
              </select>
            </div>
            <div class="flex flex-col w-full">
              <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="tipo-publicacao">Tipo de
                Publicação</label>
              <select id="tipo-publicacao" name="tipo-publicacao" required
                class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
                <option disabled selected value="">Selecione o tipo</option>
                <option value="online">Online</option>
                <option value="presencial">Presencial</option>
                <option value="hibrido">Híbrido</option>
              </select>
            </div>
            <div class="flex flex-col w-full">
              <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="segmento">Segmento</label>
              <select id="segmento" name="segmento" required
                class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
                <option disabled selected value="">Selecione o segmento</option>
                <option value="musica">Música</option>
                <option value="teatro">Teatro</option>
                <option value="dança">Dança</option>
                <option value="gastronomia">Gastronomia</option>
                <option value="outros">Outros</option>
              </select>
            </div>
            <div class="flex flex-col w-full">
              <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg"
                for="categoria">Categoria</label>
              <select id="categoria" name="categoria" required
                class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
                <option disabled selected value="">Selecione a categoria</option>
                <option value="musica">Música</option>
                <option value="teatro">Teatro</option>
                <option value="dança">Dança</option>
                <option value="gastronomia">Gastronomia</option>
                <option value="outros">Outros</option>
              </select>
            </div>
          </div>
          <div class="flex flex-col w-full space-y-2">
            <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="telefone">Telefone
              Celular</label>
            <div class="flex items-center space-x-4">
              <input autocomplete="off" id="telefone" name="telefone" placeholder="(XX) XXXXX-XXXX" type="tel"
                class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" />
              <div class="flex items-center space-x-2">
                <input id="whatsapp" name="whatsapp" type="checkbox"
                  class="h-6 w-6 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" />
                <label for="whatsapp"
                  class="font-medium text-gray-700 text-base sm:text-lg cursor-pointer select-none">WhatsApp</label>
              </div>
            </div>
          </div>
          <div class="flex flex-col w-full">
            <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="site">Site</label>
            <input autocomplete="off" id="site" name="site" placeholder="https://exemplo.com" type="url"
              class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" />
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            <div class="flex flex-col w-full">
              <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg"
                for="instagram">Instagram</label>
              <input autocomplete="off" id="instagram" name="instagram" placeholder="@usuario" type="text"
                class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" />
            </div>
            <div class="flex flex-col w-full">
              <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="tiktok">Tiktok</label>
              <input autocomplete="off" id="tiktok" name="tiktok" placeholder="@usuario" type="text"
                class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" />
            </div>
          </div>
        </div>
      </fieldset>

      <!-- Eventos -->
      <fieldset class="border border-gray-300 rounded-lg p-4 sm:p-6 space-y-6">
        <legend class="text-lg sm:text-xl font-semibold text-gray-700 px-1 sm:px-2">Eventos</legend>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <div class="flex flex-col w-full">
            <label for="tipo-evento" class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg">Tipo de
              Evento</label>
            <select id="tipo-evento" name="tipo-evento" required
              class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
              <option disabled selected value="">Selecione o tipo de evento</option>
              <option value="show">Show</option>
              <option value="festival">Festival</option>
              <option value="workshop">Workshop</option>
              <option value="palestra">Palestra</option>
              <option value="outros">Outros</option>
            </select>
          </div>
          <div class="flex flex-col w-full">
            <label for="formato-evento" class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg">Formato
              Evento</label>
            <select id="formato-evento" name="formato-evento" required
              class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
              <option disabled selected value="">Selecione o formato</option>
              <option value="online">Online</option>
              <option value="presencial">Presencial</option>
              <option value="hibrido">Híbrido</option>
            </select>
          </div>
        </div>
        <div class="flex flex-col w-full">
          <label for="expectativa"
            class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg">Expectativa</label>
          <textarea id="expectativa" name="expectativa" rows="3" placeholder="Descreva a expectativa do evento"
            class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full resize-y"></textarea>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
          <div class="flex flex-col w-full">
            <label for="dia-evento" class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg">Dia do
              Evento</label>
            <input id="dia-evento" name="dia-evento" type="date" required
              class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" />
          </div>
          <div class="flex flex-col w-full">
            <label for="hora-evento" class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg">Hora</label>
            <input id="hora-evento" name="hora-evento" type="time" required
              class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" />
          </div>
          <div class="flex flex-col w-full">
            <label for="duracao-evento" class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg">Duração
              Evento</label>
            <input id="duracao-evento" name="duracao-evento" type="text" placeholder="Ex: 2 horas"
              class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" />
          </div>
        </div>
        <div class="flex flex-col w-full">
          <label for="infos-gerais" class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg">Informações
            Gerais</label>
          <textarea id="infos-gerais" name="infos-gerais" rows="4" placeholder="Informações gerais sobre o evento"
            class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full resize-y"></textarea>
        </div>
        <div class="flex flex-col w-full">
          <label for="link-compra" class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg">Link Compra de
            Ingresso</label>
          <input id="link-compra" name="link-compra" type="url" placeholder="https://"
            class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" />
        </div>
      </fieldset>

      <div class="flex justify-center">
        <button type="submit"
          class="bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md px-10 py-2 transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
          Enviar
        </button>
      </div>
    </form>
  </section>
  <script>
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
  </script>
</body>

</html>