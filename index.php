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

// Determinar la acción a realizar
$action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';

// Incluir modelos
include_once 'models/Empresa.php';
include_once 'models/Mototaxi.php';

// Incluir controladores
include_once 'controllers/EmpresaController.php';
include_once 'controllers/MototaxiController.php';

// Crear instancias de controladores
$empresaController = new EmpresaController($db);
$mototaxiController = new MototaxiController($db);

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
        $empresaController->editar();
        break;
    case 'eliminar_empresa':
        $empresaController->eliminar();
        break;
    case 'mototaxis':
        $mototaxiController->index();
        break;
    case 'crear_mototaxi':
        $mototaxiController->crear();
        break;
    case 'editar_mototaxi':
        $mototaxiController->editar();
        break;
    case 'eliminar_mototaxi':
        $mototaxiController->eliminar();
        break;
    case 'buscar':
        // Nueva acción para búsqueda
        $mototaxiController->buscar();
        break;
    case 'buscar_empresas':
        // Nueva acción para búsqueda específica de empresas
        $empresaController->buscar();
        break;
    case 'porConductor':
        // Acción para ver mototaxis por conductor
        $mototaxiController->porConductor();
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