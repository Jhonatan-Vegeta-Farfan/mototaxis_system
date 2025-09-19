<?php
class ClientApiController {
    private $model;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new ClientApi($db);
        
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error_message'] = "No tiene permisos para acceder a esta sección.";
            header("Location: index.php?action=dashboard");
            exit();
        }
        
        $stmt = $this->model->read();
        include_once 'views/client_api/index.php';
    }

    public function crear() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error_message'] = "No tiene permisos para acceder a esta sección.";
            header("Location: index.php?action=dashboard");
            exit();
        }
        
        if (isset($_SESSION['error_message'])) {
            $error_message = $_SESSION['error_message'];
            unset($_SESSION['error_message']);
        }
        
        if (isset($_SESSION['success_message'])) {
            $success_message = $_SESSION['success_message'];
            unset($_SESSION['success_message']);
        }
        
        if($_POST) {
            $this->model->ruc = $_POST['ruc'];
            $this->model->razon_social = $_POST['razon_social'];
            $this->model->telefono = $_POST['telefono'];
            $this->model->correo = $_POST['correo'];
            
            if ($this->model->rucExists($_POST['ruc'])) {
                $_SESSION['error_message'] = "El RUC ya está registrado en el sistema.";
                header("Location: index.php?action=crear_client_api");
                exit();
            }
            
            if($this->model->create()) {
                $_SESSION['success_message'] = "Cliente API creado correctamente.";
                header("Location: index.php?action=client_api");
                exit();
            } else {
                $_SESSION['error_message'] = "No se pudo crear el cliente API. Por favor intente nuevamente.";
                header("Location: index.php?action=crear_client_api");
                exit();
            }
        }
        
        include_once 'views/client_api/crear.php';
    }

    public function editar() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error_message'] = "No tiene permisos para acceder a esta sección.";
            header("Location: index.php?action=dashboard");
            exit();
        }
        
        $this->model->id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID no encontrado.');
        
        if(!$this->model->readOne()) {
            die('ERROR: Cliente API no encontrado.');
        }
        
        if (isset($_SESSION['error_message'])) {
            $error_message = $_SESSION['error_message'];
            unset($_SESSION['error_message']);
        }
        
        if($_POST) {
            $this->model->ruc = $_POST['ruc'];
            $this->model->razon_social = $_POST['razon_social'];
            $this->model->telefono = $_POST['telefono'];
            $this->model->correo = $_POST['correo'];
            $this->model->estado = isset($_POST['estado']) ? 1 : 0;
            
            if ($this->model->rucExists($_POST['ruc'], $this->model->id)) {
                $_SESSION['error_message'] = "El RUC ya está registrado en otro cliente API.";
                header("Location: index.php?action=editar_client_api&id=" . $this->model->id);
                exit();
            }
            
            if($this->model->update()) {
                $_SESSION['success_message'] = "Cliente API actualizado correctamente.";
                header("Location: index.php?action=client_api");
                exit();
            } else {
                $_SESSION['error_message'] = "No se pudo actualizar el cliente API. Por favor intente nuevamente.";
                header("Location: index.php?action=editar_client_api&id=" . $this->model->id);
                exit();
            }
        }
        
        include_once 'views/client_api/editar.php';
    }

    public function eliminar() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error_message'] = "No tiene permisos para acceder a esta sección.";
            header("Location: index.php?action=dashboard");
            exit();
        }
        
        $this->model->id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID no encontrado.');
        
        if($this->model->delete()) {
            $_SESSION['success_message'] = "Cliente API eliminado correctamente.";
        } else {
            $_SESSION['error_message'] = "No se pudo eliminar el cliente API.";
        }
        
        header("Location: index.php?action=client_api");
        exit();
    }
}
?>