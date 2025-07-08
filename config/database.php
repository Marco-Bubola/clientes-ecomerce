<?php
// Incluir autoload do Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Configuração do MongoDB
class Database {
    private $host = 'localhost';
    private $port = 27017;
    private $database = 'ecommerce_db';
    private $connection;
    
    public function __construct() {
        $this->connect();
    }
    
    private function connect() {
        try {
            // Conexão com MongoDB usando MongoDB PHP Driver
            $this->connection = new MongoDB\Client("mongodb://{$this->host}:{$this->port}");
            echo "<!-- Conexão com MongoDB estabelecida com sucesso -->";
        } catch (Exception $e) {
            die("Erro ao conectar com MongoDB: " . $e->getMessage());
        }
    }
    
    public function getDatabase() {
        return $this->connection->selectDatabase($this->database);
    }
    
    public function getCollection($collectionName) {
        return $this->getDatabase()->selectCollection($collectionName);
    }
}

// Classe para gerenciar clientes
class ClienteManager {
    private $collection;
    
    public function __construct() {
        $db = new Database();
        $this->collection = $db->getCollection('clientes');
    }
    
    public function inserirCliente($dadosCliente) {
        try {
            // Adiciona timestamp de criação
            $dadosCliente['data_criacao'] = new MongoDB\BSON\UTCDateTime();
            $dadosCliente['data_atualizacao'] = new MongoDB\BSON\UTCDateTime();
            
            $resultado = $this->collection->insertOne($dadosCliente);
            return $resultado->getInsertedId();
        } catch (Exception $e) {
            throw new Exception("Erro ao inserir cliente: " . $e->getMessage());
        }
    }
    
    public function listarClientes() {
        try {
            $cursor = $this->collection->find();
            return $cursor->toArray();
        } catch (Exception $e) {
            throw new Exception("Erro ao listar clientes: " . $e->getMessage());
        }
    }
    
    public function buscarClientePorId($id) {
        try {
            $objectId = new MongoDB\BSON\ObjectId($id);
            return $this->collection->findOne(['_id' => $objectId]);
        } catch (Exception $e) {
            throw new Exception("Erro ao buscar cliente: " . $e->getMessage());
        }
    }
    
    public function atualizarCliente($id, $dadosCliente) {
        try {
            $objectId = new MongoDB\BSON\ObjectId($id);
            $dadosCliente['data_atualizacao'] = new MongoDB\BSON\UTCDateTime();
            
            $resultado = $this->collection->updateOne(
                ['_id' => $objectId],
                ['$set' => $dadosCliente]
            );
            
            return $resultado->getModifiedCount();
        } catch (Exception $e) {
            throw new Exception("Erro ao atualizar cliente: " . $e->getMessage());
        }
    }
    
    public function buscarClientePorEmail($email) {
        try {
            return $this->collection->findOne(['email' => $email]);
        } catch (Exception $e) {
            throw new Exception("Erro ao buscar cliente por email: " . $e->getMessage());
        }
    }
    
    public function verificarEmailUnico($email, $excluirId = null) {
        try {
            $filtro = ['email' => $email];
            
            // Se estiver editando, excluir o próprio registro da verificação
            if ($excluirId) {
                $filtro['_id'] = ['$ne' => new MongoDB\BSON\ObjectId($excluirId)];
            }
            
            $cliente = $this->collection->findOne($filtro);
            return $cliente === null; // Retorna true se não encontrou (email único)
        } catch (Exception $e) {
            throw new Exception("Erro ao verificar unicidade do email: " . $e->getMessage());
        }
    }
}

// Função para validar dados do cliente
function validarDadosCliente($dados) {
    $erros = [];
    
    if (empty($dados['nome'])) {
        $erros[] = "Nome é obrigatório";
    }
    
    if (empty($dados['email'])) {
        $erros[] = "Email é obrigatório";
    } elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
        $erros[] = "Email inválido";
    }
    
    if (empty($dados['endereco']['logradouro'])) {
        $erros[] = "Logradouro é obrigatório";
    }
    
    if (empty($dados['endereco']['cidade'])) {
        $erros[] = "Cidade é obrigatória";
    }
    
    if (empty($dados['endereco']['cep'])) {
        $erros[] = "CEP é obrigatório";
    }
    
    return $erros;
}
?>
