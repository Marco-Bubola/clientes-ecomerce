# Sistema E-commerce - Clientes

Sistema de gerenciamento de clientes para e-commerce desenvolvido com PHP, MongoDB e Bootstrap.

## Funcionalidades

- ✅ Cadastro de clientes
- ✅ Listagem de clientes  
- ✅ Edição de clientes
- ✅ Endereços completos
- ✅ Produtos adquiridos
- ✅ Histórico de compras
- ✅ Interface moderna e responsiva

## Requisitos do Sistema

- PHP 7.4 ou superior
- MongoDB 4.0 ou superior
- Apache Web Server (XAMPP recomendado)
- Composer
- Extensão PHP MongoDB

## Como Usar

### 1. Instalar MongoDB Community Server
- Baixe e instale o MongoDB
- Inicie o serviço MongoDB

### 2. Configurar PHP no XAMPP
1. Baixe o driver PHP MongoDB: https://pecl.php.net/package/mongodb
2. Extraia `php_mongodb.dll` na pasta `xampp/php/ext/`
3. Edite `xampp/php/php.ini` e adicione: `extension=mongodb`
4. Reinicie o Apache

### 3. Instalar Dependências
```bash
composer install
```

### 4. Criar Banco de Dados
Abra o MongoDB Compass e conecte em: `mongodb://localhost:27017`
- Crie o banco: `ecommerce_db`
- Crie a coleção: `clientes`

## Estrutura do Projeto

```
clientes-ecomerce/
├── config/
│   └── database.php          # Configuração do MongoDB
├── pages/
│   ├── cadastro-cliente.php  # Formulário de cadastro
│   ├── listar-clientes.php   # Lista de clientes
│   └── editar-cliente.php    # Edição de clientes
├── assets/css/style.css      # Estilos personalizados
├── composer.json             # Dependências
└── index.php                 # Página inicial
```

## Como Acessar

- **Página inicial**: `http://localhost/clientes-ecomerce/`
- **Cadastrar cliente**: `http://localhost/clientes-ecomerce/pages/cadastro-cliente.php`
- **Listar clientes**: `http://localhost/clientes-ecomerce/pages/listar-clientes.php`

## Conectar no MongoDB Compass

1. Abra o MongoDB Compass
2. Use a conexão: `mongodb://localhost:27017`
3. Banco de dados: `ecommerce_db`
4. Coleção: `clientes`

## Problemas Comuns

**Erro "Class 'MongoDB\Client' not found":**
- Verifique se instalou a extensão MongoDB no PHP
- Reinicie o Apache após configurar

**Erro "Connection refused":**
- Verifique se o MongoDB está rodando
- No Windows: verifique nos Serviços se "MongoDB" está ativo

**Não consegue conectar no Compass:**
- Use: `mongodb://localhost:27017`
- Verifique se o MongoDB está rodando na porta 27017
