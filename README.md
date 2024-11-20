# Finplanner

## Descrição
Este projeto é um sistema de controle financeiro pessoal, desenvolvido para ajudar os usuários a monitorarem suas finanças de maneira prática. Ele oferece um painel de controle para registrar receitas e despesas, definir metas financeiras e acompanhar o saldo e desempenho ao longo do tempo.

## Funcionalidades
- **Cadastro de Receitas e Despesas**: Adicione, edite e remova transações.
- **Controle de Saldo**: Acompanhe seu saldo atual.
- **Metas Financeiras**: Defina metas de economia.

## Público-Alvo
Este sistema é destinado a pessoas que buscam uma forma prática de entender e gerenciar suas finanças, otimizando a economia e controle financeiro.

## Tecnologias Utilizadas
- **Backend**: PHP puro.
- **Frontend**: HTML, CSS e Bootstrap.
- **Banco de Dados**: MySQL.
- **Testes**: PHPUnit para validação de funcionalidades.

## Dependências
- Composer
- PHPUnit
- Bootstrap

## Estrutura do Projeto
- `/controller`
- `/models`
- `/core`
- `/public`
- `/routes`
- `/vendor`
- `/database`
- `/tests`

## **Instalação**

### 1. **Clone o Repositório**

Clone este repositório no seu ambiente local:

```bash
git clone https://github.com/RodrigoLodi/finclass
cd finclass
```

### 2. **Configuração do Banco de Dados**

1. Abra o arquivo **`database/Database.php`** e edite as credenciais:

```php
private $host = "localhost"; // Host do banco de dados
private $user = "root";      // Usuário do banco
private $password = "";      // Senha do banco
private $database = "finplanner"; // Nome do banco de dados
```

### 3. **Instalar Dependências**

Instale as dependências do projeto utilizando o Composer:

```bash
composer install
```

---

## **Executando o Projeto**

### 1. **Inicie o Servidor PHP**

Use o servidor embutido do PHP para executar o projeto. Navegue até o diretório do projeto e execute:

```bash
php -S localhost:8000 -t public
```

Agora, você pode acessar o sistema em: [http://localhost:8000](http://localhost:8000)

---

## **Testes**

Este projeto utiliza PHPUnit para testes unitários.

1. **Configuração do PHPUnit**
   Certifique-se de que o PHPUnit está instalado globalmente ou via Composer:

```bash
composer require --dev phpunit/phpunit
```

2. **Executando os Testes**

Para executar os testes, use o seguinte comando:

```bash
./vendor/bin/phpunit
```

Os relatórios de sucesso ou falha serão exibidos no terminal.

---