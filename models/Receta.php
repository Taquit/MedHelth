<?php
class Receta {
    private $conn;
    private $table_name = "recetas";

    public $id_receta;
    public $id_paciente;
    public $fecha_emision;
    public $observaciones;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear una receta nueva y devolver su ID
    public function crear() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET id_paciente=:id_paciente, fecha_emision=:fecha, observaciones=:obs";

        $stmt = $this->conn->prepare($query);

        // Datos por defecto
        $this->fecha_emision = date('Y-m-d'); // Fecha de hoy
        $this->observaciones = "Registro individual de medicamento";

        $stmt->bindParam(":id_paciente", $this->id_paciente);
        $stmt->bindParam(":fecha", $this->fecha_emision);
        $stmt->bindParam(":obs", $this->observaciones);

        if($stmt->execute()) {
            // ¡Importante! Devolvemos el ID de la receta creada para usarlo en el medicamento
            return $this->conn->lastInsertId();
        }
        return false;
    }
}
?>