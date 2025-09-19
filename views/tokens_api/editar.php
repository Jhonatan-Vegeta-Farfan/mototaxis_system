<?php include_once 'views/layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Editar Token API</h5>
            </div>
            <div class="card-body">
                <form action="index.php?action=editar_token_api&id=<?php echo $model->id; ?>" method="POST">
                    <?php if(isset($error_message)): ?>
                        <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            <?php echo $error_message; ?>
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label">Token</label>
                        <input type="text" class="form-control" value="<?php echo $model->token; ?>" readonly>
                        <div class="form-text">El token no se puede modificar. Para cambiar el token, debe eliminar y crear uno nuevo.</div>
                    </div>

                    <div class="mb-3">
                        <label for="id_client_api" class="form-label">Cliente API <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_client_api" name="id_client_api" required>
                            <option value="">Seleccionar Cliente</option>
                            <?php 
                            $clients->execute();
                            while ($client = $clients->fetch(PDO::FETCH_ASSOC)): 
                            ?>
                                <option value="<?php echo $client['id']; ?>" 
                                    <?php echo $model->id_client_api == $client['id'] ? 'selected' : ''; ?>>
                                    <?php echo $client['razon_social'] . ' (' . $client['ruc'] . ')'; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="estado" name="estado" 
                                   <?php echo $model->estado ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="estado">Activo</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="index.php?action=tokens_api" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Actualizar Token
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once 'views/layouts/footer.php'; ?>