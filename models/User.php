<?php
class User {
    private $conn;
    private $table_name = "usuarios";

    public $id_usuario;
    public $username;
    public $password;
    public $nombre_completo;
    public $email;
    public $rol;
    public $activo;
    public $fecha_creacion;
    public $fecha_actualizacion;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT id_usuario, username, nombre_completo, email, rol, activo, fecha_creacion, fecha_actualizacion
                  FROM " . $this->table_name . " 
                  ORDER BY nombre_completo ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET username=:username, password=:password, nombre_completo=:nombre_completo, 
                  email=:email, rol=:rol, activo=:activo";
        
        $stmt = $this->conn->prepare($query);
        
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        $this->nombre_completo = htmlspecialchars(strip_tags($this->nombre_completo));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->rol = htmlspecialchars(strip_tags($this->rol));
        $this->activo = htmlspecialchars(strip_tags($this->activo));
        
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":nombre_completo", $this->nombre_completo);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":rol", $this->rol);
        $stmt->bindParam(":activo", $this->activo);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE id_usuario = ? LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_usuario);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->username = $row['username'];
            $this->password = $row['password'];
            $this->nombre_completo = $row['nombre_completo'];
            $this->email = $row['email'];
            $this->rol = $row['rol'];
            $this->activo = $row['activo'];
            $this->fecha_creacion = $row['fecha_creacion'];
            $this->fecha_actualizacion = $row['fecha_actualizacion'];
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET username=:username, nombre_completo=:nombre_completo, 
                  email=:email, rol=:rol, activo=:activo 
                  WHERE id_usuario=:id_usuario";
        
        $stmt = $this->conn->prepare($query);
        
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->nombre_completo = htmlspecialchars(strip_tags($this->nombre_completo));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->rol = htmlspecialchars(strip_tags($this->rol));
        $this->activo = htmlspecialchars(strip_tags($this->activo));
        $this->id_usuario = htmlspecialchars(strip_tags($this->id_usuario));
        
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":nombre_completo", $this->nombre_completo);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":rol", $this->rol);
        $stmt->bindParam(":activo", $this->activo);
        $stmt->bindParam(":id_usuario", $this->id_usuario);
        
        if($stmt->execute()) {
            // Si se proporcionó una nueva contraseña, actualizarla
            if(!empty($this->password)) {
                $query_password = "UPDATE " . $this->table_name . " 
                                  SET password=:password 
                                  WHERE id_usuario=:id_usuario";
                $stmt_password = $this->conn->prepare($query_password);
                $this->password = password_hash($this->password, PASSWORD_DEFAULT);
                $stmt_password->bindParam(":id_usuario", $this->id_usuario);
                $stmt_password->bindParam(":password", $this->password);
                $stmt_password->execute();
            }
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " 
                  WHERE id_usuario = ?";
        
        $stmt = $this->conn->prepare($query);
        
        $this->id_usuario = htmlspecialchars(strip_tags($this->id_usuario));
        $stmt->bindParam(1, $this->id_usuario);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function usernameExists($username, $exclude_id = null) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " 
                  WHERE username = :username";
        
        if ($exclude_id) {
            $query .= " AND id_usuario != :exclude_id";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        
        if ($exclude_id) {
            $stmt->bindParam(":exclude_id", $exclude_id);
        }
        
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'] > 0;
    }

    public function emailExists($email, $exclude_id = null) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " 
                  WHERE email = :email";
        
        if ($exclude_id) {
            $query .= " AND id_usuario != :exclude_id";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        
        if ($exclude_id) {
            $stmt->bindParam(":exclude_id", $exclude_id);
        }
        
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'] > 0;
    }

    public function countAll() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'];
    }

    public function authenticate($username, $password) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE username = :username AND activo = 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['password'])) {
                $this->id_usuario = $row['id_usuario'];
                $this->username = $row['username'];
                $this->nombre_completo = $row['nombre_completo'];
                $this->email = $row['email'];
                $this->rol = $row['rol'];
                $this->activo = $row['activo'];
                return true;
            }
        }
        return false;
    }
}
?>