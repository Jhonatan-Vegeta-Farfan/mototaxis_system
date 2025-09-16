<?php
// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Obtener la acción actual
$action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Mototaxis - MP Huanta</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Solo agregamos este pequeño ajuste para el logo */
        .navbar-brand img {
            height: 80px;
            width: auto;
            object-fit: contain;
        }
    </style>
</head>
<body>
    <!-- Barra de navegación superior -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-gradient bg-primary shadow">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?action=dashboard">
                <img src="assets/img/logomuni.jpg" alt="Logo" class="d-inline-block align-text-top me-2">
                <span class="d-none d-md-inline">Municipalidad Provincial de Huanta</span>
                <span class="d-md-none">MP Huanta</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarMain">
                <!-- Barra de búsqueda en la navegación -->
                <form class="d-flex me-auto my-2 my-lg-0" action="index.php" method="GET">
                    <input type="hidden" name="action" value="buscar">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" placeholder="Buscar por nombre, DNI, placa..." aria-label="Buscar">
                        <button class="btn btn-light" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($action == 'dashboard') ? 'active' : ''; ?>" 
                           href="index.php?action=dashboard">
                            <i class="fas fa-tachometer-alt me-1"></i> PANEL DE CONTROL
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($action == 'mototaxis' || $action == 'crear_mototaxi' || $action == 'editar_mototaxi' || $action == 'porConductor') ? 'active' : ''; ?>" 
                           href="index.php?action=mototaxis">
                            <i class="fas fa-motorcycle me-1"></i> Mototaxis
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($action == 'empresas' || $action == 'crear_empresa' || $action == 'editar_empresa') ? 'active' : ''; ?>" 
                           href="index.php?action=empresas">
                            <i class="fas fa-building me-1"></i> Empresas
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-plus-circle me-1"></i> Nuevo
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="index.php?action=crear_mototaxi">
                                <i class="fas fa-motorcycle me-2"></i> Nuevo Mototaxi
                            </a></li>
                            <li><a class="dropdown-item" href="index.php?action=crear_empresa">
                                <i class="fas fa-building me-2"></i> Nueva Empresa
                            </a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user me-1"></i> <?php echo $_SESSION['username'] ?? 'Usuario'; ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="#">
                                <i class="fas fa-user-circle me-2"></i> Mi Perfil
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="index.php?action=logout">
                                <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container-fluid mt-4">