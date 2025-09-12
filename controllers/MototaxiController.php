<?php
class MototaxiController {
    private $model;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new Mototaxi($db);
        
        // Iniciar sesión si no está iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {
        $stmt = $this->model->read();
        include_once 'views/mototaxis/index.php';
    }

    public function crear() {
        $empresas = $this->model->getEmpresas();
        
        // Obtener el próximo número disponible
        $nextNumber = $this->model->getNextAvailableNumber();
        
        // Mostrar mensaje de error si existe
        if (isset($_SESSION['error_message'])) {
            $error_message = $_SESSION['error_message'];
            unset($_SESSION['error_message']);
        }
        
        // Mostrar mensaje de éxito si existe
        if (isset($_SESSION['success_message'])) {
            $success_message = $_SESSION['success_message'];
            unset($_SESSION['success_message']);
        }
        
        if($_POST) {
            // Si no se proporciona número, usar el siguiente disponible
            $numero_asignado = !empty($_POST['numero_asignado']) ? $_POST['numero_asignado'] : $nextNumber;
            
            $this->model->numero_asignado = $numero_asignado;
            $this->model->conductor_nombre_completo = $_POST['conductor_nombre_completo'];
            $this->model->dni = $_POST['dni'];
            $this->model->direccion = $_POST['direccion'];
            $this->model->placa_rodaje = $_POST['placa_rodaje'];
            $this->model->anio_fabricacion = $_POST['anio_fabricacion'];
            $this->model->marca = $_POST['marca'];
            $this->model->numero_motor = $_POST['numero_motor'];
            $this->model->tipo_motor = $_POST['tipo_motor'];
            $this->model->serie = $_POST['serie'];
            $this->model->color = $_POST['color'];
            $this->model->fecha_registro = $_POST['fecha_registro'];
            $this->model->id_empresa = $_POST['id_empresa'];
            
            // Verificar si ya existe la placa
            $placa_duplicada = $this->model->placaExists($_POST['placa_rodaje']);
            
            if ($placa_duplicada && !isset($_POST['confirm_duplicate'])) {
                // Guardar los datos del formulario en sesión
                $_SESSION['form_data'] = $_POST;
                $_SESSION['duplicate_warning'] = true;
                $_SESSION['error_message'] = "La placa {$_POST['placa_rodaje']} ya está registrada en el sistema.";
                
                header("Location: index.php?action=crear_mototaxi&confirm=1");
                exit();
            }
            
            if($this->model->create()) {
                $_SESSION['success_message'] = "Mototaxi creado correctamente.";
                
                // Verificar si el conductor ya tiene otros mototaxis
                $total_mototaxis = $this->model->countByConductor($_POST['dni']);
                if ($total_mototaxis > 1) {
                    $_SESSION['info_message'] = "El conductor {$_POST['conductor_nombre_completo']} ahora tiene $total_mototaxis mototaxis registrados.";
                }
                
                header("Location: index.php?action=mototaxis");
                exit();
            } else {
                // Guardar los datos del formulario en sesión para repoblarlo
                $_SESSION['form_data'] = $_POST;
                header("Location: index.php?action=crear_mototaxi&error=1");
                exit();
            }
        }
        
        // Cargar datos del formulario desde sesión si existen
        if (isset($_SESSION['form_data'])) {
            $form_data = $_SESSION['form_data'];
            unset($_SESSION['form_data']);
        }
        
        // Verificar si hay advertencia de duplicado
        $duplicate_warning = isset($_SESSION['duplicate_warning']) ? $_SESSION['duplicate_warning'] : false;
        if (isset($_SESSION['duplicate_warning'])) {
            unset($_SESSION['duplicate_warning']);
        }
        
        // Pasar empresas a la vista
        $empresas_data = $empresas->fetchAll(PDO::FETCH_ASSOC);
        
        // Pasar el próximo número a la vista
        $next_number = $nextNumber;
        
        include_once 'views/mototaxis/crear.php';
    }

    public function editar() {
        $this->model->id_mototaxi = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID no encontrado.');
        
        if(!$this->model->readOne()) {
            die('ERROR: Mototaxi no encontrado.');
        }
        
        $empresas = $this->model->getEmpresas();
        
        // Mostrar mensaje de error si existe
        if (isset($_SESSION['error_message'])) {
            $error_message = $_SESSION['error_message'];
            unset($_SESSION['error_message']);
        }
        
        if($_POST) {
            $this->model->numero_asignado = $_POST['numero_asignado'];
            $this->model->conductor_nombre_completo = $_POST['conductor_nombre_completo'];
            $this->model->dni = $_POST['dni'];
            $this->model->direccion = $_POST['direccion'];
            $this->model->placa_rodaje = $_POST['placa_rodaje'];
            $this->model->anio_fabricacion = $_POST['anio_fabricacion'];
            $this->model->marca = $_POST['marca'];
            $this->model->numero_motor = $_POST['numero_motor'];
            $this->model->tipo_motor = $_POST['tipo_motor'];
            $this->model->serie = $_POST['serie'];
            $this->model->color = $_POST['color'];
            $this->model->fecha_registro = $_POST['fecha_registro'];
            $this->model->id_empresa = $_POST['id_empresa'];
            
            if($this->model->update()) {
                $_SESSION['success_message'] = "Mototaxi actualizado correctamente.";
                header("Location: index.php?action=mototaxis");
                exit();
            } else {
                // Guardar los datos del formulario en sesión para repoblarlo
                $_SESSION['form_data'] = $_POST;
                header("Location: index.php?action=editar_mototaxi&id=" . $this->model->id_mototaxi . "&error=1");
                exit();
            }
        }
        
        // Cargar datos del formulario desde sesión si existen
        if (isset($_SESSION['form_data'])) {
            $form_data = $_SESSION['form_data'];
            unset($_SESSION['form_data']);
        }
        
        // Pasar empresas a la vista
        $empresas_data = $empresas->fetchAll(PDO::FETCH_ASSOC);
        
        include_once 'views/mototaxis/editar.php';
    }

    public function eliminar() {
        $this->model->id_mototaxi = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID no encontrado.');
        
        if($this->model->delete()) {
            $_SESSION['success_message'] = "Mototaxi eliminado correctamente.";
        } else {
            $_SESSION['error_message'] = "No se pudo eliminar el mototaxi.";
        }
        
        header("Location: index.php?action=mototaxis");
        exit();
    }
    
    // Método para ver mototaxis por conductor
    public function porConductor() {
        $dni = isset($_GET['dni']) ? $_GET['dni'] : '';
        
        if (!empty($dni)) {
            $mototaxis = $this->model->getByConductor($dni);
            $total_mototaxis = $this->model->countByConductor($dni);
            
            // Obtener nombre del conductor desde el primer registro
            $conductor_nombre = '';
            if ($total_mototaxis > 0) {
                $first_mototaxi = $mototaxis->fetch(PDO::FETCH_ASSOC);
                $conductor_nombre = $first_mototaxi['conductor_nombre_completo'];
                // Reiniciar el puntero del resultado
                $mototaxis->execute();
            }
            
            include_once 'views/mototaxis/por_conductor.php';
        } else {
            header("Location: index.php?action=mototaxis");
            exit();
        }
    }
    
    // Método para obtener conductores con múltiples mototaxis
    public function getConductoresMultiples() {
        $query = "SELECT conductor_nombre_completo, dni, COUNT(*) as total 
                  FROM mototaxis 
                  GROUP BY dni 
                  HAVING total > 1 
                  ORDER BY total DESC 
                  LIMIT 5";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para búsqueda mejorada
    public function buscar() {
        $termino = isset($_GET['q']) ? trim($_GET['q']) : '';
        
        if (!empty($termino)) {
            // Buscar tanto en mototaxis como en empresas
            $resultados_mototaxis = $this->model->buscar($termino);
            $total_resultados_mototaxis = $this->model->contarBusqueda($termino);
            
            // Buscar en empresas
            $empresa_model = new Empresa($this->db);
            $resultados_empresas = $empresa_model->buscar($termino);
            $total_resultados_empresas = $empresa_model->contarBusqueda($termino);
            
            // Total de resultados
            $total_resultados = $total_resultados_mototaxis + $total_resultados_empresas;
            
            include_once 'views/buscar/resultados.php';
        } else {
            // Si no hay término de búsqueda, redirigir al dashboard
            header("Location: index.php?action=dashboard");
            exit();
        }
    }
}
?>