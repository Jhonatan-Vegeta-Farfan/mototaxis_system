<?php
class Empresa {
    private $conn;
    private $table_name = "empresas";

    public $id_empresa;
    public $razon_social;
    public $ruc;
    public $representante_legal;
    public $fecha_registro;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY razon_social ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Método para contar total de empresas
    public function countAll() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'];
    }

    // Método para contar empresas registradas hoy
    public function countRegistrosHoy() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " 
                  WHERE DATE(fecha_registro) = CURDATE()";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'];
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET razon_social=:razon_social, ruc=:ruc, representante_legal=:representante_legal";
        
        $stmt = $this->conn->prepare($query);
        
        $this->razon_social = htmlspecialchars(strip_tags($this->razon_social));
        $this->ruc = htmlspecialchars(strip_tags($this->ruc));
        $this->representante_legal = htmlspecialchars(strip_tags($this->representante_legal));
        
        $stmt->bindParam(":razon_social", $this->razon_social);
        $stmt->bindParam(":ruc", $this->ruc);
        $stmt->bindParam(":representante_legal", $this->representante_legal);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_empresa = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_empresa);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->razon_social = $row['razon_social'];
            $this->ruc = $row['ruc'];
            $this->representante_legal = $row['representante_legal'];
            $this->fecha_registro = $row['fecha_registro'];
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET razon_social=:razon_social, ruc=:ruc, representante_legal=:representante_legal 
                  WHERE id_empresa=:id_empresa";
        
        $stmt = $this->conn->prepare($query);
        
        $this->razon_social = htmlspecialchars(strip_tags($this->razon_social));
        $this->ruc = htmlspecialchars(strip_tags($this->ruc));
        $this->representante_legal = htmlspecialchars(strip_tags($this->representante_legal));
        $this->id_empresa = htmlspecialchars(strip_tags($this->id_empresa));
        
        $stmt->bindParam(":razon_social", $this->razon_social);
        $stmt->bindParam(":ruc", $this->ruc);
        $stmt->bindParam(":representante_legal", $this->representante_legal);
        $stmt->bindParam(":id_empresa", $this->id_empresa);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_empresa = ?";
        $stmt = $this->conn->prepare($query);
        
        $this->id_empresa = htmlspecialchars(strip_tags($this->id_empresa));
        $stmt->bindParam(1, $this->id_empresa);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Nuevo método para buscar empresas
    public function buscar($termino) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE razon_social LIKE :termino 
                     OR ruc LIKE :termino 
                     OR representante_legal LIKE :termino
                  ORDER BY razon_social ASC";
        
        $stmt = $this->conn->prepare($query);
        $termino = "%{$termino}%";
        $stmt->bindParam(":termino", $termino);
        $stmt->execute();
        
        return $stmt;
    }

    // Nuevo método para contar resultados de búsqueda de empresas
    public function contarBusqueda($termino) {
        $query = "SELECT COUNT(*) as total 
                  FROM " . $this->table_name . " 
                  WHERE razon_social LIKE :termino 
                     OR ruc LIKE :termino 
                     OR representante_legal LIKE :termino";
        
        $stmt = $this->conn->prepare($query);
        $termino = "%{$termino}%";
        $stmt->bindParam(":termino", $termino);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'];
    }
}
?>