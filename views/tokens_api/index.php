<?php include_once 'views/layouts/header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="index.php?action=dashboard" class="btn btn-secondary btn-sm me-2">
                    <i class="fas fa-arrow-left me-1"></i> Volver al Panel
                </a>
                <h2 class="mb-0 d-inline-block"><i class="fas fa-key me-2 text-primary"></i>Gestión de Tokens API</h2>
            </div>
            <a href="index.php?action=crear_token_api" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Nuevo Token
            </a>
        </div>

        <?php if(isset($_SESSION['success_message'])): ?>
            <div class='alert alert-success alert-dismissible fade show' role='alert'>
                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['error_message'])): ?>
            <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
        <?php endif; ?>

        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Listado de Tokens API</h5>
                <span class="badge bg-light text-primary"><?php echo $model->countAll(); ?> registros</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tabla-tokens-api">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Token</th>
                                <th>Cliente</th>
                                <th>RUC</th>
                                <th>Fecha Registro</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $contador = 1;
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): 
                            ?>
                            <tr>
                                <td><?php echo $contador++; ?></td>
                                <td>
                                    <code class="text-truncate d-inline-block" style="max-width: 150px;">
                                        <?php echo $row['token']; ?>
                                    </code>
                                </td>
                                <td><?php echo $row['razon_social']; ?></td>
                                <td>
                                    <span class="badge bg-secondary"><?php echo $row['ruc']; ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        <?php echo date('d/m/Y', strtotime($row['fecha_registro'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo $row['estado'] ? 'success' : 'secondary'; ?>">
                                        <?php echo $row['estado'] ? 'Activo'