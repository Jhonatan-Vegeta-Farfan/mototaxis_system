<?php include_once 'views/layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Editar Cliente API</h5>
            </div>
            <div class="card-body">
                <form action="index.php?action=editar_client_api&id=<?php echo $model->id; ?>" method="POST">
                    <?php if(isset($error_message)): ?>
                        <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            <?php echo $error_message; ?>
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="ruc" class="form-label">RUC <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="ruc" name="ruc" 
                               value="<?php echo $model->ruc; ?>" maxlength="11" required>
                    </div>

                    <div class="mb-3">
                        <label for="razon_social" class="form-label">Razón Social <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="razon_social" name="razon_social" 
                               value="<?php echo $model->razon_social; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" 
                               value="<?php echo $model->telefono; ?>" maxlength="15">
                    </div>

                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="correo" name="correo" 
                               value="<?php echo $model->correo; ?>">
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="estado" name="estado" 
                                   <?php echo $model->estado ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="estado">Activo</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="index.php?action=client_api" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Actualizar Cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once 'views/layouts/footer.php'; ?>