<?php
// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Obtener la acción actual
$action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';

// Verificar si el usuario es administrador
$isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin';
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
        .navbar-brand img {
            height: 80px;
            width: auto;
            object-fit: contain;
        }
        
        /* Estilo para elementos de menú activos */
        .nav-item .active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            color: white !important;
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
                        <a class="nav-link <?php echo (in_array($action, ['mototaxis', 'crear_mototaxi', 'editar_mototaxi', 'porConductor'])) ? 'active' : ''; ?>" 
                           href="index.php?action=mototaxis">
                            <i class="fas fa-motorcycle me-1"></i> Mototaxis
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (in_array($action, ['empresas', 'crear_empresa', 'editar_empresa'])) ? 'active' : ''; ?>" 
                           href="index.php?action=empresas">
                            <i class="fas fa-building me-1"></i> Empresas
                        </a>
                    </li>
                    
                    <?php if ($isAdmin): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (in_array($action, ['usuarios', 'crear_usuario', 'editar_usuario'])) ? 'active' : ''; ?>" 
                           href="index.php?action=usuarios">
                            <i class="fas fa-users me-1"></i> Usuarios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (in_array($action, ['client_api', 'crear_client_api', 'editar_client_api'])) ? 'active' : ''; ?>" 
                           href="index.php?action=client_api">
                            <i class="fas fa-code me-1"></i> Clientes API
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (in_array($action, ['tokens_api', 'crear_token_api', 'editar_token_api'])) ? 'active' : ''; ?>" 
                           href="index.php?action=tokens_api">
                            <i class="fas fa-key me-1"></i> Tokens API
                        </a>
                    </li>
                    <?php endif; ?>
                    
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
                            <?php if ($isAdmin): ?>
                            <li><a class="dropdown-item" href="index.php?action=crear_usuario">
                                <i class="fas fa-user-plus me-2"></i> Nuevo Usuario
                            </a></li>
                            <li><a class="dropdown-item" href="index.php?action=crear_client_api">
                                <i class="fas fa-code me-2"></i> Nuevo Cliente API
                            </a></li>
                            <li><a class="dropdown-item" href="index.php?action=crear_token_api">
                                <i class="fas fa-key me-2"></i> Nuevo Token API
                            </a></li>
                            <?php endif; ?>
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