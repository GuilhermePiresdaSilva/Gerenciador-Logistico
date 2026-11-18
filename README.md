# Gerenciador Logístico

## Descrição

O Gerenciador Logístico é uma aplicação web desenvolvida para auxiliar no cadastro e gerenciamento de informações relacionadas a produtos, endereços, transportadores e fretes.

O sistema permite cadastrar, editar e excluir registros, gerar fretes relacionando produtos, endereços e transportadores e consultar relatórios de fretes por data.

## Tecnologias utilizadas

- PHP
- MySQL
- HTML
- CSS
- JavaScript
- Bootstrap 5.3.3
- XAMPP
- Git e GitHub

## Funcionalidades

- Cadastro, edição e exclusão de produtos.
- Cadastro, edição e exclusão de endereços.
- Cadastro, edição e exclusão de transportadores.
- Cadastro e gerenciamento de fretes.
- Associação de produtos aos fretes.
- Associação de origem, destino e transportador aos fretes.
- Consulta de fretes por data.
- Geração e download de relatório em formato CSV.
- Interface responsiva utilizando Bootstrap.

## Banco de dados

O sistema utiliza o banco de dados MySQL `gerenciador_logistico`, composto pelas seguintes tabelas:

- `produtos`
- `enderecos`
- `transportadores`
- `fretes`
- `frete_produtos`

A tabela `frete_produtos` permite relacionar vários produtos aos fretes.

## Como executar o projeto

### 1. Instalar o XAMPP

Instale o XAMPP e inicie os serviços:

- Apache
- MySQL

### 2. Colocar o projeto no XAMPP

Copie a pasta do projeto para:

`D:\xampp\htdocs\gerenciador-logistico`

### 3. Criar o banco de dados

No phpMyAdmin, crie o banco de dados:

`gerenciador_logistico`

Crie as tabelas necessárias conforme a estrutura utilizada pelo projeto.

### 4. Configurar a conexão

O arquivo `conexao.php` utiliza a seguinte configuração padrão do ambiente local:

- Servidor: `localhost`
- Usuário: `root`
- Banco de dados: `gerenciador_logistico`

### 5. Acessar o sistema

Com o Apache e o MySQL em execução, acesse:

`http://localhost/gerenciador-logistico`

## Estrutura principal

```text
gerenciador-logistico/
├── .gitignore
├── baixar_relatorio.php
├── conexao.php
├── enderecos.php
├── fretes.php
├── index.php
├── produtos.php
├── relatorio.php
└── transportadores.php
```

## Projeto acadêmico

Projeto desenvolvido como atividade acadêmica do curso de Análise e Desenvolvimento de Sistemas da UNIPAR EAD.

## Autor

Guilherme Pires da Silva