<?php
class Mototaxi {
    private $conn;
    private $table_name = "mototaxis";

    public $id_mototaxi;
    public $numero_asignado;
    public $conductor_nombre_completo;
    public $dni;
    public $direccion;
    public $placa_rodaje;
    public $anio_fabricacion;
    public $marca;
    public $numero_motor;
    public $tipo_motor;
    public $serie;
    public $color;
    public $fecha_registro;
    public $id_empresa;
    public $activo;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT m.*, e.razon_social 
                  FROM " . $this->table_name . " m 
                  LEFT JOIN empresas e ON m.id_empresa = e.id_empresa 
                  ORDER BY CAST(m.numero_asignado AS UNSIGNED) ASC, m.fecha_registro DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Método para verificar si ya existe una placa (único campo que debe ser único)
    public function placaExists($placa, $exclude_id = null) {
        $query = "SELECT id_mototaxi FROM " . $this->table_name . " WHERE placa_rodaje = :placa";
        
        if ($exclude_id) {
            $query .= " AND id_mototaxi != :exclude_id";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":placa", $placa);
        
        if ($exclude_id) {
            $stmt->bindParam(":exclude_id", $exclude_id);
        }
        
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    // Método para contar total de mototaxis
    public function countAll() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'];
    }

    // Método para contar mototaxis registrados hoy
    public function countRegistrosHoy() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " 
                  WHERE DATE(fecha_registro) = CURDATE()";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'];
    }

    // Método para contar mototaxis activos
    public function countActivos() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " 
                  WHERE activo = 1 OR activo IS NULL";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'];
    }

    // Método para obtener mototaxis por conductor (DNI)
    public function getByConductor($dni) {
        $query = "SELECT m.*, e.razon_social 
                  FROM " . $this->table_name . " m 
                  LEFT JOIN empresas e ON m.id_empresa = e.id_empresa 
                  WHERE m.dni = :dni 
                  ORDER BY CAST(m.numero_asignado AS UNSIGNED) ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":dni", $dni);
        $stmt->execute();
        
        return $stmt;
    }

    // Método para contar mototaxis por conductor
    public function countByConductor($dni) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE dni = :dni";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":dni", $dni);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'];
    }

    // Método para obtener el siguiente número disponible
    public function getNextAvailableNumber() {
        $query = "SELECT MAX(CAST(numero_asignado AS UNSIGNED)) as max_num FROM " . $this->table_name;
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return ($row['max_num'] + 1) ?: 1;
    }

    public function create() {
        // Solo verificar si ya existe la placa (único campo que debe ser único)
        if ($this->placaExists($this->placa_rodaje)) {
            $_SESSION['error_message'] = "La placa $this->placa_rodaje ya está registrada en el sistema. Cada mototaxi debe tener una placa única.";
            return false;
        }
        
        $query = "INSERT INTO " . $this->table_name . " 
                  SET numero_asignado=:numero_asignado, conductor_nombre_completo=:conductor_nombre_completo, 
                  dni=:dni, direccion=:direccion, placa_rodaje=:placa_rodaje, anio_fabricacion=:anio_fabricacion, 
                  marca=:marca, numero_motor=:numero_motor, tipo_motor=:tipo_motor, serie=:serie, 
                  color=:color, fecha_registro=:fecha_registro, id_empresa=:id_empresa";
        
        $stmt = $this->conn->prepare($query);
        
        $this->numero_asignado = htmlspecialchars(strip_tags($this->numero_asignado));
        $this->conductor_nombre_completo = htmlspecialchars(strip_tags($this->conductor_nombre_completo));
        $this->dni = htmlspecialchars(strip_tags($this->dni));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        $this->placa_rodaje = htmlspecialchars(strip_tags($this->placa_rodaje));
        $this->anio_fabricacion = htmlspecialchars(strip_tags($this->anio_fabricacion));
        $this->marca = htmlspecialchars(strip_tags($this->marca));
        $this->numero_motor = htmlspecialchars(strip_tags($this->numero_motor));
        $this->tipo_motor = htmlspecialchars(strip_tags($this->tipo_motor));
        $this->serie = htmlspecialchars(strip_tags($this->serie));
        $this->color = htmlspecialchars(strip_tags($this->color));
        $this->fecha_registro = htmlspecialchars(strip_tags($this->fecha_registro));
        $this->id_empresa = htmlspecialchars(strip_tags($this->id_empresa));
        
        $stmt->bindParam(":numero_asignado", $this->numero_asignado);
        $stmt->bindParam(":conductor_nombre_completo", $this->conductor_nombre_completo);
        $stmt->bindParam(":dni", $this->dni);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":placa_rodaje", $this->placa_rodaje);
        $stmt->bindParam(":anio_fabricacion", $this->anio_fabricacion);
        $stmt->bindParam(":marca", $this->marca);
        $stmt->bindParam(":numero_motor", $this->numero_motor);
        $stmt->bindParam(":tipo_motor", $this->tipo_motor);
        $stmt->bindParam(":serie", $this->serie);
        $stmt->bindParam(":color", $this->color);
        $stmt->bindParam(":fecha_registro", $this->fecha_registro);
        $stmt->bindParam(":id_empresa", $this->id_empresa);
        
        if($stmt->execute()) {
            return true;
        }
        
        $_SESSION['error_message'] = "Error al crear el mototaxi: " . $stmt->errorInfo()[2];
        return false;
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_mototaxi = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_mototaxi);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->numero_asignado = $row['numero_asignado'];
            $this->conductor_nombre_completo = $row['conductor_nombre_completo'];
            $this->dni = $row['dni'];
            $this->direccion = $row['direccion'];
            $this->placa_rodaje = $row['placa_rodaje'];
            $this->anio_fabricacion = $row['anio_fabricacion'];
            $this->marca = $row['marca'];
            $this->numero_motor = $row['numero_motor'];
            $this->tipo_motor = $row['tipo_motor'];
            $this->serie = $row['serie'];
            $this->color = $row['color'];
            $this->fecha_registro = $row['fecha_registro'];
            $this->id_empresa = $row['id_empresa'];
            $this->activo = $row['activo'];
            return true;
        }
        return false;
    }

    public function update() {
        // Solo verificar si ya existe la placa (excluyendo el registro actual)
        if ($this->placaExists($this->placa_rodaje, $this->id_mototaxi)) {
            $_SESSION['error_message'] = "La placa $this->placa_rodaje ya está registrada en otro mototaxi. Cada mototaxi debe tener una placa única.";
            return false;
        }
        
        $query = "UPDATE " . $this->table_name . " 
                  SET numero_asignado=:numero_asignado, conductor_nombre_completo=:conductor_nombre_completo, 
                  dni=:dni, direccion=:direccion, placa_rodaje=:placa_rodaje, anio_fabricacion=:anio_fabricacion, 
                  marca=:marca, numero_motor=:numero_motor, tipo_motor=:tipo_motor, serie=:serie, 
                  color=:color, fecha_registro=:fecha_registro, id_empresa=:id_empresa 
                  WHERE id_mototaxi=:id_mototaxi";
        
        $stmt = $this->conn->prepare($query);
        
        $this->numero_asignado = htmlspecialchars(strip_tags($this->numero_asignado));
        $this->conductor_nombre_completo = htmlspecialchars(strip_tags($this->conductor_nombre_completo));
        $this->dni = htmlspecialchars(strip_tags($this->dni));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        $this->placa_rodaje = htmlspecialchars(strip_tags($this->placa_rodaje));
        $this->anio_fabricacion = htmlspecialchars(strip_tags($this->anio_fabricacion));
        $this->marca = htmlspecialchars(strip_tags($this->marca));
        $this->numero_motor = htmlspecialchars(strip_tags($this->numero_motor));
        $this->tipo_motor = htmlspecialchars(strip_tags($this->tipo_motor));
        $this->serie = htmlspecialchars(strip_tags($this->serie));
        $this->color = htmlspecialchars(strip_tags($this->color));
        $this->fecha_registro = htmlspecialchars(strip_tags($this->fecha_registro));
        $this->id_empresa = htmlspecialchars(strip_tags($this->id_empresa));
        $this->id_mototaxi = htmlspecialchars(strip_tags($this->id_mototaxi));
        
        $stmt->bindParam(":numero_asignado", $this->numero_asignado);
        $stmt->bindParam(":conductor_nombre_completo", $this->conductor_nombre_completo);
        $stmt->bindParam(":dni", $this->dni);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":placa_rodaje", $this->placa_rodaje);
        $stmt->bindParam(":anio_fabricacion", $this->anio_fabricacion);
        $stmt->bindParam(":marca", $this->marca);
        $stmt->bindParam(":numero_motor", $this->numero_motor);
        $stmt->bindParam(":tipo_motor", $this->tipo_motor);
        $stmt->bindParam(":serie", $this->serie);
        $stmt->bindParam(":color", $this->color);
        $stmt->bindParam(":fecha_registro", $this->fecha_registro);
        $stmt->bindParam(":id_empresa", $this->id_empresa);
        $stmt->bindParam(":id_mototaxi", $this->id_mototaxi);
        
        if($stmt->execute()) {
            return true;
        }
        
        $_SESSION['error_message'] = "Error al actualizar el mototaxi: " . $stmt->errorInfo()[2];
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_mototaxi = ?";
        $stmt = $this->conn->prepare($query);
        
        $this->id_mototaxi = htmlspecialchars(strip_tags($this->id_mototaxi));
        $stmt->bindParam(1, $this->id_mototaxi);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getEmpresas() {
        $query = "SELECT id_empresa, razon_social FROM empresas ORDER BY razon_social";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Método para buscar mototaxis por término de búsqueda (mejorado)
    public function buscar($termino) {
        $query = "SELECT m.*, e.razon_social, e.ruc as ruc_empresa, e.representante_legal 
                  FROM " . $this->table_name . " m 
                  LEFT JOIN empresas e ON m.id_empresa = e.id_empresa 
                  WHERE m.conductor_nombre_completo LIKE :termino 
                     OR m.dni LIKE :termino 
                     OR m.placa_rodaje LIKE :termino 
                     OR m.numero_motor LIKE :termino 
                     OR m.serie LIKE :termino 
                     OR m.numero_asignado LIKE :termino
                     OR m.marca LIKE :termino
                     OR m.color LIKE :termino
                     OR m.tipo_motor LIKE :termino
                     OR e.razon_social LIKE :termino 
                     OR e.ruc LIKE :termino 
                     OR e.representante_legal LIKE :termino
                  ORDER BY CAST(m.numero_asignado AS UNSIGNED) ASC";
        
        $stmt = $this->conn->prepare($query);
        $termino = "%{$termino}%";
        $stmt->bindParam(":termino", $termino);
        $stmt->execute();
        
        return $stmt;
    }

    // Método para contar resultados de búsqueda (mejorado)
    public function contarBusqueda($termino) {
        $query = "SELECT COUNT(*) as total 
                  FROM " . $this->table_name . " m 
                  LEFT JOIN empresas e ON m.id_empresa = e.id_empresa 
                  WHERE m.conductor_nombre_completo LIKE :termino 
                     OR m.dni LIKE :termino 
                     OR m.placa_rodaje LIKE :termino 
                     OR m.numero_motor LIKE :termino 
                     OR m.serie LIKE :termino 
                     OR m.numero_asignado LIKE :termino
                     OR m.marca LIKE :termino
                     OR m.color LIKE :termino
                     OR m.tipo_motor LIKE :termino
                     OR e.razon_social LIKE :termino 
                     OR e.ruc LIKE :termino 
                     OR e.representante_legal LIKE :termino";
        
        $stmt = $this->conn->prepare($query);
        $termino = "%{$termino}%";
        $stmt->bindParam(":termino", $termino);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'];
    }
}
?>