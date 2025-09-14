<?php
class UserController {
    private $model;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new User($db);
        
        // Iniciar sesión si no está iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {
        $stmt = $this->model->read();
        include_once 'views/usuarios/index.php';
    }

    public function crear() {
        // Mostrar mensajes de error si existen
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
            $this->model->username = $_POST['username'];
            $this->model->password = $_POST['password'];
            $this->model->nombre_completo = $_POST['nombre_completo'];
            $this->model->email = $_POST['email'];
            $this->model->rol = $_POST['rol'];
            $this->model->activo = isset($_POST['activo']) ? 1 : 0;
            
            // Verificar si ya existe el username
            if ($this->model->usernameExists($_POST['username'])) {
                $_SESSION['error_message'] = "El nombre de usuario ya está en uso. Por favor elija otro.";
                header("Location: index.php?action=crear_usuario");
                exit();
            }
            
            // Verificar si ya existe el email
            if ($this->model->emailExists($_POST['email'])) {
                $_SESSION['error_message'] = "El correo electrónico ya está en uso. Por favor elija otro.";
                header("Location: index.php?action=crear_usuario");
                exit();
            }
            
            if($this->model->create()) {
                $_SESSION['success_message'] = "Usuario creado correctamente.";
                header("Location: index.php?action=usuarios");
                exit();
            } else {
                $_SESSION['error_message'] = "No se pudo crear el usuario. Por favor intente nuevamente.";
                header("Location: index.php?action=crear_usuario");
                exit();
            }
        }
        
        include_once 'views/usuarios/crear.php';
    }

    public function editar() {
        $this->model->id_usuario = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID no encontrado.');
        
        if(!$this->model->readOne()) {
            die('ERROR: Usuario no encontrado.');
        }
        
        // Mostrar mensaje de error si existe
        if (isset($_SESSION['error_message'])) {
            $error_message = $_SESSION['error_message'];
            unset($_SESSION['error_message']);
        }
        
        if($_POST) {
            $this->model->username = $_POST['username'];
            $this->model->nombre_completo = $_POST['nombre_completo'];
            $this->model->email = $_POST['email'];
            $this->model->rol = $_POST['rol'];
            $this->model->activo = isset($_POST['activo']) ? 1 : 0;
            
            // Si se proporcionó una nueva contraseña, la actualizamos
            if (!empty($_POST['password'])) {
                $this->model->password = $_POST['password'];
            }
            
            // Verificar si ya existe el username (excluyendo el usuario actual)
            if ($this->model->usernameExists($_POST['username'], $this->model->id_usuario)) {
                $_SESSION['error_message'] = "El nombre de usuario ya está en uso. Por favor elija otro.";
                header("Location: index.php?action=editar_usuario&id=" . $this->model->id_usuario);
                exit();
            }
            
            // Verificar si ya existe el email (excluyendo el usuario actual)
            if ($this->model->emailExists($_POST['email'], $this->model->id_usuario)) {
                $_SESSION['error_message'] = "El correo electrónico ya está en uso. Por favor elija otro.";
                header("Location: index.php?action=editar_usuario&id=" . $this->model->id_usuario);
                exit();
            }
            
            if($this->model->update()) {
                $_SESSION['success_message'] = "Usuario actualizado correctamente.";
                header("Location: index.php?action=usuarios");
                exit();
            } else {
                $_SESSION['error_message'] = "No se pudo actualizar el usuario. Por favor intente nuevamente.";
                header("Location: index.php?action=editar_usuario&id=" . $this->model->id_usuario);
                exit();
            }
        }
        
        include_once 'views/usuarios/editar.php';
    }

    public function eliminar() {
        $this->model->id_usuario = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID no encontrado.');
        
        // No permitir eliminar el usuario actual
        if (isset($_SESSION['user_id']) && $this->model->id_usuario == $_SESSION['user_id']) {
            $_SESSION['error_message'] = "No puede eliminar su propio usuario.";
            header("Location: index.php?action=usuarios");
            exit();
        }
        
        if($this->model->delete()) {
            $_SESSION['success_message'] = "Usuario eliminado correctamente.";
        } else {
            $_SESSION['error_message'] = "No se pudo eliminar el usuario.";
        }
        
        header("Location: index.php?action=usuarios");
        exit();
    }
}
?>