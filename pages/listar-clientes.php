<?php
require_once '../config/database.php';

try {
    $clienteManager = new ClienteManager();
    $clientes = $clienteManager->listarClientes();
} catch (Exception $e) {
    $erro = "Erro ao carregar clientes: " . $e->getMessage();
    $clientes = [];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Clientes - E-commerce</title>
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
                <a class="nav-link active" href="listar-clientes.php">
                    <i class="fas fa-users"></i> Listar Clientes
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-header d-flex justify-content-between align-items-center bg-gradient text-white">
                        <h3><i class="fas fa-users"></i> Lista de Clientes</h3>
                        <a href="cadastro-cliente.php" class="btn btn-light">
                            <i class="fas fa-plus"></i> Novo Cliente
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if (isset($erro)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $erro; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (empty($clientes)): ?>
                            <div class="alert alert-info text-center" role="alert">
                                <h5>Nenhum cliente cadastrado</h5>
                                <p>Comece cadastrando seu primeiro cliente!</p>
                                <a href="cadastro-cliente.php" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Cadastrar Cliente
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nome</th>
                                            <th>Email</th>
                                            <th>Cidade</th>
                                            <th>Produtos</th>
                                            <th>Data Cadastro</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($clientes as $cliente): ?>
                                            <tr>
                                                <td>
                                                    <small class="text-muted">
                                                        <?php echo substr((string)$cliente['_id'], -8); ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($cliente['nome']); ?></strong>
                                                </td>
                                                <td>
                                                    <a href="mailto:<?php echo htmlspecialchars($cliente['email']); ?>">
                                                        <?php echo htmlspecialchars($cliente['email']); ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <?php echo htmlspecialchars($cliente['endereco']['cidade'] ?? 'N/A'); ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($cliente['produtos_adquiridos'])): ?>
                                                        <span class="badge bg-primary">
                                                            <?php echo count($cliente['produtos_adquiridos']); ?> produtos
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Nenhum produto</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                    if (isset($cliente['data_criacao'])) {
                                                        $data = $cliente['data_criacao']->toDateTime();
                                                        echo $data->format('d/m/Y H:i');
                                                    } else {
                                                        echo 'N/A';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-info" 
                                                                onclick="verDetalhes('<?php echo $cliente['_id']; ?>')"
                                                                title="Ver detalhes">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <a href="editar-cliente.php?id=<?php echo $cliente['_id']; ?>" 
                                                           class="btn btn-sm btn-warning"
                                                           title="Editar cliente">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    <small class="text-muted">
                                        Total de <?php echo count($clientes); ?> cliente(s) cadastrado(s)
                                    </small>
                                </div>
                                <div>
                                    <a href="../index.php" class="btn btn-secondary">
                                        <i class="fas fa-home"></i> Voltar ao Início
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para detalhes do cliente -->
    <div class="modal fade" id="modalDetalhes" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalhes do Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="conteudoDetalhes">
                        <!-- Conteúdo será carregado via JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js"></script>
    <script>
        function verDetalhes(clienteId) {
            // Implementar modal de detalhes
            document.getElementById('conteudoDetalhes').innerHTML = `
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle"></i> Cliente ID: ${clienteId}</h6>
                    <p>Funcionalidade de detalhes completos será implementada em breve.</p>
                    <p>Por enquanto, use o botão de editar para ver todos os dados.</p>
                </div>
            `;
            
            const modal = new bootstrap.Modal(document.getElementById('modalDetalhes'));
            modal.show();
        }
    </script>
</body>
</html>
