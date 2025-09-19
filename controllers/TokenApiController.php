<?php
class TokenApiController {
    private $model;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new TokenApi($db);
        
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
        include_once 'views/tokens_api/index.php';
    }

    public function crear() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error_message'] = "No tiene permisos para acceder a esta sección.";
            header("Location: index.php?action=dashboard");
            exit();
        }
        
        $clients = $this->model->getClients();
        
        if (isset($_SESSION['error_message'])) {
            $error_message = $_SESSION['error_message'];
            unset($_SESSION['error_message']);
        }
        
        if (isset($_SESSION['success_message'])) {
            $success_message = $_SESSION['success_message'];
            unset($_SESSION['success_message']);
        }
        
        if($_POST) {
            $this->model->id_client_api = $_POST['id_client_api'];
            
            if($this->model->create()) {
                $_SESSION['success_message'] = "Token API creado correctamente. Token: " . $this->model->token;
                header("Location: index.php?action=tokens_api");
                exit();
            } else {
                $_SESSION['error_message'] = "No se pudo crear el token API. Por favor intente nuevamente.";
                header("Location: index.php?action=crear_token_api");
                exit();
            }
        }
        
        include_once 'views/tokens_api/crear.php';
    }

    public function editar() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error_message'] = "No tiene permisos para acceder a esta sección.";
            header("Location: index.php?action=dashboard");
            exit();
        }
        
        $this->model->id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID no encontrado.');
        
        if(!$this->model->readOne()) {
            die('ERROR: Token API no encontrado.');
        }
        
        $clients = $this->model->getClients();
        
        if (isset($_SESSION['error_message'])) {
            $error_message = $_SESSION['error_message'];
            unset($_SESSION['error_message']);
        }
        
        if($_POST) {
            $this->model->id_client_api = $_POST['id_client_api'];
            $this->model->estado = isset($_POST['estado']) ? 1 : 0;
            
            if($this->model->update()) {
                $_SESSION['success_message'] = "Token API actualizado correctamente.";
                header("Location: index.php?action=tokens_api");
                exit();
            } else {
                $_SESSION['error_message'] = "No se pudo actualizar el token API. Por favor intente nuevamente.";
                header("Location: index.php?action=editar_token_api&id=" . $this->model->id);
                exit();
            }
        }
        
        include_once 'views/tokens_api/editar.php';
    }

    public function eliminar() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error_message'] = "No tiene permisos para acceder a esta sección.";
            header("Location: index.php?action=dashboard");
            exit();
        }
        
        $this->model->id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID no encontrado.');
        
        if($this->model->delete()) {
            $_SESSION['success_message'] = "Token API eliminado correctamente.";
        } else {
            $_SESSION['error_message'] = "No se pudo eliminar el token API.";
        }
        
        header("Location: index.php?action=tokens_api");
        exit();
    }
}
?>