<?php
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema E-commerce - Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-store"></i> E-commerce Clientes
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-home"></i> Início
                </a>
                <a class="nav-link" href="pages/cadastro-cliente.php">
                    <i class="fas fa-user-plus"></i> Cadastrar Cliente
                </a>
                <a class="nav-link" href="pages/listar-clientes.php">
                    <i class="fas fa-users"></i> Listar Clientes
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="hero-section text-center bg-light p-5 rounded">
                    <h1 class="display-4">Sistema de Clientes E-commerce</h1>
                    <p class="lead">Gerencie seus clientes, produtos e histórico de compras</p>
                    <hr class="my-4">
                    <div class="row mt-5">
                        <div class="col-md-6">
                            <div class="card hover-card">
                                <div class="card-body text-center">
                                    <div class="icon-large mb-3">
                                        <i class="fas fa-user-plus text-primary"></i>
                                    </div>
                                    <h5 class="card-title">Cadastrar Cliente</h5>
                                    <p class="card-text">Adicione novos clientes ao sistema com informações completas</p>
                                    <a href="pages/cadastro-cliente.php" class="btn btn-primary btn-lg">
                                        <i class="fas fa-plus"></i> Cadastrar
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card hover-card">
                                <div class="card-body text-center">
                                    <div class="icon-large mb-3">
                                        <i class="fas fa-users text-success"></i>
                                    </div>
                                    <h5 class="card-title">Listar Clientes</h5>
                                    <p class="card-text">Visualize, edite e gerencie todos os clientes cadastrados</p>
                                    <a href="pages/listar-clientes.php" class="btn btn-success btn-lg">
                                        <i class="fas fa-list"></i> Visualizar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
