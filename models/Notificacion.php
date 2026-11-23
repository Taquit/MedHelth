<?php
class Notificacion {
    private $conn;
    private $table_name = "notificaciones";

    public $id_notificacion;
    public $id_medicamento;
    public $id_toma;
    public $fecha_hora_programada;
    public $pospuesta_veces;
    public $estado_alerta; // 'pendiente', 'pospuesta', 'atendida', 'vencida'
    public $notificar_cuidador;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Crea una nueva notificacion programada para un medicamento.
     *
     * @return bool
     */
    public function crear(): bool {
        $query = "INSERT INTO " . $this->table_name . "
                  SET id_medicamento = :id_med,
                      id_toma = :id_toma,
                      fecha_hora_programada = :fecha_prog,
                      pospuesta_veces = :pospuesta,
                      estado_alerta = :estado,
                      notificar_cuidador = :notificar";

        $stmt = $this->conn->prepare($query);

        $this->fecha_hora_programada = $this->fecha_hora_programada ?: date('Y-m-d H:i:s');
        $this->pospuesta_veces = $this->pospuesta_veces ?? 0;
        $this->estado_alerta = $this->estado_alerta ?: 'pendiente';
        $this->notificar_cuidador = $this->notificar_cuidador ?? 0;

        $stmt->bindParam(":id_med", $this->id_medicamento, PDO::PARAM_INT);
        if ($this->id_toma === null) {
            $stmt->bindValue(":id_toma", null, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(":id_toma", $this->id_toma, PDO::PARAM_INT);
        }
        $stmt->bindParam(":fecha_prog", $this->fecha_hora_programada);
        $stmt->bindParam(":pospuesta", $this->pospuesta_veces, PDO::PARAM_INT);
        $stmt->bindParam(":estado", $this->estado_alerta);
        $stmt->bindParam(":notificar", $this->notificar_cuidador, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $this->id_notificacion = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    /**
     * Pospone la alerta activa (pendiente o pospuesta) de un medicamento sumando minutos
     * e incrementando pospuesta_veces. Si no existe alerta activa, crea una nueva pospuesta.
     *
     * @param int $id_medicamento ID del medicamento.
     * @param int $minutos Minutos a posponer (por defecto 5).
     * @return bool
     */
    public function posponerPorMedicamento(int $id_medicamento, int $minutos = 5): bool {
        $querySelect = "SELECT id_notificacion, pospuesta_veces
                        FROM " . $this->table_name . "
                        WHERE id_medicamento = :id_med
                          AND estado_alerta IN ('pendiente', 'pospuesta')
                        ORDER BY fecha_hora_programada DESC
                        LIMIT 1";

        $stmtSelect = $this->conn->prepare($querySelect);
        $stmtSelect->bindParam(":id_med", $id_medicamento, PDO::PARAM_INT);
        $stmtSelect->execute();
        $row = $stmtSelect->fetch(PDO::FETCH_ASSOC);

        $nuevaFecha = date('Y-m-d H:i:s', time() + ($minutos * 60));

        if ($row) {
            $queryUpdate = "UPDATE " . $this->table_name . "
                            SET fecha_hora_programada = :fecha_prog,
                                pospuesta_veces = pospuesta_veces + 1,
                                estado_alerta = 'pospuesta'
                            WHERE id_notificacion = :id_notif";

            $stmtUpdate = $this->conn->prepare($queryUpdate);
            $stmtUpdate->bindParam(":fecha_prog", $nuevaFecha);
            $stmtUpdate->bindParam(":id_notif", $row['id_notificacion'], PDO::PARAM_INT);
            return $stmtUpdate->execute();
        }

        $this->id_medicamento = $id_medicamento;
        $this->id_toma = null;
        $this->fecha_hora_programada = $nuevaFecha;
        $this->pospuesta_veces = 1;
        $this->estado_alerta = 'pospuesta';
        $this->notificar_cuidador = 0;

        return $this->crear();
    }

    /**
     * Marca una notificacion activa de un medicamento como 'atendida' y la vincula con id_toma.
     *
     * @param int $id_medicamento ID del medicamento.
     * @param int $id_toma ID del registro en historial_tomas.
     * @return bool
     */
    public function resolverPorMedicamento(int $id_medicamento, int $id_toma): bool {
        $querySelect = "SELECT id_notificacion
                        FROM " . $this->table_name . "
                        WHERE id_medicamento = :id_med
                          AND estado_alerta IN ('pendiente', 'pospuesta')
                        ORDER BY fecha_hora_programada DESC
                        LIMIT 1";

        $stmtSelect = $this->conn->prepare($querySelect);
        $stmtSelect->bindParam(":id_med", $id_medicamento, PDO::PARAM_INT);
        $stmtSelect->execute();
        $row = $stmtSelect->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $queryUpdate = "UPDATE " . $this->table_name . "
                            SET id_toma = :id_toma,
                                estado_alerta = 'atendida'
                            WHERE id_notificacion = :id_notif";

            $stmtUpdate = $this->conn->prepare($queryUpdate);
            $stmtUpdate->bindParam(":id_toma", $id_toma, PDO::PARAM_INT);
            $stmtUpdate->bindParam(":id_notif", $row['id_notificacion'], PDO::PARAM_INT);
            return $stmtUpdate->execute();
        }

        return false;
    }

    /**
     * Obtiene las notificaciones pendientes o pospuestas de un paciente.
     *
     * @param int $id_paciente ID del paciente.
     * @return PDOStatement
     */
    public function leerPendientesPorPaciente(int $id_paciente) {
        $query = "SELECT n.*, m.nombre AS nombre_medicamento, m.dosis
                  FROM " . $this->table_name . " n
                  JOIN medicamentos m ON n.id_medicamento = m.id_medicamento
                  JOIN recetas r ON m.id_receta = r.id_receta
                  WHERE r.id_paciente = :id_paciente
                    AND n.estado_alerta IN ('pendiente', 'pospuesta')
                  ORDER BY n.fecha_hora_programada ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_paciente", $id_paciente, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
}
?>
