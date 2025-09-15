<?php include_once 'views/layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i>Editar Usuario</h5>
            </div>
            <div class="card-body">
                <form action="index.php?action=editar_usuario&id=<?php echo $model->id_usuario; ?>" method="POST">
                    <?php if(isset($error_message)): ?>
                        <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            <?php echo $error_message; ?>
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre_completo" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" 
                                   value="<?php echo $model->nombre_completo; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label">Nombre de Usuario <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?php echo $model->username; ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo $model->email; ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <div class="form-text">Dejar en blanco si no desea cambiar la contraseña</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="confirm_password" class="form-label">Confirmar Nueva Contraseña</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="rol" class="form-label">Rol <span class="text-danger">*</span></label>
                            <select class="form-select" id="rol" name="rol" required>
                                <option value="admin" <?php echo $model->rol == 'admin' ? 'selected' : ''; ?>>Administrador</option>
                                <option value="usuario" <?php echo $model->rol == 'usuario' ? 'selected' : ''; ?>>Usuario</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Estado</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" id="activo" name="activo" 
                                       <?php echo $model->activo ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="activo">Activo</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="index.php?action=usuarios" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Actualizar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Validación de confirmación de contraseña
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    
    form.addEventListener('submit', function(e) {
        if (password.value !== confirmPassword.value) {
            e.preventDefault();
            alert('Las contraseñas no coinciden. Por favor verifique.');
            confirmPassword.focus();
        }
    });
});
</script>

<?php include_once 'views/layouts/footer.php'; ?>