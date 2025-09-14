<?php include_once 'views/layouts/header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="index.php?action=dashboard" class="btn btn-secondary btn-sm me-2">
                    <i class="fas fa-arrow-left me-1"></i> Volver al Panel
                </a>
                <h2 class="mb-0 d-inline-block"><i class="fas fa-users me-2 text-primary"></i>Gestión de Usuarios</h2>
            </div>
            <a href="index.php?action=crear_usuario" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Nuevo Usuario
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

        <!-- Tarjetas para vista móvil -->
        <div class="d-block d-md-none">
            <?php 
            $usuarios_data = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $usuarios_data[] = $row;
            }
            
            $count = 0;
            foreach ($usuarios_data as $row): 
                $count++;
            ?>
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><?php echo $row['nombre_completo']; ?></h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">Usuario:</small>
                            <p class="mb-1"><?php echo $row['username']; ?></p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Rol:</small>
                            <p class="mb-1">
                                <span class="badge bg-<?php echo $row['rol'] == 'admin' ? 'danger' : 'info'; ?>">
                                    <?php echo ucfirst($row['rol']); ?>
                                </span>
                            </p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Email:</small>
                            <p class="mb-1"><?php echo $row['email']; ?></p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Estado:</small>
                            <p class="mb-1">
                                <span class="badge bg-<?php echo $row['activo'] ? 'success' : 'secondary'; ?>">
                                    <?php echo $row['activo'] ? 'Activo' : 'Inactivo'; ?>
                                </span>
                            </p>
                        </div>
                        <div class="col-12">
                            <small class="text-muted">Registro:</small>
                            <p class="mb-2"><?php echo date('d/m/Y', strtotime($row['fecha_creacion'])); ?></p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <a href="index.php?action=editar_usuario&id=<?php echo $row['id_usuario']; ?>" 
                           class="btn btn-sm btn-warning me-1">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="index.php?action=eliminar_usuario&id=<?php echo $row['id_usuario']; ?>" 
                           class="btn btn-sm btn-danger" 
                           onclick="return confirm('¿Está seguro de eliminar este usuario?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if ($count == 0): ?>
            <div class="card shadow-sm mb-3">
                <div class="card-body text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay usuarios registrados</h5>
                    <p class="text-muted">Agregue nuevos usuarios para comenzar</p>
                    <a href="index.php?action=crear_usuario" class="btn btn-primary mt-2">
                        <i class="fas fa-plus me-1"></i> Crear Usuario
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Tabla para vista escritorio -->
        <div class="card shadow-sm d-none d-md-block">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="tablaUsuarios">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nombre Completo</th>
                                <th>Usuario</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Fecha Registro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Reiniciar el puntero del resultado
                            $stmt->execute();
                            $count = 0;
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): 
                                $count++;
                            ?>
                            <tr>
                                <td><?php echo $row['id_usuario']; ?></td>
                                <td><?php echo $row['nombre_completo']; ?></td>
                                <td><?php echo $row['username']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $row['rol'] == 'admin' ? 'danger' : 'info'; ?>">
                                        <?php echo ucfirst($row['rol']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo $row['activo'] ? 'success' : 'secondary'; ?>">
                                        <?php echo $row['activo'] ? 'Activo' : 'Inactivo'; ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($row['fecha_creacion'])); ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="index.php?action=editar_usuario&id=<?php echo $row['id_usuario']; ?>" 
                                           class="btn btn-sm btn-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="index.php?action=eliminar_usuario&id=<?php echo $row['id_usuario']; ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('¿Está seguro de eliminar este usuario?')" 
                                           title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if ($count == 0): ?>
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No hay usuarios registrados</h5>
                                    <p class="text-muted">Agregue nuevos usuarios para comenzar</p>
                                    <a href="index.php?action=crear_usuario" class="btn btn-primary mt-2">
                                        <i class="fas fa-plus me-1"></i> Crear Usuario
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