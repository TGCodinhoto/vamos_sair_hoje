<html class="h-full bg-[#1B3B57]" lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>
        Tela de Login
    </title>
    <link rel="shortcut icon" href="../image/favicon.svg" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com">
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&amp;display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="h-full flex items-center justify-center px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-xl shadow-lg">
        <div class="flex justify-center">
            <a href="index.php"><img alt="Logotipo da empresa em fundo branco, com ícone abstrato azul e texto ao lado" class="h-20 w-20" height="100" src="../image/favicon.svg" width="100" /></a>
        </div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Entrar na sua conta
        </h2>
        <form action="#" class="mt-8 space-y-6" method="POST" novalidate="">
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label class="sr-only" for="email">
                        Endereço de email
                    </label>
                    <input autocomplete="email" class="appearance-none rounded-t-md relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" id="email" name="email" placeholder="Endereço de email" required="" type="email" />
                </div>
                <div>
                    <label class="sr-only" for="password">
                        Senha
                    </label>
                    <input autocomplete="current-password" class="appearance-none rounded-b-md relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" id="password" name="password" placeholder="Senha" required="" type="password" />
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" id="remember_me" name="remember_me" type="checkbox" />
                    <label class="ml-2 block text-sm text-gray-900" for="remember_me">
                        Lembrar-me
                    </label>
                </div>
                <div class="text-sm">
                    <a class="font-medium text-blue-600 hover:text-blue-500" href="#">
                        Esqueceu a senha?
                    </a>
                </div>
            </div>
            <div>
                <button class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-semibold rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" type="submit">
                   
                    Entrar
                </button>
            </div>
        </form>
        <p class="mt-6 text-center text-sm text-gray-600">
            Não tem uma conta?
            <a class="font-medium text-blue-600 hover:text-blue-500" href="#">
                Crie uma agora
            </a>
        </p>
    </div>
</body>

</html>