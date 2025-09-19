<?php include_once 'views/layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Crear Nuevo Token API</h5>
            </div>
            <div class="card-body">
                <form action="index.php?action=crear_token_api" method="POST">
                    <?php if(isset($error_message)): ?>
                        <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            <?php echo $error_message; ?>
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="id_client_api" class="form-label">Cliente API <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_client_api" name="id_client_api" required>
                            <option value="">Seleccionar Cliente</option>
                            <?php while ($client = $clients->fetch(PDO::FETCH_ASSOC)): ?>
                                <option value="<?php echo $client['id']; ?>">
                                    <?php echo $client['razon_social'] . ' (' . $client['ruc'] . ')'; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="index.php?action=tokens_api" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Generar Token
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once 'views/layouts/footer.php'; ?>