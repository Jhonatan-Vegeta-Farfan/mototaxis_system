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
                                        <?php echo $row['estado'] ? 'Activo' : 'Inactivo'; ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="index.php?action=editar_token_api&id=<?php echo $row['id']; ?>" 
                                           class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="index.php?action=eliminar_token_api&id=<?php echo $row['id']; ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('¿Está seguro de eliminar este token API?')"
                                           data-bs-toggle="tooltip" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if ($contador == 1): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-key fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No hay tokens API registrados</h5>
                                    <p class="text-muted">Agregue nuevos tokens API para comenzar</p>
                                    <a href="index.php?action=crear_token_api" class="btn btn-primary mt-2">
                                        <i class="fas fa-plus me-1"></i> Crear Token API
                                    </a>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'views/layouts/footer.php'; ?>