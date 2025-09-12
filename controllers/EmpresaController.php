<?php
class EmpresaController {
    private $model;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new Empresa($db);
    }

    public function index() {
        $stmt = $this->model->read();
        include_once 'views/empresas/index.php';
    }

    public function crear() {
        if($_POST) {
            $this->model->razon_social = $_POST['razon_social'];
            $this->model->ruc = $_POST['ruc'];
            $this->model->representante_legal = $_POST['representante_legal'];
            
            if($this->model->create()) {
                header("Location: index.php?action=empresas");
                exit();
            } else {
                echo "<div class='alert alert-danger'>No se pudo crear la empresa.</div>";
            }
        }
        include_once 'views/empresas/crear.php';
    }

    public function editar() {
        $this->model->id_empresa = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID no encontrado.');
        
        if(!$this->model->readOne()) {
            die('ERROR: Empresa no encontrada.');
        }
        
        if($_POST) {
            $this->model->razon_social = $_POST['razon_social'];
            $this->model->ruc = $_POST['ruc'];
            $this->model->representante_legal = $_POST['representante_legal'];
            
            if($this->model->update()) {
                header("Location: index.php?action=empresas");
                exit();
            } else {
                echo "<div class='alert alert-danger'>No se pudo actualizar la empresa.</div>";
            }
        }
        include_once 'views/empresas/editar.php';
    }

    public function eliminar() {
        $this->model->id_empresa = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID no encontrado.');
        
        if($this->model->delete()) {
            header("Location: index.php?action=empresas");
            exit();
        } else {
            echo "<div class='alert alert-danger'>No se pudo eliminar la empresa.</div>";
        }
    }
    
    // Nuevo método para búsqueda
    public function buscar() {
        $termino = isset($_GET['q']) ? trim($_GET['q']) : '';
        
        if (!empty($termino)) {
            $resultados = $this->model->buscar($termino);
            $total_resultados = $this->model->contarBusqueda($termino);
            
            include_once 'views/empresas/resultados_busqueda.php';
        } else {
            header("Location: index.php?action=empresas");
            exit();
        }
    }
}
?>