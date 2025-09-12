<?php include_once 'views/layouts/header.php'; ?>

<?php
// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="container mt-4">
    <h2>Editar Mototaxi</h2>
    
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>
    
    <form action="index.php?action=editar_mototaxi&id=<?php echo $this->model->id_mototaxi; ?>" method="post">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="numero_asignado">Número Asignado</label>
                    <input type="text" class="form-control" id="numero_asignado" name="numero_asignado" 
                           value="<?php echo $this->model->numero_asignado; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="conductor_nombre_completo">Nombre del Conductor</label>
                    <input type="text" class="form-control" id="conductor_nombre_completo" name="conductor_nombre_completo" 
                           value="<?php echo $this->model->conductor_nombre_completo; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="dni">DNI</label>
                    <input type="text" class="form-control" id="dni" name="dni" 
                           value="<?php echo $this->model->dni; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <textarea class="form-control" id="direccion" name="direccion" rows="3"><?php echo $this->model->direccion; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="placa_rodaje">Placa de Rodaje</label>
                    <input type="text" class="form-control" id="placa_rodaje" name="placa_rodaje" 
                           value="<?php echo $this->model->placa_rodaje; ?>" required>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="anio_fabricacion">Año de Fabricación</label>
                    <input type="number" class="form-control" id="anio_fabricacion" name="anio_fabricacion" 
                           value="<?php echo $this->model->anio_fabricacion; ?>">
                </div>
                
                <div class="form-group">
                    <label for="marca">Marca</label>
                    <input type="text" class="form-control" id="marca" name="marca" 
                           value="<?php echo $this->model->marca; ?>">
                </div>
                
                <div class="form-group">
                    <label for="numero_motor">Número de Motor</label>
                    <input type="text" class="form-control" id="numero_motor" name="numero_motor" 
                           value="<?php echo $this->model->numero_motor; ?>">
                </div>
                
                <div class="form-group">
                    <label for="tipo_motor">Tipo de Motor</label>
                    <input type="text" class="form-control" id="tipo_motor" name="tipo_motor" 
                           value="<?php echo $this->model->tipo_motor; ?>">
                </div>
                
                <div class="form-group">
                    <label for="serie">Serie</label>
                    <input type="text" class="form-control" id="serie" name="serie" 
                           value="<?php echo $this->model->serie; ?>">
                </div>
                
                <div class="form-group">
                    <label for="color">Color</label>
                    <input type="text" class="form-control" id="color" name="color" 
                           value="<?php echo $this->model->color; ?>">
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="fecha_registro">Fecha de Registro</label>
                    <input type="date" class="form-control" id="fecha_registro" name="fecha_registro" 
                           value="<?php echo $this->model->fecha_registro; ?>" required>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="id_empresa">Empresa</label>
                    <select class="form-control" id="id_empresa" name="id_empresa" required>
                        <option value="">Seleccionar Empresa</option>
                        <?php 
                        $empresas = $this->model->getEmpresas();
                        while ($row = $empresas->fetch(PDO::FETCH_ASSOC)): 
                        ?>
                            <option value="<?php echo $row['id_empresa']; ?>" 
                                <?php if ($this->model->id_empresa == $row['id_empresa']) echo 'selected'; ?>>
                                <?php echo $row['razon_social']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php include_once 'views/layouts/footer.php'; ?>