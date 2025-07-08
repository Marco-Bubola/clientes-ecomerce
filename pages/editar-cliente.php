<?php
require_once '../config/database.php';

$mensagem = '';
$tipo_mensagem = '';
$cliente = null;

// Verificar se foi passado um ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: listar-clientes.php?erro=id_nao_fornecido');
    exit;
}

$clienteId = $_GET['id'];

try {
    $clienteManager = new ClienteManager();
    $cliente = $clienteManager->buscarClientePorId($clienteId);
    
    if (!$cliente) {
        header('Location: listar-clientes.php?erro=cliente_nao_encontrado');
        exit;
    }
} catch (Exception $e) {
    header('Location: listar-clientes.php?erro=erro_buscar_cliente');
    exit;
}

// Processar formulário de edição
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Preparar dados do cliente
        $dadosCliente = [
            'nome' => $_POST['nome'],
            'email' => $_POST['email'],
            'telefone' => $_POST['telefone'],
            'endereco' => [
                'logradouro' => $_POST['logradouro'],
                'numero' => $_POST['numero'],
                'complemento' => $_POST['complemento'],
                'bairro' => $_POST['bairro'],
                'cidade' => $_POST['cidade'],
                'estado' => $_POST['estado'],
                'cep' => $_POST['cep']
            ]
        ];
        
        // Processar produtos adquiridos se fornecidos
        if (!empty($_POST['produtos'])) {
            $produtos = explode(',', $_POST['produtos']);
            $dadosCliente['produtos_adquiridos'] = [];
            foreach ($produtos as $produto) {
                $dadosCliente['produtos_adquiridos'][] = [
                    'nome' => trim($produto),
                    'data_aquisicao' => date('Y-m-d H:i:s')
                ];
            }
        } else {
            // Manter produtos existentes se campo estiver vazio
            $dadosCliente['produtos_adquiridos'] = $cliente['produtos_adquiridos'] ?? [];
        }
        
        // Manter histórico existente
        $dadosCliente['historico_compras'] = $cliente['historico_compras'] ?? [];
        
        // Validar dados
        $erros = validarDadosCliente($dadosCliente);
        
        // Verificar se email já existe (excluindo o próprio cliente)
        if (!$clienteManager->verificarEmailUnico($dadosCliente['email'], $clienteId)) {
            $erros[] = "Este email já está sendo usado por outro cliente";
        }
        
        if (empty($erros)) {
            $modificados = $clienteManager->atualizarCliente($clienteId, $dadosCliente);
            
            if ($modificados > 0) {
                $mensagem = "Cliente atualizado com sucesso!";
                $tipo_mensagem = 'success';
                
                // Recarregar dados atualizados
                $cliente = $clienteManager->buscarClientePorId($clienteId);
            } else {
                $mensagem = "Nenhuma alteração foi detectada.";
                $tipo_mensagem = 'info';
            }
        } else {
            $mensagem = implode('<br>', $erros);
            $tipo_mensagem = 'danger';
        }
        
    } catch (Exception $e) {
        $mensagem = "Erro ao atualizar cliente: " . $e->getMessage();
        $tipo_mensagem = 'danger';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente - E-commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <i class="fas fa-store"></i> E-commerce Clientes
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="../index.php">
                    <i class="fas fa-home"></i> Início
                </a>
                <a class="nav-link" href="cadastro-cliente.php">
                    <i class="fas fa-user-plus"></i> Cadastrar Cliente
                </a>
                <a class="nav-link" href="listar-clientes.php">
                    <i class="fas fa-users"></i> Listar Clientes
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="card shadow-lg">
                    <div class="card-header bg-gradient text-white">
                        <h3>
                            <i class="fas fa-edit"></i> Editar Cliente
                            <small class="ms-2"><?php echo htmlspecialchars($cliente['nome']); ?></small>
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($mensagem)): ?>
                            <div class="alert alert-<?php echo $tipo_mensagem; ?> alert-dismissible fade show" role="alert">
                                <i class="fas fa-<?php echo $tipo_mensagem == 'success' ? 'check-circle' : ($tipo_mensagem == 'danger' ? 'exclamation-triangle' : 'info-circle'); ?>"></i>
                                <?php echo $mensagem; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nome" class="form-label">
                                            <i class="fas fa-user"></i> Nome Completo *
                                        </label>
                                        <input type="text" class="form-control" id="nome" name="nome" 
                                               value="<?php echo htmlspecialchars($cliente['nome']); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">
                                            <i class="fas fa-envelope"></i> Email *
                                        </label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?php echo htmlspecialchars($cliente['email']); ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="telefone" class="form-label">
                                            <i class="fas fa-phone"></i> Telefone
                                        </label>
                                        <input type="tel" class="form-control" id="telefone" name="telefone" 
                                               value="<?php echo htmlspecialchars($cliente['telefone'] ?? ''); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="produtos" class="form-label">
                                            <i class="fas fa-shopping-cart"></i> Produtos Adquiridos
                                        </label>
                                        <?php
                                        $produtos_str = '';
                                        if (!empty($cliente['produtos_adquiridos'])) {
                                            // Converter BSONArray/BSONDocument para array PHP
                                            $produtos_array = iterator_to_array($cliente['produtos_adquiridos']);
                                            $produtos_nomes = [];
                                            foreach ($produtos_array as $produto) {
                                                if (is_array($produto)) {
                                                    $produtos_nomes[] = $produto['nome'];
                                                } else if (is_object($produto) && isset($produto->nome)) {
                                                    $produtos_nomes[] = $produto->nome;
                                                } else if (is_object($produto) && method_exists($produto, 'toArray')) {
                                                    $produtoArray = $produto->toArray();
                                                    $produtos_nomes[] = $produtoArray['nome'] ?? '';
                                                }
                                            }
                                            $produtos_str = implode(', ', $produtos_nomes);
                                        }
                                        ?>
                                        <input type="text" class="form-control" id="produtos" name="produtos" 
                                               value="<?php echo htmlspecialchars($produtos_str); ?>"
                                               placeholder="Separe os produtos por vírgula">
                                        <small class="form-text text-muted">Ex: Notebook, Mouse, Teclado</small>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">
                            <h5><i class="fas fa-map-marker-alt"></i> Endereço</h5>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="logradouro" class="form-label">Logradouro *</label>
                                        <input type="text" class="form-control" id="logradouro" name="logradouro" 
                                               value="<?php echo htmlspecialchars($cliente['endereco']['logradouro']); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="numero" class="form-label">Número</label>
                                        <input type="text" class="form-control" id="numero" name="numero" 
                                               value="<?php echo htmlspecialchars($cliente['endereco']['numero'] ?? ''); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="complemento" class="form-label">Complemento</label>
                                        <input type="text" class="form-control" id="complemento" name="complemento" 
                                               value="<?php echo htmlspecialchars($cliente['endereco']['complemento'] ?? ''); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="bairro" class="form-label">Bairro</label>
                                        <input type="text" class="form-control" id="bairro" name="bairro" 
                                               value="<?php echo htmlspecialchars($cliente['endereco']['bairro'] ?? ''); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="cidade" class="form-label">Cidade *</label>
                                        <input type="text" class="form-control" id="cidade" name="cidade" 
                                               value="<?php echo htmlspecialchars($cliente['endereco']['cidade']); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="estado" class="form-label">Estado</label>
                                        <select class="form-select" id="estado" name="estado">
                                            <option value="">Selecione...</option>
                                            <?php
                                            $estados = [
                                                'AC' => 'Acre', 'AL' => 'Alagoas', 'AP' => 'Amapá', 'AM' => 'Amazonas',
                                                'BA' => 'Bahia', 'CE' => 'Ceará', 'DF' => 'Distrito Federal', 'ES' => 'Espírito Santo',
                                                'GO' => 'Goiás', 'MA' => 'Maranhão', 'MT' => 'Mato Grosso', 'MS' => 'Mato Grosso do Sul',
                                                'MG' => 'Minas Gerais', 'PA' => 'Pará', 'PB' => 'Paraíba', 'PR' => 'Paraná',
                                                'PE' => 'Pernambuco', 'PI' => 'Piauí', 'RJ' => 'Rio de Janeiro', 'RN' => 'Rio Grande do Norte',
                                                'RS' => 'Rio Grande do Sul', 'RO' => 'Rondônia', 'RR' => 'Roraima', 'SC' => 'Santa Catarina',
                                                'SP' => 'São Paulo', 'SE' => 'Sergipe', 'TO' => 'Tocantins'
                                            ];
                                            foreach ($estados as $sigla => $nome) {
                                                $selected = ($cliente['endereco']['estado'] ?? '') == $sigla ? 'selected' : '';
                                                echo "<option value='$sigla' $selected>$nome</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="cep" class="form-label">CEP *</label>
                                        <input type="text" class="form-control" id="cep" name="cep" 
                                               value="<?php echo htmlspecialchars($cliente['endereco']['cep']); ?>" 
                                               maxlength="9" placeholder="00000-000" required>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="listar-clientes.php" class="btn btn-secondary me-md-2">
                                    <i class="fas fa-arrow-left"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Salvar Alterações
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Máscara para CEP
        document.getElementById('cep').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 5) {
                value = value.replace(/^(\d{5})(\d)/, '$1-$2');
            }
            e.target.value = value;
        });
    </script>
</body>
</html>
