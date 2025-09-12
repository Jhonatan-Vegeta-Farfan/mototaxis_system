<?php include_once 'views/layouts/header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="index.php?action=dashboard" class="btn btn-secondary btn-sm me-2">
                    <i class="fas fa-arrow-left me-1"></i> Volver al Panel
                </a>
                <h2 class="mb-0 d-inline-block"><i class="fas fa-building me-2 text-primary"></i>Gestión de Empresas</h2>
            </div>
            <a href="index.php?action=crear_empresa" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Nueva Empresa
            </a>
        </div>

        <?php if(isset($_GET['message'])): ?>
            <div class='alert alert-<?php echo (strpos($_GET['message'], 'Error') !== false) ? 'danger' : 'success'; ?> alert-dismissible fade show' role='alert'>
                <?php echo $_GET['message']; ?>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
        <?php endif; ?>

        <!-- Tarjetas para vista móvil -->
        <div class="d-block d-md-none">
            <?php 
            $empresas_data = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $empresas_data[] = $row;
            }
            
            $count = 0;
            foreach ($empresas_data as $row): 
                $count++;
            ?>
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><?php echo $row['razon_social']; ?></h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">RUC:</small>
                            <p class="mb-1"><?php echo $row['ruc']; ?></p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Representante:</small>
                            <p class="mb-1"><?php echo $row['representante_legal']; ?></p>
                        </div>
                        <div class="col-12">
                            <small class="text-muted">Registro:</small>
                            <p class="mb-2"><?php echo date('d/m/Y', strtotime($row['fecha_registro'])); ?></p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <a href="index.php?action=editar_empresa&id=<?php echo $row['id_empresa']; ?>" 
                           class="btn btn-sm btn-warning me-1">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="index.php?action=eliminar_empresa&id=<?php echo $row['id_empresa']; ?>" 
                           class="btn btn-sm btn-danger" 
                           onclick="return confirm('¿Está seguro de eliminar esta empresa?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if ($count == 0): ?>
            <div class="card shadow-sm mb-3">
                <div class="card-body text-center">
                    <i class="fas fa-building fa-3x text-muted mb-3"></i>
                    <h5>No hay empresas registradas</h5>
                    <p class="text-muted">Comienza agregando tu primera empresa</p>
                    <a href="index.php?action=crear_empresa" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Agregar Empresa
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Tabla para vista desktop -->
        <div class="card shadow d-none d-md-block">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Listado de Empresas</h5>
                <span class="badge bg-light text-primary"><?php echo count($empresas_data); ?> registros</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tabla-empresas">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Razón Social</th>
                                <th>RUC</th>
                                <th>Representante Legal</th>
                                <th>Fecha Registro</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $contador = 1;
                            foreach ($empresas_data as $row): 
                            ?>
                            <tr>
                                <td><?php echo $contador++; ?></td>
                                <td>
                                    <strong><?php echo $row['razon_social']; ?></strong>
                                </td>
                                <td>
                                    <span class="badge bg-secondary"><?php echo $row['ruc']; ?></span>
                                </td>
                                <td><?php echo $row['representante_legal']; ?></td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        <?php echo date('d/m/Y', strtotime($row['fecha_registro'])); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="index.php?action=editar_empresa&id=<?php echo $row['id_empresa']; ?>" 
                                           class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="index.php?action=eliminar_empresa&id=<?php echo $row['id_empresa']; ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('¿Está seguro de eliminar esta empresa?')"
                                           data-bs-toggle="tooltip" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'views/layouts/footer.php'; ?>