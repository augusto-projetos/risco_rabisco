# 🛒 Risco & Rabisco Papelaria (Projeto Senac)

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)

Este é o repositório do projeto "Risco & Rabisco", um aplicativo web completo de catálogo de papelaria desenvolvido para a Feira de Projetos do Senac. A aplicação simula uma plataforma de orçamento, permitindo que usuários criem contas, salvem produtos favoritos e montem um orçamento detalhado em tempo real.

---

## 🎯 Sobre o Projeto

O desafio era criar um site interativo que demonstrasse competências full-stack (PHP, MySQL, JS, CSS) sem a complexidade de um gateway de pagamento real. A solução foi criar um **sistema de orçamento**: o usuário não "compra" os produtos, mas os adiciona a um orçamento que calcula o total, permitindo ao usuário planejar seus gastos com material escolar.

## ✨ Funcionalidades Principais

O sistema é dividido em duas partes: uma área pública (Landing Page e "Sobre") e uma área privada (o aplicativo, acessível após login).

### Área Pública
* **Landing Page (`index.php`):** Página de boas-vindas com teaser de produtos e botões de ação para registro/login.
* **Página "Sobre" (`sobre.php`):** Informações (fictícias) sobre a loja, contato e um guia de "Como Funciona".

### Área Privada (Aplicativo)
* **Autenticação Segura:** Sistema completo de **Registro** e **Login** usando `password_hash()` e `password_verify()` do PHP.
* **Catálogo de Produtos (`produtos.php`):**
    * Exibe todos os produtos cadastrados no banco de dados.
    * **Pesquisa em Tempo Real:** Filtra produtos dinamicamente com JavaScript.
* **Sistema de Orçamento (`orcamento.php`):**
    * Adiciona produtos ao orçamento (com `INSERT` ou `UPDATE` de quantidade).
    * Calcula subtotais e o valor **total** do orçamento em tempo real.
    * Permite **atualizar a quantidade** ou **remover** itens (usando um único script PHP inteligente).
* **Sistema de Favoritos (`favoritos.php`):**
    * Permite ao usuário salvar itens para ver mais tarde (lógica de `INSERT IGNORE`).
    * Permite remover itens da lista (lógica de `DELETE`).
* **Gerenciamento de Perfil (`perfil.php`):**
    * Permite ao usuário **alterar seu nome**.
    * Permite ao usuário **alterar sua senha** de forma segura (verificando a senha atual).
* **Design 100% Responsivo:** O cabeçalho e todas as páginas (Orçamento, Produtos, Perfil) se adaptam perfeitamente a dispositivos móveis.
* **Tratamento de Erros:** O sistema captura erros de SQL (como sessões de usuário inválidas) e redireciona o usuário de forma amigável, sem quebrar a aplicação.

---

## 🛠️ Tecnologias Utilizadas

Este projeto foi construído do zero, com foco em um fluxo de trabalho de desenvolvimento moderno.

* **Back-end:** **PHP 8.2** (Lógica de sessão, autenticação, scripts de ação).
* **Banco de Dados:** **MySQL** (Relacionamento de tabelas com `FOREIGN KEY`, consultas com `JOIN`).
* **Front-end:** **HTML5**, **CSS3** (Flexbox, Grid, Variáveis) e **JavaScript (ES6)** (Event Listeners, manipulação de DOM).
* **Servidor:** XAMPP (Apache + MySQL).
* **Assistente de IA:** **Gemini (Google AI)** foi utilizado como assistente de *pair programming*, auxiliando na depuração de código, otimização de consultas SQL e sugestão de boas práticas.

---

## 🚀 Como Executar o Projeto

Para rodar este projeto localmente, você precisará do [XAMPP](https://www.apachefriends.org/pt_br/index.html) (ou similar) instalado.

1.  **Clone o Repositório:**
    ```bash
    git clone https://github.com/augusto-projetos/projeto_risco_rabisco
    ```

2.  **Mova os Arquivos:**
    * Mova a pasta inteira do projeto para dentro do diretório `htdocs` da sua instalação do XAMPP (ex: `C:/xampp/htdocs/risco_rabisco`).

3.  **Inicie o XAMPP:**
    * Inicie os módulos **Apache** e **MySQL**.

4.  **Importe o Banco de Dados:**
    * Abra o **phpMyAdmin** (normalmente `http://localhost/phpmyadmin`).
    * Crie um novo banco de dados chamado `risco_rabisco`.
    * Selecione o banco `risco_rabisco` que você acabou de criar.
    * Clique na aba **"Importar"**.
    * Selecione o arquivo `risco_rabisco.sql` (que está neste repositório) e clique em "Executar".

5.  **Pronto!**
    * Acesse `http://localhost/risco_rabisco/` (ou o nome da pasta que você usou) no seu navegador.
