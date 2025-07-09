<<<<<<< HEAD
# Eventos-Ribeirao
=======
# Eventos Ribeirão

Este é um projeto Next.js para um portal de eventos em Ribeirão Preto, desenvolvido com TypeScript e estilizado com Tailwind CSS.

## Visão Geral do Projeto

O objetivo deste projeto é fornecer uma plataforma centralizada para a divulgação e visualização de eventos que acontecem na cidade de Ribeirão Preto e região. A aplicação foi construída utilizando tecnologias modernas para garantir uma experiência de usuário rápida, responsiva e agradável.

---

## 🚀 Começando

Estas instruções fornecerão uma cópia do projeto em funcionamento na sua máquina local para fins de desenvolvimento e teste.

### Pré-requisitos

Para executar este projeto, você precisará ter o Node.js e o npm (ou Yarn/pnpm) instalados em sua máquina.

- [Node.js](https://nodejs.org/) (versão 20 ou superior recomendada)
- [npm](https://www.npmjs.com/get-npm)

### Instalação

1.  **Clone o repositório:**
    ```bash
    git clone <URL_DO_REPOSITORIO>
    cd eventos-ribeirao
    ```

2.  **Instale as dependências:**
    Usando npm (que criará a pasta `node_modules` com base no arquivo `package-lock.json`):
    ```bash
    npm install
    ```

---

## ⚙️ Executando a Aplicação

### Servidor de Desenvolvimento

Para iniciar o servidor de desenvolvimento com o Turbopack (para uma experiência de desenvolvimento mais rápida), execute o seguinte comando:

```bash
npm run dev
```

Abra [http://localhost:3000](http://localhost:3000) em seu navegador para ver o resultado. A página será atualizada automaticamente conforme você edita os arquivos.

### Scripts Disponíveis

No diretório do projeto, você pode executar os seguintes scripts:

-   `npm run dev`: Inicia a aplicação em modo de desenvolvimento com Turbopack.
-   `npm run build`: Compila a aplicação para produção.
-   `npm run start`: Inicia um servidor de produção com a versão compilada.
-   `npm run lint`: Executa o ESLint para analisar o código em busca de problemas e inconsistências de estilo.

---

## 📂 Estrutura do Projeto

A estrutura de pastas segue o padrão do Next.js App Router:

```
eventos-ribeirao/
├── public/             # Arquivos estáticos (imagens, fontes, etc.)
├── src/
│   ├── app/            # Rotas da aplicação, layouts e páginas
│   ├── components/     # Componentes React reutilizáveis
│   ├── lib/            # Funções utilitárias
│   └── css/            # Arquivos CSS globais e de estilo
├── .eslintrc.json      # Configurações do ESLint
├── next.config.ts      # Configurações do Next.js
├── package.json        # Dependências e scripts do projeto
└── tsconfig.json       # Configurações do TypeScript
```

---

## ✨ Padrão de Código e Linting

Este projeto utiliza **ESLint** para garantir um padrão de código consistente e evitar erros comuns. Para verificar seu código, execute:

```bash
npm run lint
```

Recomenda-se a instalação de uma extensão do ESLint em seu editor de código para receber feedback em tempo real.

---

## 🛠️ Tecnologias Utilizadas

-   **[Next.js](https://nextjs.org/)** - Framework React para produção.
-   **[React](https://reactjs.org/)** - Biblioteca para construção de interfaces de usuário.
-   **[TypeScript](https://www.typescriptlang.org/)** - Superset de JavaScript que adiciona tipagem estática.
-   **[Tailwind CSS](https://tailwindcss.com/)** - Framework CSS utility-first para estilização rápida.
-   **[Shadcn/ui](https://ui.shadcn.com/)** - Coleção de componentes de UI reutilizáveis.
-   **[ESLint](https://eslint.org/)** - Ferramenta de linting para JavaScript e TypeScript.
>>>>>>> master
