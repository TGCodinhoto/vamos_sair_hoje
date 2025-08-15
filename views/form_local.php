<html lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>
        Cadastrar Local
    </title>
    <script src="https://cdn.tailwindcss.com">
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&amp;display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <style>
        #botoes {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen flex flex-col items-center justify-start p-4 sm:p-6 md:p-8">
    <div class="flex justify-center space-x-4 mb-6" id="botoes">
        <a class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition flex items-center space-x-2"
            href="navegacao_forms.php">
            <i class="fas fa-arrow-left">
            </i>
            <span class="hidden sm:block">
                Voltar
            </span>
        </a>
        <a class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition flex items-center space-x-2"
            href="../index.php">
            <i class="fas fa-home">
            </i>
            <span class="hidden sm:block">
                Home
            </span>
        </a>
        <a class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition flex items-center space-x-2"
            href="../views/listar_eventos.php">
            <i class="fas fa-list">
            </i>
            <span class="hidden sm:block">
                Locais Cadastrados
            </span>
        </a>
    </div>
    <section class="w-full max-w-4xl bg-white rounded-lg shadow-md p-4 sm:p-8 space-y-8">
        <h1
            class="text-3xl sm:text-4xl md:text-5xl font-bold mb-8 text-center text-blue-600 font-montserrat leading-tight">
            Cadastrar Local
        </h1>
        <form action="#" class="space-y-8" enctype="multipart/form-data" id="evento-form" method="POST">
            <fieldset class="border border-gray-300 rounded-lg p-4 sm:p-6 space-y-6">
                <div class="flex flex-col w-full">
                    <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="nome">
                        Nome
                    </label>
                    <input
                        class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                        id="nome" name="nome" placeholder="Nome do Local" required="" type="text" />
                </div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 sm:gap-6">
                    <div class="flex flex-col w-full">
                        <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg"
                            for="validade-inicial">
                            Validade Inicial
                        </label>
                        <input
                            class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                            id="validade-inicial" name="validade-inicial" required="" type="date" />
                    </div>
                    <div class="flex flex-col w-full">
                        <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="validade-final">
                            Validade Final
                        </label>
                        <input
                            class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                            id="validade-final" name="validade-final" required="" type="date" />
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <input class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" id="auditado"
                        name="auditado" type="checkbox" />
                    <label class="font-medium text-gray-700 text-base sm:text-lg cursor-pointer" for="auditado">
                        Auditado
                    </label>
                </div>
                <div class="flex flex-col w-full space-y-2">
                    <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="foto1">
                        Foto 1
                    </label>
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-20 h-20 border border-gray-300 rounded-md flex items-center justify-center overflow-hidden bg-gray-50 shrink-0">
                            <img alt="Pré-visualização da Foto 1 do local, quadrado, 80 por 80 pixels, fundo cinza claro"
                                class="max-h-full max-w-full object-contain" id="preview-foto1"
                                src="https://placehold.co/80x80?text=Foto+1" />
                        </div>
                        <input accept="image/*"
                            class="border border-gray-300 rounded-md px-2 py-1 text-sm sm:text-base file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 w-full sm:w-auto"
                            id="foto1" name="foto1" required="" type="file" />
                    </div>
                </div>
                <div class="flex flex-col w-full space-y-2">
                    <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="foto2">
                        Foto 2
                    </label>
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-20 h-20 border border-gray-300 rounded-md flex items-center justify-center overflow-hidden bg-gray-50 shrink-0">
                            <img alt="Pré-visualização da Foto 2 do local, quadrado, 80 por 80 pixels, fundo cinza claro"
                                class="max-h-full max-w-full object-contain" id="preview-foto2"
                                src="https://placehold.co/80x80?text=Foto+2" />
                        </div>
                        <input accept="image/*"
                            class="border border-gray-300 rounded-md px-2 py-1 text-sm sm:text-base file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 w-full sm:w-auto"
                            id="foto2" name="foto2" type="file" />
                    </div>
                </div>
                <div class="flex flex-col w-full space-y-2">
                    <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="foto3">
                        Foto 3
                    </label>
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-20 h-20 border border-gray-300 rounded-md flex items-center justify-center overflow-hidden bg-gray-50 shrink-0">
                            <img alt="Pré-visualização da Foto 3 do local, quadrado, 80 por 80 pixels, fundo cinza claro"
                                class="max-h-full max-w-full object-contain" id="preview-foto3"
                                src="https://placehold.co/80x80?text=Foto+3" />
                        </div>
                        <input accept="image/*"
                            class="border border-gray-300 rounded-md px-2 py-1 text-sm sm:text-base file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 w-full sm:w-auto"
                            id="foto3" name="foto3" type="file" />
                    </div>
                </div>
                <div class="flex flex-col w-full space-y-2">
                    <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="foto4">
                        Foto 4
                    </label>
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-20 h-20 border border-gray-300 rounded-md flex items-center justify-center overflow-hidden bg-gray-50 shrink-0">
                            <img alt="Pré-visualização da Foto 4 do local, quadrado, 80 por 80 pixels, fundo cinza claro"
                                class="max-h-full max-w-full object-contain" id="preview-foto4"
                                src="https://placehold.co/80x80?text=Foto+4" />
                        </div>
                        <input accept="image/*"
                            class="border border-gray-300 rounded-md px-2 py-1 text-sm sm:text-base file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 w-full sm:w-auto"
                            id="foto4" name="foto4" type="file" />
                    </div>
                </div>
                <div class="flex flex-col w-full space-y-2">
                    <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="foto5">
                        Foto 5
                    </label>
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-20 h-20 border border-gray-300 rounded-md flex items-center justify-center overflow-hidden bg-gray-50 shrink-0">
                            <img alt="Pré-visualização da Foto 5 do local, quadrado, 80 por 80 pixels, fundo cinza claro"
                                class="max-h-full max-w-full object-contain" id="preview-foto5"
                                src="https://placehold.co/80x80?text=Foto+5" />
                        </div>
                        <input accept="image/*"
                            class="border border-gray-300 rounded-md px-2 py-1 text-sm sm:text-base file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 w-full sm:w-auto"
                            id="foto5" name="foto5" type="file" />
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <input class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        id="publicacao-pagamento" name="publicacao-pagamento" type="checkbox" />
                    <label class="font-medium text-gray-700 text-base sm:text-lg cursor-pointer"
                        for="publicacao-pagamento">
                        Publicação Pagamento
                    </label>
                </div>
                <div>
                    <input name="endereco_id" type="hidden" value="" />
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="flex flex-col w-full">
                            <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="logradouro">
                                Rua
                            </label>
                            <input autocomplete="off"
                                class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                                id="logradouro" name="logradouro" placeholder="Rua" required="" type="text" value="" />
                        </div>
                        <div class="flex flex-col w-full">
                            <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="bairro">
                                Bairro
                            </label>
                            <input autocomplete="off"
                                class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                                id="bairro" name="bairro" placeholder="Bairro" required="" type="text" value="" />
                        </div>
                        <div class="flex flex-col w-full">
                            <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="numero">
                                Número
                            </label>
                            <input autocomplete="off"
                                class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                                id="numero" name="numero" placeholder="Número" required="" type="text" value="" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                        <div class="flex flex-col w-full">
                            <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="cidade">
                                Cidade
                            </label>
                            <select
                                class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                                id="cidade" name="cidade" required="">
                                <option disabled="" selected="" value="">
                                    Selecione a cidade
                                </option>
                                <option data-estadoid="1" data-estadosigla="SP" value="1">
                                    São Paulo
                                </option>
                                <option data-estadoid="2" data-estadosigla="RJ" value="2">
                                    Rio de Janeiro
                                </option>
                                <option data-estadoid="3" data-estadosigla="MG" value="3">
                                    Belo Horizonte
                                </option>
                            </select>
                        </div>
                        <div class="flex flex-col w-full">
                            <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="estado">
                                Estado
                            </label>
                            <input autocomplete="off"
                                class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full bg-gray-100"
                                id="estado" name="estado_display" placeholder="--" readonly="" required="" type="text"
                                value="" />
                            <input id="estado_id_hidden" name="estado" type="hidden" value="" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div class="flex flex-col w-full">
                            <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg"
                                for="complemento">
                                Complemento
                            </label>
                            <input autocomplete="off"
                                class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                                id="complemento" name="complemento" placeholder="Complemento" type="text" value="" />
                        </div>
                        <div class="flex flex-col w-full">
                            <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="cep">
                                CEP
                            </label>
                            <input autocomplete="off"
                                class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                                id="cep" name="cep" placeholder="CEP" required="" type="text" value="" />
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex flex-col w-full">
                        <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="classificacao">
                            Classificação
                        </label>
                        <select
                            class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                            id="classificacao" name="classificacao" required="">
                            <option disabled="" selected="" value="">
                                Selecione a classificação
                            </option>
                            <option value="1">
                                Livre
                            </option>
                            <option value="2">
                                10 anos
                            </option>
                            <option value="3">
                                12 anos
                            </option>
                            <option value="4">
                                14 anos
                            </option>
                            <option value="5">
                                16 anos
                            </option>
                            <option value="6">
                                18 anos
                            </option>
                        </select>
                    </div>
                    <div class="flex flex-col w-full">
                        <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg"
                            for="tipo-publicacao">
                            Tipo de Público
                        </label>
                        <select
                            class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                            id="tipo-publicacao" name="tipo-publicacao" required="">
                            <option disabled="" selected="" value="">
                                Selecione o tipo
                            </option>
                            <option value="1">
                                Público Geral
                            </option>
                            <option value="2">
                                Estudantes
                            </option>
                            <option value="3">
                                Profissionais
                            </option>
                        </select>
                    </div>
                    <div class="flex flex-col w-full">
                        <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="segmento">
                            Segmento
                        </label>
                        <select
                            class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                            id="segmento" name="segmento" required="">
                            <option disabled="" selected="" value="">
                                Selecione o segmento
                            </option>
                            <option value="1">
                                Tecnologia
                            </option>
                            <option value="2">
                                Educação
                            </option>
                            <option value="3">
                                Saúde
                            </option>
                            <option value="4">
                                Entretenimento
                            </option>
                        </select>
                    </div>
                    <div class="flex flex-col w-full">
                        <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="categoria">
                            Categoria
                        </label>
                        <select
                            class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                            id="categoria" name="categoria" required="">
                            <option disabled="" selected="" value="">
                                Selecione a categoria
                            </option>
                            <option value="1">
                                Show
                            </option>
                            <option value="2">
                                Workshop
                            </option>
                            <option value="3">
                                Palestra
                            </option>
                            <option value="4">
                                Feira
                            </option>
                        </select>
                    </div>
                    <div class="flex flex-col w-full space-y-2">
                        <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="telefone">
                            Telefone Celular
                        </label>
                        <div
                            class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-4">
                            <input autocomplete="off"
                                class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                                id="telefone" name="telefone" placeholder="(XX) XXXXX-XXXX" type="tel" value="" />
                            <div class="flex items-center space-x-2">
                                <input class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                    id="whatsapp" name="whatsapp" type="checkbox" />
                                <label class="font-medium text-gray-700 text-base sm:text-lg cursor-pointer select-none"
                                    for="whatsapp">
                                    WhatsApp
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="flex flex-col w-full">
                        <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="instagram">
                            Instagram
                        </label>
                        <input autocomplete="off"
                            class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                            id="instagram" name="instagram" placeholder="@usuario" type="text" value="" />
                    </div>
                    <div class="flex flex-col w-full">
                        <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="tiktok">
                            Tiktok
                        </label>
                        <input autocomplete="off"
                            class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                            id="tiktok" name="tiktok" placeholder="@usuario" type="text" value="" />
                    </div>
                    <div class="flex flex-col w-full">
                        <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="site">
                            Site
                        </label>
                        <input autocomplete="off"
                            class="border border-gray-300 rounded-md px-3 py-2 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                            id="site" name="site" placeholder="https://exemplo.com" type="url" value="" />
                    </div>
                </div>
                <div class="flex flex-col w-full mt-4">
                    <label class="mb-1 sm:mb-2 font-medium text-gray-700 text-base sm:text-lg" for="tipo-evento">
                        Tipo de Evento
                    </label>
                    <select
                        class="border border-gray-300 rounded-md px-4 py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                        id="tipo-evento" name="tipo-evento" required="">
                        <option disabled="" selected="" value="">
                            Selecione o tipo de evento
                        </option>
                        <option value="1">
                            Palestra
                        </option>
                        <option value="2">
                            Workshop
                        </option>
                        <option value="3">
                            Show
                        </option>
                        <option value="4">
                            Feira
                        </option>
                    </select>
                </div>
            </fieldset>
            <div class="flex justify-center">
                <button
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md px-10 py-2 transition focus:outline-none focus:ring-2 focus:ring-blue-500"
                    type="submit">
                    Cadastrar
                </button>
            </div>
        </form>
    </section>
    <script>
        // Preview Images
        const inputsFoto = document.querySelectorAll('input[type="file"]');

        inputsFoto.forEach(input => {
            input.addEventListener('change', function() {
                const previewId = `preview-${this.id}`;
                const previewElement = document.getElementById(previewId);
                const file = this.files[0];

                if (file) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        previewElement.src = e.target.result;
                    }

                    reader.readAsDataURL(file);
                } else {
                    // Reseta a pré-visualização se o usuário cancelar a seleção
                    previewElement.src = `https://placehold.co/80x80?text=${previewId.replace('preview-', 'Foto+')}`;
                }
            });
        });



        // Estado e Cidade
        const cidadeSelect = document.getElementById('cidade');
        const estadoInput = document.getElementById('estado');
        const estadoIdHidden = document.getElementById('estado_id_hidden');

        cidadeSelect.addEventListener('change', () => {
            const selectedOption = cidadeSelect.options[cidadeSelect.selectedIndex];
            const estadoSigla = selectedOption.getAttribute('data-estadosigla') || '';
            const estadoId = selectedOption.getAttribute('data-estadoid') || '';
            estadoInput.value = estadoSigla;
            estadoIdHidden.value = estadoId;
        });
    </script>
</body>

</html>