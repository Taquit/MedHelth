<?php
class HistorialToma {
    private $conn;
    private $table_name = "historial_tomas";

    public $id_toma;
    public $id_medicamento;
    public $fecha_hora;
    public $estado; // 'tomado' o 'no_tomado'
    public $observaciones;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Registra una toma u omision de medicamento y guarda el id_toma generado.
     *
     * @return bool
     */
    public function registrar() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET id_medicamento=:id_med, fecha_hora=:fecha, estado=:estado, observaciones=:obs";

        $stmt = $this->conn->prepare($query);

        $this->fecha_hora = date('Y-m-d H:i:s');
        $this->observaciones = $this->observaciones !== null
            ? htmlspecialchars(strip_tags((string) $this->observaciones))
            : null;

        $stmt->bindParam(":id_med", $this->id_medicamento, PDO::PARAM_INT);
        $stmt->bindParam(":fecha", $this->fecha_hora);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":obs", $this->observaciones);

        if ($stmt->execute()) {
            $this->id_toma = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    /**
     * Obtiene el historial de tomas de un paciente uniendo con medicamentos y recetas.
     *
     * @param int $id_paciente ID del paciente.
     * @return PDOStatement
     */
    public function leerPorPaciente($id_paciente) {
        $query = "SELECT h.id_toma, h.fecha_hora, h.estado, h.observaciones, m.nombre as nombre_medicamento
                  FROM " . $this->table_name . " h
                  JOIN medicamentos m ON h.id_medicamento = m.id_medicamento
                  JOIN recetas r ON m.id_receta = r.id_receta
                  WHERE r.id_paciente = :id_paciente
                  ORDER BY h.fecha_hora DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_paciente", $id_paciente);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Obtiene el ultimo estado registrado hoy por cada medicamento de un paciente.
     *
     * @param int $id_paciente ID del paciente.
     * @return array<int, string> Mapa [id_medicamento => estado]
     */
    public function obtenerTomasHoyPorPaciente(int $id_paciente): array {
        $hoy = date('Y-m-d');
        $query = "SELECT h.id_medicamento, h.estado
                  FROM " . $this->table_name . " h
                  JOIN medicamentos m ON h.id_medicamento = m.id_medicamento
                  JOIN recetas r ON m.id_receta = r.id_receta
                  WHERE r.id_paciente = :id_paciente
                    AND DATE(h.fecha_hora) = :hoy
                  ORDER BY h.fecha_hora ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_paciente", $id_paciente, PDO::PARAM_INT);
        $stmt->bindParam(":hoy", $hoy);
        $stmt->execute();

        $resultado = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $resultado[(int) $row['id_medicamento']] = (string) $row['estado'];
        }
        return $resultado;
    }

    /**
     * Cuenta los dias distintos en que el paciente ha registrado al menos una toma ('tomado')
     * por cada medicamento.
     *
     * @param int $id_paciente ID del paciente.
     * @return array<int, int> Mapa [id_medicamento => dias_tomados]
     */
    public function contarDiasTomadosPorPaciente(int $id_paciente): array {
        $query = "SELECT h.id_medicamento, COUNT(DISTINCT DATE(h.fecha_hora)) AS dias_tomados
                  FROM " . $this->table_name . " h
                  JOIN medicamentos m ON h.id_medicamento = m.id_medicamento
                  JOIN recetas r ON m.id_receta = r.id_receta
                  WHERE r.id_paciente = :id_paciente
                    AND h.estado = 'tomado'
                  GROUP BY h.id_medicamento";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_paciente", $id_paciente, PDO::PARAM_INT);
        $stmt->execute();

        $conteos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $conteos[(int) $row['id_medicamento']] = (int) $row['dias_tomados'];
        }
        return $conteos;
    }
}
?>
