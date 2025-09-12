<?php 
// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Obtener el término de búsqueda
$termino = isset($_GET['q']) ? htmlspecialchars(trim($_GET['q'])) : '';
?>
<?php include_once 'views/layouts/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <a href="index.php?action=dashboard" class="btn btn-secondary btn-sm me-2">
                        <i class="fas fa-arrow-left me-1"></i> Volver al Panel
                    </a>
                    <h2 class="mb-0 d-inline-block"><i class="fas fa-search me-2 text-primary"></i>Resultados de Búsqueda</h2>
                </div>
                <div>
                    <a href="index.php?action=mototaxis" class="btn btn-primary me-2">
                        <i class="fas fa-list me-1"></i> Ver Todos los Mototaxis
                    </a>
                    <a href="index.php?action=empresas" class="btn btn-success">
                        <i class="fas fa-building me-1"></i> Ver Empresas
                    </a>
                </div>
            </div>

            <!-- Tarjeta de resumen de búsqueda -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="card-title mb-1">Término buscado: "<?php echo $termino; ?>"</h5>
                            <p class="card-text text-muted mb-0">
                                Se encontraron <?php echo $total_resultados; ?> resultado(s) - 
                                <?php echo $total_resultados_mototaxis; ?> mototaxi(s) y 
                                <?php echo $total_resultados_empresas; ?> empresa(s)
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <form action="index.php" method="GET" class="d-inline">
                                <input type="hidden" name="action" value="buscar">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="q" placeholder="Nueva búsqueda..." value="<?php echo $termino; ?>">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resultados de Mototaxis -->
            <?php if ($total_resultados_mototaxis > 0): ?>
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-motorcycle me-2"></i>Mototaxis Encontrados</h5>
                    <span class="badge bg-light text-primary"><?php echo $total_resultados_mototaxis; ?> mototaxis</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Número</th>
                                    <th>Conductor</th>
                                    <th>DNI</th>
                                    <th>Placa</th>
                                    <th>Marca/Modelo</th>
                                    <th>Motor</th>
                                    <th>Empresa</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $resultados_mototaxis->fetch(PDO::FETCH_ASSOC)): ?>
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
                                    </td>
                                    <td>
                                        <small><?php echo $row['numero_motor']; ?></small>
                                        <br><small class="text-muted">Tipo: <?php echo $row['tipo_motor']; ?></small>
                                    </td>
                                    <td>
                                        <?php echo !empty($row['razon_social']) ? $row['razon_social'] : 'Sin empresa'; ?>
                                        <?php if (!empty($row['ruc_empresa'])): ?>
                                            <br><small class="text-muted">RUC: <?php echo $row['ruc_empresa']; ?></small>
                                        <?php endif; ?>
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
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Resultados de Empresas -->
            <?php if ($total_resultados_empresas > 0): ?>
            <div class="card shadow">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-building me-2"></i>Empresas Encontradas</h5>
                    <span class="badge bg-light text-success"><?php echo $total_resultados_empresas; ?> empresas</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
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
                                while ($row = $resultados_empresas->fetch(PDO::FETCH_ASSOC)): 
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
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Mensaje cuando no hay resultados -->
            <?php if ($total_resultados == 0): ?>
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No se encontraron resultados</h4>
                    <p class="text-muted">Intenta con otros términos de búsqueda o verifica la ortografía.</p>
                    <a href="index.php?action=dashboard" class="btn btn-primary mt-2">
                        <i class="fas fa-home me-1"></i> Volver al Panel Principal
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include_once 'views/layouts/footer.php'; ?>