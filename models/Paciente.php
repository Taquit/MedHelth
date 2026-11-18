<?php
class Paciente {
    private $conn;
    private $table_name = "pacientes";

    public $id_paciente;
    public $id_usuario;
    public $nombre;
    public $apellido_paterno;
    public $apellido_materno;
    public $telefono;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear perfil de paciente
    public function crear() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET id_usuario=:id_usuario, nombre=:nombre, apellido_paterno=:paterno, 
                      apellido_materno=:materno, telefono=:telefono";

        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido_paterno = htmlspecialchars(strip_tags($this->apellido_paterno));
        $this->apellido_materno = htmlspecialchars(strip_tags($this->apellido_materno));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));

        // Bind
        $stmt->bindParam(":id_usuario", $this->id_usuario);
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":paterno", $this->apellido_paterno);
        $stmt->bindParam(":materno", $this->apellido_materno);
        $stmt->bindParam(":telefono", $this->telefono);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Obtener datos del paciente + Correo del usuario

    /**
     * @return PDOStatement
     */
    public function leerPorUsuario() {
        // CAMBIO: Hacemos JOIN con la tabla 'usuarios' para traer el campo 'correo'
        $query = "SELECT p.*, u.correo 
                  FROM " . $this->table_name . " p
                  JOIN usuarios u ON p.id_usuario = u.id_usuario
                  WHERE p.id_usuario = ? 
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_usuario);
        $stmt->execute();
        return $stmt;
    }
}
?>