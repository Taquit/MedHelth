<?php
class Medicamento {
    private $conn;
    private $table_name = "medicamentos";

    public $id_medicamento;
    public $id_receta;
    public $nombre;
    public $dosis;
    public $frecuencia_horas;
    public $duracion_dias;
    public $hora_inicio;
    public $via_administracion;
    public $indicaciones;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Verifica si un medicamento pertenece a un paciente especifico a traves de su receta.
     *
     * @param int $id_medicamento ID del medicamento.
     * @param int $id_paciente ID del paciente.
     * @return bool
     */
    public function perteneceAPaciente(int $id_medicamento, int $id_paciente): bool {
        $query = "SELECT m.id_medicamento
                  FROM " . $this->table_name . " m
                  JOIN recetas r ON m.id_receta = r.id_receta
                  WHERE m.id_medicamento = :id_med AND r.id_paciente = :id_paciente
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_med", $id_medicamento, PDO::PARAM_INT);
        $stmt->bindParam(":id_paciente", $id_paciente, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    /**
     * Registra un nuevo medicamento asociado a una receta.
     *
     * @return bool
     */
    public function crear() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET id_receta=:id_receta, nombre=:nombre, dosis=:dosis, 
                      frecuencia_horas=:frecuencia, duracion_dias=:duracion, 
                      hora_inicio=:hora_inicio, via_administracion=:via, indicaciones=:indicaciones";

        $stmt = $this->conn->prepare($query);

        $this->nombre = htmlspecialchars(strip_tags((string) $this->nombre));
        $this->dosis = htmlspecialchars(strip_tags((string) $this->dosis));
        $this->via_administracion = htmlspecialchars(strip_tags((string) $this->via_administracion));
        $this->indicaciones = htmlspecialchars(strip_tags((string) $this->indicaciones));

        $stmt->bindParam(":id_receta", $this->id_receta);
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":dosis", $this->dosis);
        $stmt->bindParam(":frecuencia", $this->frecuencia_horas);
        $stmt->bindParam(":duracion", $this->duracion_dias);
        $stmt->bindParam(":hora_inicio", $this->hora_inicio);
        $stmt->bindParam(":via", $this->via_administracion);
        $stmt->bindParam(":indicaciones", $this->indicaciones);

        if ($stmt->execute()) {
            $this->id_medicamento = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    /**
     * Obtiene todos los medicamentos de un paciente haciendo JOIN con recetas.
     *
     * @param int $id_paciente ID del paciente.
     * @return PDOStatement
     */
    public function leerPorPaciente($id_paciente) {
        $query = "SELECT m.* FROM " . $this->table_name . " m
                  JOIN recetas r ON m.id_receta = r.id_receta
                  WHERE r.id_paciente = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_paciente);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Carga los datos de un medicamento en la instancia actual.
     * Si se proporciona $id_paciente, valida que pertenezca a dicho paciente.
     *
     * @param int|null $id_paciente ID del paciente propietario opcional.
     * @return bool
     */
    public function leerUno(?int $id_paciente = null) {
        if ($id_paciente !== null && !$this->perteneceAPaciente((int) $this->id_medicamento, $id_paciente)) {
            return false;
        }

        $query = "SELECT * FROM " . $this->table_name . " WHERE id_medicamento = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_medicamento);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->id_receta = $row['id_receta'];
            $this->nombre = $row['nombre'];
            $this->dosis = $row['dosis'];
            $this->frecuencia_horas = $row['frecuencia_horas'];
            $this->duracion_dias = $row['duracion_dias'];
            $this->hora_inicio = $row['hora_inicio'];
            $this->via_administracion = $row['via_administracion'];
            $this->indicaciones = $row['indicaciones'];
            return true;
        }
        return false;
    }

    /**
     * Actualiza los datos de un medicamento existente.
     * Si se proporciona $id_paciente, valida que pertenezca a dicho paciente.
     *
     * @param int|null $id_paciente ID del paciente propietario opcional.
     * @return bool
     */
    public function actualizar(?int $id_paciente = null) {
        if ($id_paciente !== null && !$this->perteneceAPaciente((int) $this->id_medicamento, $id_paciente)) {
            return false;
        }

        $query = "UPDATE " . $this->table_name . "
                  SET nombre = :nombre,
                      dosis = :dosis,
                      frecuencia_horas = :frecuencia,
                      duracion_dias = :duracion,
                      hora_inicio = :hora_inicio,
                      via_administracion = :via,
                      indicaciones = :indicaciones
                  WHERE id_medicamento = :id";

        $stmt = $this->conn->prepare($query);

        $this->nombre = htmlspecialchars(strip_tags((string) $this->nombre));
        $this->dosis = htmlspecialchars(strip_tags((string) $this->dosis));
        $this->via_administracion = htmlspecialchars(strip_tags((string) $this->via_administracion));
        $this->indicaciones = htmlspecialchars(strip_tags((string) $this->indicaciones));

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":dosis", $this->dosis);
        $stmt->bindParam(":frecuencia", $this->frecuencia_horas);
        $stmt->bindParam(":duracion", $this->duracion_dias);
        $stmt->bindParam(":hora_inicio", $this->hora_inicio);
        $stmt->bindParam(":via", $this->via_administracion);
        $stmt->bindParam(":indicaciones", $this->indicaciones);
        $stmt->bindParam(":id", $this->id_medicamento);

        return $stmt->execute();
    }

    /**
     * Elimina un medicamento por su ID.
     * Si se proporciona $id_paciente, valida que pertenezca a dicho paciente.
     *
     * @param int|null $id_paciente ID del paciente propietario opcional.
     * @return bool
     */
    public function eliminar(?int $id_paciente = null) {
        if ($id_paciente !== null && !$this->perteneceAPaciente((int) $this->id_medicamento, $id_paciente)) {
            return false;
        }

        $query = "DELETE FROM " . $this->table_name . " WHERE id_medicamento = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id_medicamento);

        return $stmt->execute();
    }
}
?>
