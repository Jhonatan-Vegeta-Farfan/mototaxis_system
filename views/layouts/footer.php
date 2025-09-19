    </main>

    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Municipalidad Provincial de Huanta</h5>
                    <p>Sistema de Gestión de Mototaxis</p>
                </div>
                <div class="col-md-6 text-end">
                    <p>&copy; <?php echo date('Y'); ?> - Todos los derechos reservados</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>

    <!-- Custom JS -->
    <script src="assets/js/script.js"></script>
    
    <script>
    // Inicializar DataTables
    $(document).ready(function() {
        $('#tabla-usuarios, #tabla-clientes-api, #tabla-tokens-api').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json'
            },
            responsive: true,
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]]
        });
        
        // Inicializar tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
    </script>
</body>
</html>