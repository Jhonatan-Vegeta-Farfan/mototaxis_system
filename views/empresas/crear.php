<?php include_once 'views/layouts/header.php'; ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fas fa-plus me-2"></i>Crear Nueva Empresa</h4>
                <a href="index.php?action=empresas" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            </div>
            <div class="card-body">
                <form action="index.php?action=crear_empresa" method="POST" onsubmit="return validarFormularioEmpresa()">
                    <div class="mb-3">
                        <label for="razon_social" class="form-label">Razón Social</label>
                        <input type="text" class="form-control" id="razon_social" name="razon_social" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="ruc" class="form-label">RUC</label>
                        <input type="text" class="form-control" id="ruc" name="ruc" maxlength="11" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="representante_legal" class="form-label">Representante Legal</label>
                        <input type="text" class="form-control" id="representante_legal" name="representante_legal" required>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="index.php?action=empresas" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once 'views/layouts/footer.php'; ?>