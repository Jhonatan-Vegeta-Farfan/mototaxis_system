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

// Enrutamiento mejorado
try {
    switch ($action) {
        case 'dashboard':
            // Cargar modelos para estadísticas
            $mototaxi = new Mototaxi($db);
            $empresa = new Empresa($db);
            $user = new User($db);
            $clientApi = new ClientApi($db);
            $tokenApi = new TokenApi($db);
            
            // Mostrar el panel de control
            include_once 'views/dashboard.php';
            break;
            
        case 'empresas':
            $empresaController->index();
            break;
            
        case 'crear_empresa':
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $empresaController->crear();
            } else {
                include_once 'views/empresas/crear.php';
            }
            break;
            
        case 'editar_empresa':
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            if (!$id) {
                throw new Exception("ID de empresa no proporcionado");
            }
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $empresaController->editar($id);
            } else {
                $empresaController->editar($id);
            }
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
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $mototaxiController->crear();
            } else {
                // Obtener empresas para el formulario
                $empresa = new Empresa($db);
                $empresas = $empresa->read();
                $empresas_data = $empresas->fetchAll(PDO::FETCH_ASSOC);
                
                // Obtener próximo número disponible
                $mototaxi = new Mototaxi($db);
                $next_number = $mototaxi->getNextAvailableNumber();
                
                include_once 'views/mototaxis/crear.php';
            }
            break;
            
        case 'editar_mototaxi':
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            if (!$id) {
                throw new Exception("ID de mototaxi no proporcionado");
            }
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $mototaxiController->editar($id);
            } else {
                $mototaxiController->editar($id);
            }
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
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $userController->crear();
            } else {
                include_once 'views/usuarios/crear.php';
            }
            break;

        case 'editar_usuario':
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            if (!$id) {
                throw new Exception("ID de usuario no proporcionado");
            }
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $userController->editar($id);
            } else {
                // Cargar datos del usuario
                $user = new User($db);
                $user->id_usuario = $id;
                if (!$user->readOne()) {
                    throw new Exception("Usuario no encontrado");
                }
                include_once 'views/usuarios/editar.php';
            }
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
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $clientApiController->crear();
            } else {
                include_once 'views/client_api/crear.php';
            }
            break;
            
        case 'editar_client_api':
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            if (!$id) {
                throw new Exception("ID de cliente API no proporcionado");
            }
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $clientApiController->editar($id);
            } else {
                // Cargar datos del cliente API
                $client = new ClientApi($db);
                $client->id = $id;
                if (!$client->readOne()) {
                    throw new Exception("Cliente API no encontrado");
                }
                include_once 'views/client_api/editar.php';
            }
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
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $tokenApiController->crear();
            } else {
                // Obtener lista de clientes para el formulario
                $clientApi = new ClientApi($db);
                $clients = $clientApi->read();
                include_once 'views/tokens_api/crear.php';
            }
            break;
            
        case 'editar_token_api':
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            if (!$id) {
                throw new Exception("ID de token API no proporcionado");
            }
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $tokenApiController->editar($id);
            } else {
                // Cargar datos del token API
                $token = new TokenApi($db);
                $token->id = $id;
                if (!$token->readOne()) {
                    throw new Exception("Token API no encontrado");
                }
                
                // Obtener lista de clientes para el formulario
                $clientApi = new ClientApi($db);
                $clients = $clientApi->read();
                
                include_once 'views/tokens_api/editar.php';
            }
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
            // Cargar modelos para estadísticas
            $mototaxi = new Mototaxi($db);
            $empresa = new Empresa($db);
            $user = new User($db);
            $clientApi = new ClientApi($db);
            $tokenApi = new TokenApi($db);
            
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