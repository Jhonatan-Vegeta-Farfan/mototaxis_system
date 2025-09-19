<?php
class ClientApi {
    private $conn;
    private $table_name = "client_api";

    public $id;
    public $ruc;
    public $razon_social;
    public $telefono;
    public $correo;
    public $fecha_registro;
    public $estado;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY fecha_registro DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET ruc=:ruc, razon_social=:razon_social, telefono=:telefono, 
                  correo=:correo, fecha_registro=CURDATE(), estado=1";
        
        $stmt = $this->conn->prepare($query);
        
        $this->ruc = htmlspecialchars(strip_tags($this->ruc));
        $this->razon_social = htmlspecialchars(strip_tags($this->razon_social));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->correo = htmlspecialchars(strip_tags($this->correo));
        
        $stmt->bindParam(":ruc", $this->ruc);
        $stmt->bindParam(":razon_social", $this->razon_social);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":correo", $this->correo);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->ruc = $row['ruc'];
            $this->razon_social = $row['razon_social'];
            $this->telefono = $row['telefono'];
            $this->correo = $row['correo'];
            $this->fecha_registro = $row['fecha_registro'];
            $this->estado = $row['estado'];
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET ruc=:ruc, razon_social=:razon_social, telefono=:telefono, 
                  correo=:correo, estado=:estado 
                  WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->ruc = htmlspecialchars(strip_tags($this->ruc));
        $this->razon_social = htmlspecialchars(strip_tags($this->razon_social));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->correo = htmlspecialchars(strip_tags($this->correo));
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        $stmt->bindParam(":ruc", $this->ruc);
        $stmt->bindParam(":razon_social", $this->razon_social);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":correo", $this->correo);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function rucExists($ruc, $exclude_id = null) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE ruc = :ruc";
        
        if ($exclude_id) {
            $query .= " AND id != :exclude_id";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":ruc", $ruc);
        
        if ($exclude_id) {
            $stmt->bindParam(":exclude_id", $exclude_id);
        }
        
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function countAll() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
}
?>