<?php
session_start();

// Si ya está autenticado, redirigir al dashboard
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: index.php?action=dashboard");
    exit();
}

// Procesar el formulario de login
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Credenciales hardcodeadas (vegeta/123456789)
    if ($username === 'vegeta' && $password === '123456789') {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header("Location: index.php?action=dashboard");
        exit();
    } else {
        $error = 'Credenciales incorrectas';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Gestión de Mototaxis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0d6efd;
            --secondary: #0a58ca;
            --accent: #ff7e29;
            --light: #f8f9fa;
            --dark: #212529;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: url('https://i.ytimg.com/vi/TW9A3iusLV8/maxresdefault.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.85) 0%, rgba(10, 88, 202, 0.9) 100%);
            z-index: 0;
        }
        
        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 1100px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
            width: 100%;
            animation: fadeInDown 1s ease;
        }
        
        .login-logo {
            height: 120px;
            width: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin-bottom: 15px;
            transition: all 0.4s ease;
        }
        
        .login-logo:hover {
            transform: scale(1.05) rotate(5deg);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }
        
        .login-title {
            color: white;
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 5px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .login-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1rem;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
            transition: all 0.4s ease;
            animation: fadeInUp 1s ease;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .login-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 25px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .card-header::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(30deg);
            transition: all 0.5s ease;
        }
        
        .card-header:hover::after {
            transform: rotate(45deg);
        }
        
        .card-title {
            font-weight: 600;
            font-size: 1.5rem;
            margin-bottom: 5px;
            position: relative;
            z-index: 1;
        }
        
        .card-subtitle {
            font-size: 0.9rem;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }
        
        .card-body {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--dark);
            display: flex;
            align-items: center;
        }
        
        .form-label i {
            margin-right: 10px;
            color: var(--primary);
        }
        
        .form-control {
            border-radius: 12px;
            padding: 15px 20px;
            border: 2px solid #e1e5eb;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.2);
            transform: translateY(-2px);
        }
        
        .input-icon {
            position: absolute;
            right: 15px;
            top: 42px;
            color: #6c757d;
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border: none;
            border-radius: 12px;
            padding: 15px;
            font-weight: 600;
            font-size: 1.1rem;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(13, 110, 253, 0.3);
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: all 0.5s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 25px rgba(13, 110, 253, 0.4);
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .credential-hint {
            text-align: center;
            margin-top: 20px;
            padding: 12px;
            background: rgba(13, 110, 253, 0.1);
            border-radius: 10px;
            font-size: 0.9rem;
            color: var(--primary);
            border-left: 4px solid var(--primary);
        }
        
        .alert-danger {
            border-radius: 12px;
            padding: 15px;
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.2);
            color: #dc3545;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
            animation: shake 0.5s ease;
        }
        
        .mototaxi-animation {
            position: absolute;
            bottom: 20px;
            right: 20px;
            font-size: 3rem;
            color: white;
            opacity: 0.8;
            animation: drive 15s linear infinite;
            z-index: 1;
        }
        
        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        @keyframes drive {
            0% { transform: translateX(100vw); }
            100% { transform: translateX(-100px); }
        }
        
        /* Responsive design para todo tipo de pantallas */
        /* Pantallas muy grandes (TV, monitores 4K) */
        @media (min-width: 2000px) {
            .login-container {
                max-width: 1400px;
            }
            
            .login-card {
                max-width: 500px;
            }
            
            .login-title {
                font-size: 3rem;
            }
            
            .login-subtitle {
                font-size: 1.5rem;
            }
            
            .login-logo {
                height: 180px;
                width: 180px;
            }
            
            .card-title {
                font-size: 2rem;
            }
            
            .form-control {
                padding: 20px 25px;
                font-size: 1.2rem;
            }
            
            .btn-login {
                padding: 20px;
                font-size: 1.3rem;
            }
        }
        
        /* Tablets en modo horizontal y laptops */
        @media (max-width: 1024px) {
            .login-title {
                font-size: 2rem;
            }
            
            .login-logo {
                height: 110px;
                width: 110px;
            }
        }
        
        /* Tablets en modo vertical */
        @media (max-width: 768px) {
            .login-title {
                font-size: 1.8rem;
            }
            
            .login-subtitle {
                font-size: 1rem;
            }
            
            .login-logo {
                height: 100px;
                width: 100px;
            }
            
            .card-body {
                padding: 25px;
            }
        }
        
        /* Móviles en horizontal */
        @media (max-width: 576px) {
            body {
                padding: 15px;
            }
            
            .login-title {
                font-size: 1.6rem;
            }
            
            .login-subtitle {
                font-size: 0.9rem;
            }
            
            .login-logo {
                height: 90px;
                width: 90px;
            }
            
            .card-header {
                padding: 20px;
            }
            
            .card-title {
                font-size: 1.3rem;
            }
            
            .card-body {
                padding: 20px;
            }
            
            .form-control {
                padding: 12px 15px;
            }
            
            .input-icon {
                top: 38px;
            }
            
            .mototaxi-animation {
                font-size: 2rem;
                bottom: 10px;
                right: 10px;
            }
        }
        
        /* Móviles pequeños */
        @media (max-width: 400px) {
            body {
                padding: 10px;
            }
            
            .login-header {
                margin-bottom: 20px;
            }
            
            .login-title {
                font-size: 1.4rem;
            }
            
            .login-logo {
                height: 80px;
                width: 80px;
                margin-bottom: 10px;
            }
            
            .card-header {
                padding: 15px;
            }
            
            .card-title {
                font-size: 1.2rem;
            }
            
            .card-subtitle {
                font-size: 0.8rem;
            }
            
            .card-body {
                padding: 15px;
            }
            
            .form-label {
                font-size: 0.9rem;
            }
            
            .form-control {
                padding: 10px 12px;
                font-size: 0.9rem;
            }
            
            .input-icon {
                top: 35px;
                right: 12px;
            }
            
            .btn-login {
                padding: 12px;
                font-size: 1rem;
            }
            
            .credential-hint {
                font-size: 0.8rem;
                padding: 10px;
            }
            
            .mototaxi-animation {
                font-size: 1.5rem;
            }
        }
        
        /* Modo paisaje en móviles */
        @media (max-height: 500px) and (orientation: landscape) {
            body {
                align-items: flex-start;
                padding-top: 30px;
                padding-bottom: 30px;
            }
            
            .login-header {
                margin-bottom: 15px;
            }
            
            .login-logo {
                height: 70px;
                width: 70px;
                margin-bottom: 5px;
            }
            
            .login-title {
                font-size: 1.4rem;
                margin-bottom: 0;
            }
            
            .login-subtitle {
                font-size: 0.8rem;
            }
            
            .login-card {
                max-width: 350px;
            }
            
            .card-body {
                padding: 15px;
            }
            
            .form-group {
                margin-bottom: 15px;
            }
        }
        
        /* Pantallas con alta densidad de píxeles (Retina) */
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
            .login-card {
                border-width: 0.5px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <img src="assets/img/logomuni.jpg" alt="Logo Municipalidad de Huanta" class="login-logo">
            <h1 class="login-title">Sistema de Gestión Mototaxis</h1>
            <p class="login-subtitle">Municipalidad Provincial de Huanta</p>
        </div>
        
        <div class="login-card">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-lock me-2"></i>Acceso al Sistema</h2>
                <p class="card-subtitle">Ingrese sus credenciales para continuar</p>
            </div>
            
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="username" class="form-label">
                            <i class="fas fa-user"></i>Usuario
                        </label>
                        <input type="text" class="form-control" id="username" name="username" required 
                               placeholder="Ingrese su usuario">
                        <span class="input-icon"><i class="fas fa-user"></i></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-key"></i>Contraseña
                        </label>
                        <input type="password" class="form-control" id="password" name="password" required 
                               placeholder="Ingrese su contraseña">
                        <span class="input-icon"><i class="fas fa-lock"></i></span>
                    </div>
                    
                    <button type="submit" class="btn btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i> Iniciar Sesión
                    </button>
                </form>
                
<<<<<<< HEAD
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required
                           placeholder="Ingrese su contraseña">
                </div>
=======
>>>>>>> 04ab8709ef88c7d0f2a5b62896a71a3a1923529e
                
            </div>
        </div>
    </div>
    
    <div class="mototaxi-animation">
        <i class="fas fa-motorcycle"></i>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>