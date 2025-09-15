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
$database = new Database();
$db = $database->getConnection();

// Incluir modelos
include_once 'models/Empresa.php';
include_once 'models/Mototaxi.php';
include_once 'models/User.php'; // Asegurar que el modelo User está incluido

// Incluir controladores
include_once 'controllers/EmpresaController.php';
include_once 'controllers/MototaxiController.php';
include_once 'controllers/UserController.php'; // Asegurar que el controlador User está incluido

// Determinar la acción a realizar
$action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';

// Crear instancias de controladores
$empresaController = new EmpresaController($db);
$mototaxiController = new MototaxiController($db);
$userController = new UserController($db); // Instancia para el controlador de usuarios

// Enrutamiento
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
        $empresaController->editar($id);
        break;
        
    case 'eliminar_empresa':
        $id = isset($_GET['id']) ? $_GET['id'] : null;
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
        $mototaxiController->editar($id);
        break;
        
    case 'eliminar_mototaxi':
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $mototaxiController->eliminar($id);
        break;
        
    case 'buscar':
        $mototaxiController->buscar();
        break;
        
    case 'buscar_empresas':
        $empresaController->buscar();
        break;
        
    case 'porConductor':
        $mototaxiController->porConductor();
        break;
        
    case 'usuarios':
        $userController->index();
        break;

    case 'crear_usuario':
        $userController->crear();
        break;

    case 'editar_usuario':
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $userController->editar($id);
        break;

    case 'eliminar_usuario':
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $userController->eliminar($id);
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
?>