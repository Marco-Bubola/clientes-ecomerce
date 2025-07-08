<?php
require_once '../config/database.php';

$mensagem = '';
$tipo_mensagem = '';

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
            ],
            'produtos_adquiridos' => [],
            'historico_compras' => []
        ];
        
        // Processar produtos adquiridos se fornecidos
        if (!empty($_POST['produtos'])) {
            $produtos = explode(',', $_POST['produtos']);
            foreach ($produtos as $produto) {
                $dadosCliente['produtos_adquiridos'][] = [
                    'nome' => trim($produto),
                    'data_aquisicao' => date('Y-m-d H:i:s')
                ];
            }
        }
        
        // Validar dados
        $erros = validarDadosCliente($dadosCliente);
        
        if (empty($erros)) {
            $clienteManager = new ClienteManager();
            $id = $clienteManager->inserirCliente($dadosCliente);
            
            $mensagem = "Cliente cadastrado com sucesso! ID: " . $id;
            $tipo_mensagem = 'success';
            
            // Limpar formulário
            $_POST = [];
        } else {
            $mensagem = implode('<br>', $erros);
            $tipo_mensagem = 'danger';
        }
        
    } catch (Exception $e) {
        $mensagem = "Erro ao cadastrar cliente: " . $e->getMessage();
        $tipo_mensagem = 'danger';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cliente - E-commerce</title>
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
                <a class="nav-link active" href="cadastro-cliente.php">
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
            <div class="col-md-8 offset-md-2">
                <div class="card shadow-lg">
                    <div class="card-header bg-gradient text-white">
                        <h3><i class="fas fa-user-plus"></i> Cadastro de Cliente</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($mensagem)): ?>
                            <div class="alert alert-<?php echo $tipo_mensagem; ?> alert-dismissible fade show" role="alert">
                                <?php echo $mensagem; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nome" class="form-label">Nome Completo *</label>
                                        <input type="text" class="form-control" id="nome" name="nome" 
                                               value="<?php echo htmlspecialchars($_POST['nome'] ?? ''); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email *</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="telefone" class="form-label">Telefone</label>
                                        <input type="tel" class="form-control" id="telefone" name="telefone" 
                                               value="<?php echo htmlspecialchars($_POST['telefone'] ?? ''); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="produtos" class="form-label">Produtos Adquiridos</label>
                                        <input type="text" class="form-control" id="produtos" name="produtos" 
                                               value="<?php echo htmlspecialchars($_POST['produtos'] ?? ''); ?>"
                                               placeholder="Separe os produtos por vírgula">
                                        <small class="form-text text-muted">Ex: Notebook, Mouse, Teclado</small>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h5>Endereço</h5>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="logradouro" class="form-label">Logradouro *</label>
                                        <input type="text" class="form-control" id="logradouro" name="logradouro" 
                                               value="<?php echo htmlspecialchars($_POST['logradouro'] ?? ''); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="numero" class="form-label">Número</label>
                                        <input type="text" class="form-control" id="numero" name="numero" 
                                               value="<?php echo htmlspecialchars($_POST['numero'] ?? ''); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="complemento" class="form-label">Complemento</label>
                                        <input type="text" class="form-control" id="complemento" name="complemento" 
                                               value="<?php echo htmlspecialchars($_POST['complemento'] ?? ''); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="bairro" class="form-label">Bairro</label>
                                        <input type="text" class="form-control" id="bairro" name="bairro" 
                                               value="<?php echo htmlspecialchars($_POST['bairro'] ?? ''); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="cidade" class="form-label">Cidade *</label>
                                        <input type="text" class="form-control" id="cidade" name="cidade" 
                                               value="<?php echo htmlspecialchars($_POST['cidade'] ?? ''); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="estado" class="form-label">Estado</label>
                                        <select class="form-select" id="estado" name="estado">
                                            <option value="">Selecione...</option>
                                            <option value="AC" <?php echo ($_POST['estado'] ?? '') == 'AC' ? 'selected' : ''; ?>>Acre</option>
                                            <option value="AL" <?php echo ($_POST['estado'] ?? '') == 'AL' ? 'selected' : ''; ?>>Alagoas</option>
                                            <option value="AP" <?php echo ($_POST['estado'] ?? '') == 'AP' ? 'selected' : ''; ?>>Amapá</option>
                                            <option value="AM" <?php echo ($_POST['estado'] ?? '') == 'AM' ? 'selected' : ''; ?>>Amazonas</option>
                                            <option value="BA" <?php echo ($_POST['estado'] ?? '') == 'BA' ? 'selected' : ''; ?>>Bahia</option>
                                            <option value="CE" <?php echo ($_POST['estado'] ?? '') == 'CE' ? 'selected' : ''; ?>>Ceará</option>
                                            <option value="DF" <?php echo ($_POST['estado'] ?? '') == 'DF' ? 'selected' : ''; ?>>Distrito Federal</option>
                                            <option value="ES" <?php echo ($_POST['estado'] ?? '') == 'ES' ? 'selected' : ''; ?>>Espírito Santo</option>
                                            <option value="GO" <?php echo ($_POST['estado'] ?? '') == 'GO' ? 'selected' : ''; ?>>Goiás</option>
                                            <option value="MA" <?php echo ($_POST['estado'] ?? '') == 'MA' ? 'selected' : ''; ?>>Maranhão</option>
                                            <option value="MT" <?php echo ($_POST['estado'] ?? '') == 'MT' ? 'selected' : ''; ?>>Mato Grosso</option>
                                            <option value="MS" <?php echo ($_POST['estado'] ?? '') == 'MS' ? 'selected' : ''; ?>>Mato Grosso do Sul</option>
                                            <option value="MG" <?php echo ($_POST['estado'] ?? '') == 'MG' ? 'selected' : ''; ?>>Minas Gerais</option>
                                            <option value="PA" <?php echo ($_POST['estado'] ?? '') == 'PA' ? 'selected' : ''; ?>>Pará</option>
                                            <option value="PB" <?php echo ($_POST['estado'] ?? '') == 'PB' ? 'selected' : ''; ?>>Paraíba</option>
                                            <option value="PR" <?php echo ($_POST['estado'] ?? '') == 'PR' ? 'selected' : ''; ?>>Paraná</option>
                                            <option value="PE" <?php echo ($_POST['estado'] ?? '') == 'PE' ? 'selected' : ''; ?>>Pernambuco</option>
                                            <option value="PI" <?php echo ($_POST['estado'] ?? '') == 'PI' ? 'selected' : ''; ?>>Piauí</option>
                                            <option value="RJ" <?php echo ($_POST['estado'] ?? '') == 'RJ' ? 'selected' : ''; ?>>Rio de Janeiro</option>
                                            <option value="RN" <?php echo ($_POST['estado'] ?? '') == 'RN' ? 'selected' : ''; ?>>Rio Grande do Norte</option>
                                            <option value="RS" <?php echo ($_POST['estado'] ?? '') == 'RS' ? 'selected' : ''; ?>>Rio Grande do Sul</option>
                                            <option value="RO" <?php echo ($_POST['estado'] ?? '') == 'RO' ? 'selected' : ''; ?>>Rondônia</option>
                                            <option value="RR" <?php echo ($_POST['estado'] ?? '') == 'RR' ? 'selected' : ''; ?>>Roraima</option>
                                            <option value="SC" <?php echo ($_POST['estado'] ?? '') == 'SC' ? 'selected' : ''; ?>>Santa Catarina</option>
                                            <option value="SP" <?php echo ($_POST['estado'] ?? '') == 'SP' ? 'selected' : ''; ?>>São Paulo</option>
                                            <option value="SE" <?php echo ($_POST['estado'] ?? '') == 'SE' ? 'selected' : ''; ?>>Sergipe</option>
                                            <option value="TO" <?php echo ($_POST['estado'] ?? '') == 'TO' ? 'selected' : ''; ?>>Tocantins</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="cep" class="form-label">CEP *</label>
                                        <input type="text" class="form-control" id="cep" name="cep" 
                                               value="<?php echo htmlspecialchars($_POST['cep'] ?? ''); ?>" 
                                               maxlength="9" placeholder="00000-000" required>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Cadastrar Cliente
                                </button>
                                <a href="../index.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Voltar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js"></script>
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
