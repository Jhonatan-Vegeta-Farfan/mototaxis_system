<?php
// Iniciar sesión
session_start();

// Verificar autenticación
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

// Incluir la conexión a la base de datos
include_once 'config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
} catch (Exception $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

// Incluir modelos
include_once 'models/Empresa.php';
include_once 'models/Mototaxi.php';
include_once 'models/User.php';
include_once 'models/ClientApi.php';
include_once 'models/TokenApi.php';

// Incluir controladores
include_once 'controllers/EmpresaController.php';
include_once 'controllers/MototaxiController.php';
include_once 'controllers/UserController.php';
include_once 'controllers/ClientApiController.php';
include_once 'controllers/TokenApiController.php';

// Determinar la acción a realizar
$action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';

// Crear instancias de controladores
$empresaController = new EmpresaController($db);
$mototaxiController = new MototaxiController($db);
$userController = new UserController($db);
$clientApiController = new ClientApiController($db);
$tokenApiController = new TokenApiController($db);

// Enrutamiento
try {
    switch ($action) {
        case 'dashboard':
            // Mostrar el panel de control
            include_once 'views/dashboard.php';
            break;
            
        case 'empresas':
            $empresaController->index();
            break;
            
        case 'crear_empresa':
            $empresaController->crear();
            break;
            
        case 'editar_empresa':
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            if (!$id) {
                throw new Exception("ID de empresa no proporcionado");
            }
            $empresaController->editar($id);
            break;
            
        case 'eliminar_empresa':
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            if (!$id) {
                throw new Exception("ID de empresa no proporcionado");
            }
            $empresaController->eliminar($id);
            break;
            
        case 'mototaxis':
            $mototaxiController->index();
            break;
            
        case 'crear_mototaxi':
            $mototaxiController->crear();
            break;
            
        case 'editar_mototaxi':
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            if (!$id) {
                throw new Exception("ID de mototaxi no proporcionado");
            }
            $mototaxiController->editar($id);
            break;
            
        case 'eliminar_mototaxi':
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            if (!$id) {
                throw new Exception("ID de mototaxi no proporcionado");
            }
            $mototaxiController->eliminar($id);
            break;
            
        case 'buscar':
            $mototaxiController->buscar();
            break;
            
        case 'buscar_empresas':
            $empresaController->buscar();
            break;
            
        case 'porConductor':
            $dni = isset($_GET['dni']) ? $_GET['dni'] : null;
            if (!$dni) {
                throw new Exception("DNI de conductor no proporcionado");
            }
            $mototaxiController->porConductor($dni);
            break;
            
        case 'usuarios':
            $userController->index();
            break;

        case 'crear_usuario':
            $userController->crear();
            break;

        case 'editar_usuario':
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            if (!$id) {
                throw new Exception("ID de usuario no proporcionado");
            }
            $userController->editar($id);
            break;

        case 'eliminar_usuario':
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            if (!$id) {
                throw new Exception("ID de usuario no proporcionado");
            }
            $userController->eliminar($id);
            break;
            
        case 'client_api':
            $clientApiController->index();
            break;
            
        case 'crear_client_api':
            $clientApiController->crear();
            break;
            
        case 'editar_client_api':
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            if (!$id) {
                throw new Exception("ID de cliente API no proporcionado");
            }
            $clientApiController->editar($id);
            break;
            
        case 'eliminar_client_api':
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            if (!$id) {
                throw new Exception("ID de cliente API no proporcionado");
            }
            $clientApiController->eliminar($id);
            break;
            
        case 'tokens_api':
            $tokenApiController->index();
            break;
            
        case 'crear_token_api':
            $tokenApiController->crear();
            break;
            
        case 'editar_token_api':
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            if (!$id) {
                throw new Exception("ID de token API no proporcionado");
            }
            $tokenApiController->editar($id);
            break;
            
        case 'eliminar_token_api':
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            if (!$id) {
                throw new Exception("ID de token API no proporcionado");
            }
            $tokenApiController->eliminar($id);
            break;
            
        case 'logout':
            // Cerrar sesión
            session_destroy();
            header("Location: login.php");
            exit();
            break;
            
        default:
            include_once 'views/dashboard.php';
            break;
    }
} catch (Exception $e) {
    // Manejo de errores
    error_log("Error en la aplicación: " . $e->getMessage());
    
    // Mostrar página de error
    include_once 'views/error.php';
}
?>