# Sistema E-commerce - Clientes

Sistema de gerenciamento de client├── pages/
│   ├── cadastro-cliente.php  # Formulário de cadastro
│   ├── listar-clientes.php   # Lista de clientes
│   └── editar-cliente.php    # Edição de clientespara e-commerce desenvolvido com PHP, MongoDB e Bootstrap.

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

## Instalação

### 1. Configurar MongoDB

1. Baixe e instale o MongoDB Community Server
2. Inicie o serviço MongoDB
3. Crie o banco de dados:
   ```bash
   mongosh
   use ecommerce_db
   db.createCollection("clientes")
   ```

### 2. Instalar Extensão PHP MongoDB

#### Windows (XAMPP):
1. Baixe o driver PHP MongoDB: https://pecl.php.net/package/mongodb
2. Extraia o arquivo `php_mongodb.dll` na pasta `php/ext/` do XAMPP
3. Edite o arquivo `php.ini` e adicione:
   ```ini
   extension=mongodb
   ```
4. Reinicie o Apache

#### Linux/Mac:
```bash
sudo pecl install mongodb
echo "extension=mongodb.so" >> /etc/php/8.0/apache2/php.ini
```

### 3. Instalar Dependências

No diretório do projeto:
```bash
composer install
```

### 4. Configurar Permissões

Certifique-se de que o Apache tenha permissões de leitura/escrita na pasta do projeto.

## Estrutura do Projeto

```
clientes ecomerce/
├── config/
│   └── database.php          # Configuração do MongoDB
├── pages/
│   ├── cadastro-cliente.php  # Formulário de cadastro
│   ├── listar-clientes.php   # Lista de clientes
│   └── editar-cliente.php    # Edição de clientes
├── assets/
│   └── css/
│       └── style.css         # Estilos personalizados
├── composer.json             # Dependências do Composer
├── index.php                 # Página inicial
└── README.md                 # Este arquivo
```

## Uso

### Cadastrar Cliente

1. Acesse `http://localhost/clientes%20ecomerce/pages/cadastro-cliente.php`
2. Preencha o formulário com os dados do cliente
3. Adicione produtos separados por vírgula
4. Clique em "Cadastrar Cliente"

### Listar Clientes

1. Acesse `http://localhost/clientes%20ecomerce/pages/listar-clientes.php`
2. Visualize todos os clientes cadastrados
3. Use os botões de ação para ver detalhes ou editar

### Editar Cliente

1. Na lista de clientes, clique no ícone de editar (lápis) do cliente desejado
2. Modifique os dados necessários no formulário
3. Clique em "Salvar Alterações" para confirmar

### Editar Cliente

1. Acesse a lista de clientes
2. Clique no botão de editar (ícone de lápis) do cliente desejado
3. Modifique os dados necessários
4. Clique em "Salvar Alterações"

## Estrutura de Dados (MongoDB)

### Coleção: clientes

```javascript
{
  "_id": ObjectId("..."),
  "nome": "João Silva",
  "email": "joao@email.com",
  "telefone": "(11) 99999-9999",
  "endereco": {
    "logradouro": "Rua das Flores",
    "numero": "123",
    "complemento": "Apt 45",
    "bairro": "Centro",
    "cidade": "São Paulo",
    "estado": "SP",
    "cep": "01234-567"
  },
  "produtos_adquiridos": [
    {
      "nome": "Notebook",
      "data_aquisicao": "2025-01-15 10:30:00"
    }
  ],
  "historico_compras": [],
  "data_criacao": ISODate("2025-01-15T10:30:00Z"),
  "data_atualizacao": ISODate("2025-01-15T10:30:00Z")
}
```

## Troubleshooting

### Erro: "Class 'MongoDB\Client' not found"

1. Verifique se a extensão MongoDB está instalada:
   ```bash
   php -m | grep mongodb
   ```

2. Se não estiver instalada, siga os passos de instalação da extensão

### Erro: "Connection refused"

1. Verifique se o MongoDB está rodando:
   ```bash
   sudo systemctl status mongod
   ```

2. Inicie o MongoDB se necessário:
   ```bash
   sudo systemctl start mongod
   ```

### Erro: "Permission denied"

1. Verifique as permissões da pasta:
   ```bash
   chmod -R 755 /caminho/para/projeto
   ```

## Próximos Passos

- [x] Implementar edição de clientes
- [ ] Adicionar histórico de compras detalhado
- [ ] Implementar busca e filtros
- [ ] Adicionar validação de CEP via API
- [ ] Implementar backup automático
- [ ] Sistema de autenticação
- [ ] Relatórios e dashboards

## Suporte

Em caso de dúvidas ou problemas, verifique:
1. Logs do Apache: `/var/log/apache2/error.log`
2. Logs do MongoDB: `/var/log/mongodb/mongod.log`
3. Logs do PHP: Definido no `php.ini`

## Licença

Este projeto é para fins educacionais e pode ser usado livremente.
