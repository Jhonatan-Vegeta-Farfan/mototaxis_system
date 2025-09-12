<?php include_once 'views/layouts/header.php'; ?>

<div class="row">
    <!-- Sidebar/Navegación -->
    <div class="col-lg-3 col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-bars me-2"></i>Menú Principal</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <a href="index.php?action=dashboard" class="list-group-item list-group-item-action active">
                        <i class="fas fa-tachometer-alt me-2"></i> Panel de Control
                    </a>
                    <a href="index.php?action=mototaxis" class="list-group-item list-group-item-action">
                        <i class="fas fa-motorcycle me-2"></i> Gestión de Mototaxis
                    </a>
                    <a href="index.php?action=empresas" class="list-group-item list-group-item-action">
                        <i class="fas fa-building me-2"></i> Gestión de Empresas
                    </a>
                    <a href="index.php?action=crear_mototaxi" class="list-group-item list-group-item-action">
                        <i class="fas fa-plus-circle me-2"></i> Nuevo Mototaxi
                    </a>
                    <a href="index.php?action=crear_empresa" class="list-group-item list-group-item-action">
                        <i class="fas fa-plus-square me-2"></i> Nueva Empresa
                    </a>
                </div>
            </div>
        </div>

        <!-- Búsqueda rápida en sidebar -->
        <div class="card shadow mb-4">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="fas fa-search me-2"></i>Búsqueda Rápida</h6>
            </div>
            <div class="card-body">
                <form action="index.php" method="GET">
                    <input type="hidden" name="action" value="buscar">
                    <div class="mb-3">
                        <label for="busquedaSidebar" class="form-label">Buscar mototaxis</label>
                        <input type="text" class="form-control" id="busquedaSidebar" name="q" 
                               placeholder="Nombre, DNI, placa..." required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Buscar
                    </button>
                </form>
            </div>
        </div>

        <!-- Estadísticas rápidas -->
        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Estadísticas</h6>
            </div>
            <div class="card-body">
                <?php
                $mototaxi = new Mototaxi($db);
                $empresa = new Empresa($db);
                
                // Usar los nuevos métodos para obtener los totales
                $totalMototaxis = $mototaxi->countAll();
                $totalEmpresas = $empresa->countAll();
                $registrosHoyMototaxis = $mototaxi->countRegistrosHoy();
                $registrosHoyEmpresas = $empresa->countRegistrosHoy();
                $totalRegistrosHoy = $registrosHoyMototaxis + $registrosHoyEmpresas;
                $mototaxisActivos = $mototaxi->countActivos();
                ?>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Mototaxis registrados:</span>
                    <span class="badge bg-primary"><?php echo $totalMototaxis; ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Empresas registradas:</span>
                    <span class="badge bg-success"><?php echo $totalEmpresas; ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Registros hoy:</span>
                    <span class="badge bg-info"><?php echo $totalRegistrosHoy; ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Mototaxis activos:</span>
                    <span class="badge bg-warning"><?php echo $mototaxisActivos; ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="col-lg-9 col-md-8">
        <!-- Header del dashboard -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0"><i class="fas fa-tachometer-alt me-2 text-primary"></i>Panel de Control</h2>
            <div class="btn-group">
                <a href="index.php?action=crear_mototaxi" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Nuevo Mototaxi
                </a>
                <a href="index.php?action=crear_empresa" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i> Nueva Empresa
                </a>
            </div>
        </div>

        <!-- Buscador destacado en dashboard -->
        <div class="card shadow-lg mb-4 border-0 bg-gradient-primary text-white">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="card-title mb-2"><i class="fas fa-search me-2"></i>Búsqueda Rápida</h3>
                        <p class="card-text mb-0">Encuentra mototaxis por nombre, DNI, placa, número de motor o empresa</p>
                    </div>
                    <div class="col-md-4">
                        <form action="index.php" method="GET" class="mt-3 mt-md-0">
                            <input type="hidden" name="action" value="buscar">
                            <div class="input-group input-group-lg">
                                <input type="text" class="form-control" name="q" placeholder="Ej: Juan Pérez, 12345678, ABC123..." required>
                                <button class="btn btn-light" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php if(isset($_GET['message'])): ?>
            <div class='alert alert-<?php echo (strpos($_GET['message'], 'Error') !== false) ? 'danger' : 'success'; ?> alert-dismissible fade show' role='alert'>
                <?php echo $_GET['message']; ?>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
        <?php endif; ?>

        <!-- Tarjetas de resumen -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Mototaxis</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalMototaxis; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-motorcycle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Empresas</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalEmpresas; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-building fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Registros Hoy</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalRegistrosHoy; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Activos</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $mototaxisActivos; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Últimos mototaxis registrados -->
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-motorcycle me-2"></i>Últimos Mototaxis Registrados</h6>
                <a href="index.php?action=mototaxis" class="btn btn-sm btn-light">Ver Todos</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Número</th>
                                <th>Conductor</th>
                                <th>Placa</th>
                                <th>Empresa</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $mototaxis = $mototaxi->read();
                            $mototaxis_data = [];
                            while ($row = $mototaxis->fetch(PDO::FETCH_ASSOC)) {
                                $mototaxis_data[] = $row;
                            }
                            
                            // Ordenar por número asignado (numéricamente)
                            usort($mototaxis_data, function($a, $b) {
                                return (int)$a['numero_asignado'] - (int)$b['numero_asignado'];
                            });
                            
                            $count = 0;
                            foreach ($mototaxis_data as $row) {
                                if ($count >= 5) break;
                                $count++;
                            ?>
                            <tr>
                                <td><span class="badge bg-primary"><?php echo str_pad($row['numero_asignado'], 2, '0', STR_PAD_LEFT); ?></span></td>
                                <td><?php echo $row['conductor_nombre_completo']; ?></td>
                                <td><span class="badge bg-info text-dark"><?php echo $row['placa_rodaje']; ?></span></td>
                                <td>
                                    <?php
                                    if (!empty($row['id_empresa'])) {
                                        $empresaTemp = new Empresa($db);
                                        $empresaTemp->id_empresa = $row['id_empresa'];
                                        if ($empresaTemp->readOne()) {
                                            echo $empresaTemp->razon_social;
                                        } else {
                                            echo "Sin empresa";
                                        }
                                    } else {
                                        echo "Sin empresa";
                                    }
                                    ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="index.php?action=editar_mototaxi&id=<?php echo $row['id_mototaxi']; ?>" 
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php if ($count == 0): ?>
                            <tr>
                                <td colspan="5" class="text-center">No hay mototaxis registrados</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Últimas empresas registradas -->
        <div class="card shadow">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-building me-2"></i>Últimas Empresas Registradas</h6>
                <a href="index.php?action=empresas" class="btn btn-sm btn-light">Ver Todas</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Razón Social</th>
                                <th>RUC</th>
                                <th>Representante</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $empresas = $empresa->read();
                            $empresas_data = [];
                            while ($row = $empresas->fetch(PDO::FETCH_ASSOC)) {
                                $empresas_data[] = $row;
                            }
                            
                            $count = 0;
                            foreach ($empresas_data as $row) {
                                if ($count >= 5) break;
                                $count++;
                            ?>
                            <tr>
                                <td><?php echo $row['razon_social']; ?></td>
                                <td><span class="badge bg-secondary"><?php echo $row['ruc']; ?></span></td>
                                <td><?php echo $row['representante_legal']; ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="index.php?action=editar_empresa&id=<?php echo $row['id_empresa']; ?>" 
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php if ($count == 0): ?>
                            <tr>
                                <td colspan="4" class="text-center">No hay empresas registradas</td>
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