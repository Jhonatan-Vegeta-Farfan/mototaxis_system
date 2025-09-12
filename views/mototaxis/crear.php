<?php 
// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Recuperar datos del formulario si existen en sesión
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : array();
$duplicate_warning = isset($_SESSION['duplicate_warning']) ? $_SESSION['duplicate_warning'] : false;

// Limpiar datos de sesión después de usarlos
if (isset($_SESSION['form_data'])) {
    unset($_SESSION['form_data']);
}
if (isset($_SESSION['duplicate_warning'])) {
    unset($_SESSION['duplicate_warning']);
}
?>

<?php include_once 'views/layouts/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Registrar Nuevo Mototaxi</h4>
                        <a href="index.php?action=mototaxis" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Volver al Listado
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label='Close'></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label='Close'></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['info_message'])): ?>
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <?php echo $_SESSION['info_message']; unset($_SESSION['info_message']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label='Close'></button>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?action=crear_mototaxi" method="POST" onsubmit="return validarFormularioMototaxi()">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-section mb-4">
                                    <h5 class="section-title"><i class="fas fa-user me-2"></i>Información del Conductor</h5>
                                    
                                    <div class="mb-3">
                                        <label for="conductor_nombre_completo" class="form-label">Nombre Completo del Conductor</label>
                                        <input type="text" class="form-control" id="conductor_nombre_completo" name="conductor_nombre_completo" 
                                               value="<?php echo isset($form_data['conductor_nombre_completo']) ? $form_data['conductor_nombre_completo'] : ''; ?>" 
                                               required placeholder="Ej: Juan Pérez García">
                                        <div class="form-text">Puede registrar múltiples mototaxis para el mismo conductor.</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="dni" class="form-label">DNI del Conductor</label>
                                        <input type="text" class="form-control" id="dni" name="dni" 
                                               value="<?php echo isset($form_data['dni']) ? $form_data['dni'] : ''; ?>" 
                                               maxlength="8" required placeholder="Ej: 72358506">
                                        <div class="form-text">Ingrese los 8 dígitos del DNI.</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="direccion" class="form-label">Dirección del Conductor</label>
                                        <textarea class="form-control" id="direccion" name="direccion" rows="3" 
                                                  placeholder="Ej: Av. Libertad 123, Huanta"><?php echo isset($form_data['direccion']) ? $form_data['direccion'] : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-section mb-4">
                                    <h5 class="section-title"><i class="fas fa-motorcycle me-2"></i>Información del Mototaxi</h5>
                                    
                                    <div class="mb-3">
                                        <label for="numero_asignado" class="form-label">Número Asignado</label>
                                        <input type="number" class="form-control" id="numero_asignado" name="numero_asignado" 
                                               value="<?php echo isset($form_data['numero_asignado']) ? $form_data['numero_asignado'] : $next_number; ?>" 
                                               min="1" required>
                                        <div class="form-text">Número secuencial. El siguiente disponible es: <?php echo $next_number; ?></div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="placa_rodaje" class="form-label">Placa de Rodaje</label>
                                        <input type="text" class="form-control" id="placa_rodaje" name="placa_rodaje" 
                                               value="<?php echo isset($form_data['placa_rodaje']) ? $form_data['placa_rodaje'] : ''; ?>" 
                                               required placeholder="Ej: ABC-123">
                                        <div class="form-text">Cada mototaxi debe tener una placa única.</div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="anio_fabricacion" class="form-label">Año de Fabricación</label>
                                                <input type="number" class="form-control" id="anio_fabricacion" name="anio_fabricacion" 
                                                       value="<?php echo isset($form_data['anio_fabricacion']) ? $form_data['anio_fabricacion'] : ''; ?>" 
                                                       min="2000" max="<?php echo date('Y'); ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="marca" class="form-label">Marca</label>
                                                <input type="text" class="form-control" id="marca" name="marca" 
                                                       value="<?php echo isset($form_data['marca']) ? $form_data['marca'] : ''; ?>" 
                                                       required placeholder="Ej: Honda">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="color" class="form-label">Color</label>
                                                <input type="text" class="form-control" id="color" name="color" 
                                                       value="<?php echo isset($form_data['color']) ? $form_data['color'] : ''; ?>" 
                                                       required placeholder="Ej: Rojo">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-section mb-4">
                                    <h5 class="section-title"><i class="fas fa-cogs me-2"></i>Especificaciones Técnicas</h5>
                                    
                                    <div class="mb-3">
                                        <label for="numero_motor" class="form-label">Número de Motor</label>
                                        <input type="text" class="form-control" id="numero_motor" name="numero_motor" 
                                               value="<?php echo isset($form_data['numero_motor']) ? $form_data['numero_motor'] : ''; ?>" 
                                               required placeholder="Ej: MT123456789">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="tipo_motor" class="form-label">Tipo de Motor</label>
                                        <input type="text" class="form-control" id="tipo_motor" name="tipo_motor" 
                                               value="<?php echo isset($form_data['tipo_motor']) ? $form_data['tipo_motor'] : ''; ?>" 
                                               required placeholder="Ej: 4 Tiempos, 125cc">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="serie" class="form-label">Serie</label>
                                        <input type="text" class="form-control" id="serie" name="serie" 
                                               value="<?php echo isset($form_data['serie']) ? $form_data['serie'] : ''; ?>" 
                                               required placeholder="Ej: CH123456789">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-section mb-4">
                                    <h5 class="section-title"><i class="fas fa-building me-2"></i>Información de Registro</h5>
                                    
                                    <div class="mb-3">
                                        <label for="id_empresa" class="form-label">Empresa</label>
                                        <select class="form-select" id="id_empresa" name="id_empresa" required>
                                            <option value="">Seleccione una empresa</option>
                                            <?php foreach ($empresas_data as $empresa): ?>
                                                <option value="<?php echo $empresa['id_empresa']; ?>" 
                                                    <?php if (isset($form_data['id_empresa']) && $form_data['id_empresa'] == $empresa['id_empresa']) echo 'selected'; ?>>
                                                    <?php echo $empresa['razon_social']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="fecha_registro" class="form-label">Fecha de Registro</label>
                                        <input type="date" class="form-control" id="fecha_registro" name="fecha_registro" 
                                               value="<?php echo isset($form_data['fecha_registro']) ? $form_data['fecha_registro'] : date('Y-m-d'); ?>" required>
                                    </div>
                                    
                                    <?php if ($duplicate_warning || isset($_GET['confirm'])): ?>
                                        <div class="alert alert-warning">
                                            <h5><i class="fas fa-exclamation-triangle me-2"></i>¡Advertencia!</h5>
                                            <p>La placa ingresada ya está registrada en el sistema.</p>
                                            <p>¿Está seguro de que desea continuar con el registro?</p>
                                            
                                            <div class="form-check mb-3">
                                                <input type="checkbox" class="form-check-input" id="confirm_duplicate" name="confirm_duplicate" required>
                                                <label class="form-check-label" for="confirm_duplicate">Sí, estoy seguro de registrar a pesar de la duplicación</label>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <a href="index.php?action=mototaxis" class="btn btn-secondary btn-lg me-3">
                                <i class="fas fa-times me-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-1"></i> Registrar Mototaxi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'views/layouts/footer.php'; ?>