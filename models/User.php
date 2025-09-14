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
        $query = "CALL sp_obtener_usuarios()";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "CALL sp_crear_usuario(:username, :password, :nombre_completo, :email, :rol, :activo)";
        
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
        $query = "CALL sp_obtener_usuario_por_id(:id_usuario)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_usuario", $this->id_usuario);
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
        $query = "CALL sp_actualizar_usuario(:id_usuario, :username, :nombre_completo, :email, :rol, :activo)";
        
        $stmt = $this->conn->prepare($query);
        
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->nombre_completo = htmlspecialchars(strip_tags($this->nombre_completo));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->rol = htmlspecialchars(strip_tags($this->rol));
        $this->activo = htmlspecialchars(strip_tags($this->activo));
        $this->id_usuario = htmlspecialchars(strip_tags($this->id_usuario));
        
        $stmt->bindParam(":id_usuario", $this->id_usuario);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":nombre_completo", $this->nombre_completo);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":rol", $this->rol);
        $stmt->bindParam(":activo", $this->activo);
        
        if($stmt->execute()) {
            // Si se proporcionó una nueva contraseña, actualizarla
            if(!empty($this->password)) {
                $query_password = "CALL sp_actualizar_password_usuario(:id_usuario, :password)";
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
        $query = "CALL sp_eliminar_usuario(:id_usuario)";
        $stmt = $this->conn->prepare($query);
        
        $this->id_usuario = htmlspecialchars(strip_tags($this->id_usuario));
        $stmt->bindParam(":id_usuario", $this->id_usuario);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function usernameExists($username, $exclude_id = null) {
        $query = "CALL sp_verificar_username(:username, :exclude_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":exclude_id", $exclude_id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['existe'] > 0;
    }

    public function emailExists($email, $exclude_id = null) {
        $query = "CALL sp_verificar_email(:email, :exclude_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":exclude_id", $exclude_id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['existe'] > 0;
    }

    public function countAll() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'];
    }

    public function authenticate($username, $password) {
        $query = "CALL sp_autenticar_usuario(:username)";
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