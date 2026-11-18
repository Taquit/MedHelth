<?php
class Reporte {
    private $conn;
    private $table_name = "reportes";

    public $id_reporte;
    public $id_paciente;
    public $id_medicamento; // INT UNSIGNED NULL (opcional para reportes generales)
    public $fecha;
    public $generado_por; // ENUM('sistema','cuidador','paciente')

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Registra la generacion de un reporte en la base de datos.
     * Soporta id_medicamento NULL para reportes generales de todo el tratamiento.
     *
     * @return bool
     */
    public function crear() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET id_paciente = :id_paciente,
                      id_medicamento = :id_med,
                      fecha = :fecha,
                      generado_por = :por";

        $stmt = $this->conn->prepare($query);

        $this->fecha = date('Y-m-d H:i:s');
        $rolesPermitidos = ['sistema', 'cuidador', 'paciente'];
        if (!in_array($this->generado_por, $rolesPermitidos, true)) {
            $this->generado_por = 'sistema';
        }

        $stmt->bindParam(":id_paciente", $this->id_paciente, PDO::PARAM_INT);
        if (empty($this->id_medicamento)) {
            $stmt->bindValue(":id_med", null, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(":id_med", $this->id_medicamento, PDO::PARAM_INT);
        }
        $stmt->bindParam(":fecha", $this->fecha);
        $stmt->bindParam(":por", $this->generado_por);

        if ($stmt->execute()) {
            $this->id_reporte = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    /**
     * Obtiene el historial de reportes generados para un paciente.
     *
     * @param int $id_paciente ID del paciente.
     * @return PDOStatement
     */
    public function leerPorPaciente(int $id_paciente) {
        $query = "SELECT r.*, m.nombre AS nombre_medicamento
                  FROM " . $this->table_name . " r
                  LEFT JOIN medicamentos m ON r.id_medicamento = m.id_medicamento
                  WHERE r.id_paciente = :id_paciente
                  ORDER BY r.fecha DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_paciente", $id_paciente, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt;
    }
}
?>
