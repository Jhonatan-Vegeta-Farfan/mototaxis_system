// Inicializar DataTables con configuración responsiva
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    
    // Inicializar DataTables si existen en la página
    if ($.fn.DataTable.isDataTable('#tabla-empresas')) {
        $('#tabla-empresas').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json'
            },
            responsive: true,
            order: [[0, 'asc']],
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]]
        });
    }
    
    if ($.fn.DataTable.isDataTable('#tabla-mototaxis')) {
        $('#tabla-mototaxis').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json'
            },
            responsive: true,
            order: [[0, 'asc']],
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]]
        });
    }
    
    // Inicializar popovers
    $('[data-bs-toggle="popover"]').popover();
    
    // Ajustar altura de las tarjetas del dashboard
    ajustarAlturaTarjetas();
});

// Validación de formularios
function validarFormularioEmpresa() {
    const razonSocial = document.getElementById('razon_social').value;
    const ruc = document.getElementById('ruc').value;
    const representante = document.getElementById('representante_legal').value;
    
    if (razonSocial.trim() === '') {
        mostrarAlerta('Por favor ingrese la razón social', 'danger');
        return false;
    }
    
    if (ruc.trim() === '' || ruc.length !== 11 || isNaN(ruc)) {
        mostrarAlerta('El RUC debe tener 11 dígitos numéricos', 'danger');
        return false;
    }
    
    if (representante.trim() === '') {
        mostrarAlerta('Por favor ingrese el nombre del representante legal', 'danger');
        return false;
    }
    
    return true;
}

function validarFormularioMototaxi() {
    const dni = document.getElementById('dni').value;
    const placa = document.getElementById('placa_rodaje').value;
    const anio = document.getElementById('anio_fabricacion').value;
    const numero = document.getElementById('numero_asignado').value;
    
    if (dni.length !== 8 || isNaN(dni)) {
        mostrarAlerta('El DNI debe tener 8 dígitos numéricos', 'danger');
        return false;
    }
    
    if (placa.trim() === '') {
        mostrarAlerta('Por favor ingrese la placa de rodaje', 'danger');
        return false;
    }
    
    if (numero <= 0 || isNaN(numero)) {
        mostrarAlerta('El número asignado debe ser un valor numérico positivo', 'danger');
        return false;
    }
    
    const currentYear = new Date().getFullYear();
    if (anio < 2000 || anio > currentYear) {
        mostrarAlerta(`El año de fabricación debe estar entre 2000 y ${currentYear}`, 'danger');
        return false;
    }
    
    return true;
}

// Función para mostrar alertas bonitas
function mostrarAlerta(mensaje, tipo) {
    // Crear elemento de alerta
    const alerta = document.createElement('div');
    alerta.className = `alert alert-${tipo} alert-dismissible fade show`;
    alerta.innerHTML = `
        <i class="fas ${tipo === 'danger' ? 'fa-exclamation-triangle' : 'fa-check-circle'} me-2"></i>
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Insertar al principio del main
    const main = document.querySelector('main');
    main.insertBefore(alerta, main.firstChild);
    
    // Auto-eliminar después de 5 segundos
    setTimeout(() => {
        if (alerta.parentNode) {
            const bsAlert = new bootstrap.Alert(alerta);
            bsAlert.close();
        }
    }, 5000);
}

// Confirmación antes de eliminar
function confirmarEliminacion() {
    return confirm('¿Está seguro de que desea eliminar este registro? Esta acción no se puede deshacer.');
}

// Mejorar la experiencia táctil en dispositivos móviles
if ('ontouchstart' in window) {
    document.documentElement.classList.add('touch-device');
    
    // Mejorar feedback táctil para botones
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(btn => {
        btn.addEventListener('touchstart', function() {
            this.classList.add('btn-active');
        });
        
        btn.addEventListener('touchend', function() {
            this.classList.remove('btn-active');
        });
    });
}

// Función para alternar el menú en móviles
function toggleMenu() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('active');
}

// Inicializar elementos cuando el DOM esté listo
$(document).ready(function() {
    // Ajustar altura de las tarjetas del dashboard
    ajustarAlturaTarjetas();
    
    // Efecto de animación para las cards
    $('.card').hover(
        function() {
            $(this).css('transform', 'translateY(-8px)');
        },
        function() {
            $(this).css('transform', 'translateY(0)');
        }
    );
    
    // Animación para botones
    $('.btn').hover(
        function() {
            $(this).css('transform', 'translateY(-3px)');
        },
        function() {
            $(this).css('transform', 'translateY(0)');
        }
    );
});

// Ajustar altura de las tarjetas para que coincidan
function ajustarAlturaTarjetas() {
    if ($(window).width() > 768) {
        $('.card-stats').matchHeight();
    }
}

// Reajustar cuando cambie el tamaño de la ventana
$(window).resize(function() {
    ajustarAlturaTarjetas();
});

// Función para buscar conductores
function buscarConductor() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("buscarConductor");
    filter = input.value.toUpperCase();
    table = document.getElementById("tabla-mototaxis");
    tr = table.getElementsByTagName("tr");
    
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[1]; // Columna del conductor
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

// Función para mostrar modal de confirmación de duplicados
function mostrarConfirmacionDuplicado(mensaje) {
    const modal = new bootstrap.Modal(document.getElementById('modalConfirmacion'));
    document.getElementById('modalMensaje').innerText = mensaje;
    modal.show();
    
    return new Promise((resolve) => {
        document.getElementById('confirmarDuplicado').addEventListener('click', function() {
            resolve(true);
            modal.hide();
        });
        
        document.getElementById('cancelarDuplicado').addEventListener('click', function() {
            resolve(false);
            modal.hide();
        });
    });
}