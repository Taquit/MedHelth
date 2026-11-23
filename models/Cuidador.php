<?php
class Cuidador {
    private $conn;
    private $table_name = "cuidadores";
    private $table_relacion = "paciente_cuidador";

    // Propiedades del Cuidador
    public $id_cuidador;
    public $id_usuario;
    public $id_relacion;
    public $nombre;
    public $apellido_paterno;
    public $apellido_materno;
    public $parentesco;
    public $telefono;

    public function __construct($db) {
        $this->conn = $db;
    }

    // --- A) CREAR PERFIL DEL CUIDADOR ---
    public function crear() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET id_usuario=:id_usuario, nombre=:nombre, apellido_paterno=:paterno, 
                      apellido_materno=:materno, parentesco=:parentesco, telefono=:telefono";

        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido_paterno = htmlspecialchars(strip_tags($this->apellido_paterno));
        $this->apellido_materno = htmlspecialchars(strip_tags($this->apellido_materno));
        $this->parentesco = htmlspecialchars(strip_tags($this->parentesco));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));

        // Bind
        $stmt->bindParam(":id_usuario", $this->id_usuario);
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":paterno", $this->apellido_paterno);
        $stmt->bindParam(":materno", $this->apellido_materno);
        $stmt->bindParam(":parentesco", $this->parentesco);
        $stmt->bindParam(":telefono", $this->telefono);

        if($stmt->execute()) {
            // Obtenemos el ID del cuidador recién creado
            $this->id_cuidador = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // --- B) ASIGNAR CUIDADOR A UN PACIENTE (Alta en tabla intermedia) ---
    public function asignarPaciente($id_paciente) {
        // Primero, verificamos que no tenga un cuidador activo ya (opcional, por seguridad)
        $this->darBaja($id_paciente); 

        $query = "INSERT INTO " . $this->table_relacion . "
                  SET id_paciente = :id_paciente, id_cuidador = :id_cuidador, fecha_inicio = CURDATE()";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_paciente", $id_paciente);
        $stmt->bindParam(":id_cuidador", $this->id_cuidador);

        return $stmt->execute();
    }

    // --- C) DAR DE BAJA (Actualizar fecha_fin) ---
    public function darBaja($id_paciente) {
        // Actualiza TODOS los registros activos de este paciente a fecha de hoy
        $query = "UPDATE " . $this->table_relacion . "
                  SET fecha_fin = CURDATE()
                  WHERE id_paciente = :id_paciente AND fecha_fin IS NULL";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_paciente", $id_paciente);
        
        return $stmt->execute();
    }

    // --- D) OBTENER CUIDADOR ACTIVO (Para la vista del perfil) ---
    public function obtenerActivo($id_paciente) {
        $query = "SELECT c.*, u.correo, pc.fecha_inicio 
                  FROM " . $this->table_name . " c
                  JOIN " . $this->table_relacion . " pc ON c.id_cuidador = pc.id_cuidador
                  JOIN usuarios u ON c.id_usuario = u.id_usuario
                  WHERE pc.id_paciente = ? AND pc.fecha_fin IS NULL
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_paciente);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC); // Retorna array o false
    }

    // --- E) OBTENER HISTORIAL (Para la tabla inferior) ---
    public function obtenerHistorial($id_paciente) {
        $query = "SELECT c.nombre, c.apellido_paterno, pc.fecha_inicio, pc.fecha_fin
                  FROM " . $this->table_relacion . " pc
                  JOIN " . $this->table_name . " c ON pc.id_cuidador = c.id_cuidador
                  WHERE pc.id_paciente = ?
                  ORDER BY pc.fecha_inicio DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_paciente);
        $stmt->execute();
        
        return $stmt; // Retorna el statement para iterar con while
    }
}
?>