<?php include_once 'views/layouts/header.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="index.php?action=dashboard" class="btn btn-secondary btn-sm me-2">
                    <i class="fas fa-arrow-left me-1"></i> Volver al Panel
                </a>
                <h2 class="mb-0 d-inline-block"><i class="fas fa-motorcycle me-2 text-primary"></i>Gestión de Mototaxis</h2>
            </div>
            <a href="index.php?action=crear_mototaxi" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Nuevo Mototaxi
            </a>
        </div>

        <?php
        if(isset($_GET['message'])) {
            $message = $_GET['message'];
            $alertType = (strpos($message, 'Error') !== false) ? 'danger' : 'success';
            echo "<div class='alert alert-$alertType alert-dismissible fade show' role='alert'>
                    $message
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                  </div>";
        }
        ?>

        <!-- Tarjetas para vista móvil -->
        <div class="d-block d-md-none">
            <?php 
            // Obtener todos los datos y ordenarlos por número asignado
            $mototaxis_data = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $mototaxis_data[] = $row;
            }
            
            // Ordenar por número asignado (numéricamente)
            usort($mototaxis_data, function($a, $b) {
                return (int)$a['numero_asignado'] - (int)$b['numero_asignado'];
            });
            
            $count = 0;
            foreach ($mototaxis_data as $row): 
                $count++;
            ?>
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">#<?php echo str_pad($row['numero_asignado'], 2, '0', STR_PAD_LEFT); ?> - <?php echo $row['conductor_nombre_completo']; ?></h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">Placa:</small>
                            <p class="mb-1"><?php echo $row['placa_rodaje']; ?></p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">DNI:</small>
                            <p class="mb-1"><?php echo $row['dni']; ?></p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Marca:</small>
                            <p class="mb-1"><?php echo $row['marca']; ?></p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Año:</small>
                            <p class="mb-1"><?php echo $row['anio_fabricacion']; ?></p>
                        </div>
                        <div class="col-12">
                            <small class="text-muted">Empresa:</small>
                            <p class="mb-2"><?php echo $row['razon_social']; ?></p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-sm btn-info me-1" data-bs-toggle="collapse" 
                                data-bs-target="#detalles-<?php echo $row['id_mototaxi']; ?>" 
                                aria-expanded="false" aria-controls="detalles-<?php echo $row['id_mototaxi']; ?>">
                            <i class="fas fa-eye"></i>
                        </button>
                        <a href="index.php?action=editar_mototaxi&id=<?php echo $row['id_mototaxi']; ?>" 
                           class="btn btn-sm btn-warning me-1">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="index.php?action=eliminar_mototaxi&id=<?php echo $row['id_mototaxi']; ?>" 
                           class="btn btn-sm btn-danger" 
                           onclick="return confirm('¿Está seguro de eliminar este mototaxi?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                    
                    <!-- Detalles expandibles -->
                    <div class="collapse mt-3" id="detalles-<?php echo $row['id_mototaxi']; ?>">
                        <div class="card card-body">
                            <h6 class="border-bottom pb-2">Detalles Completos</h6>
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Motor:</small>
                                    <p class="mb-1"><?php echo $row['numero_motor']; ?></p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Tipo Motor:</small>
                                    <p class="mb-1"><?php echo $row['tipo_motor']; ?></p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Serie:</small>
                                    <p class="mb-1"><?php echo $row['serie']; ?></p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Color:</small>
                                    <p class="mb-1"><?php echo $row['color']; ?></p>
                                </div>
                                <div class="col-12">
                                    <small class="text-muted">Dirección:</small>
                                    <p class="mb-1"><?php echo $row['direccion']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if ($count == 0): ?>
            <div class="card shadow-sm mb-3">
                <div class="card-body text-center">
                    <i class="fas fa-motorcycle fa-3x text-muted mb-3"></i>
                    <h5>No hay mototaxis registrados</h5>
                    <p class="text-muted">Comienza agregando tu primer mototaxi</p>
                    <a href="index.php?action=crear_mototaxi" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Agregar Mototaxi
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Tabla para vista desktop -->
        <div class="card shadow d-none d-md-block">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Listado de Mototaxis</h5>
                <span class="badge bg-light text-primary"><?php echo count($mototaxis_data); ?> registros</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tabla-mototaxis">
                        <thead>
                            <tr>
                                <th>Número</th>
                                <th>Conductor</th>
                                <th>DNI</th>
                                <th>Placa</th>
                                <th>Marca/Modelo</th>
                                <th>Motor</th>
                                <th>Color</th>
                                <th>Empresa</th>
                                <th>Registro</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            foreach ($mototaxis_data as $row): 
                            ?>
                            <tr>
                                <td>
                                    <span class="badge bg-primary"><?php echo str_pad($row['numero_asignado'], 2, '0', STR_PAD_LEFT); ?></span>
                                </td>
                                <td>
                                    <strong><?php echo $row['conductor_nombre_completo']; ?></strong>
                                    <br><small class="text-muted"><?php echo $row['direccion']; ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary"><?php echo $row['dni']; ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-info text-dark"><?php echo $row['placa_rodaje']; ?></span>
                                </td>
                                <td>
                                    <?php echo $row['marca']; ?> 
                                    <br><small class="text-muted">Año: <?php echo $row['anio_fabricacion']; ?></small>
                                    <br><small class="text-muted">Serie: <?php echo $row['serie']; ?></small>
                                </td>
                                <td>
                                    <small><?php echo $row['numero_motor']; ?></small>
                                    <br><small class="text-muted">Tipo: <?php echo $row['tipo_motor']; ?></small>
                                </td>
                                <td>
                                    <span class="badge" style="background-color: <?php echo obtenerColorHex($row['color']); ?>; color: white;">
                                        <?php echo $row['color']; ?>
                                    </span>
                                </td>
                                <td><?php echo $row['razon_social']; ?></td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        <?php echo date('d/m/Y', strtotime($row['fecha_registro'])); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="index.php?action=editar_mototaxi&id=<?php echo $row['id_mototaxi']; ?>" 
                                           class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="index.php?action=eliminar_mototaxi&id=<?php echo $row['id_mototaxi']; ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('¿Está seguro de eliminar este mototaxi?')"
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

<?php 
function obtenerColorHex($color) {
    $colores = [
        'rojo' => '#dc3545',
        'azul' => '#007bff',
        'verde' => '#28a745',
        'amarillo' => '#ffc107',
        'negro' => '#000000',
        'blanco' => '#ffffff',
        'gris' => '#6c757d',
        'naranja' => '#fd7e14'
    ];
    
    return $colores[strtolower($color)] ?? '#6c757d';
}
?>

<?php include_once 'views/layouts/footer.php'; ?>