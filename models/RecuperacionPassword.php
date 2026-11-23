<?php
class RecuperacionPassword {
    private $conn;
    private $table_name = "recuperacion_password";

    public $id_recuperacion;
    public $id_usuario;
    public $token;
    public $fecha_solicitud;
    public $fecha_expiracion;
    public $usado;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Crea un nuevo token de recuperacion para un usuario.
     *
     * @param int $minutosValidez Minutos antes de que expire el token (por defecto 60).
     * @return bool
     */
    public function crear(int $minutosValidez = 60) {
        $query = "INSERT INTO " . $this->table_name . "
                  SET id_usuario = :id_usuario,
                      token = :token,
                      fecha_solicitud = :fecha_solicitud,
                      fecha_expiracion = :fecha_expiracion,
                      usado = 0";

        $stmt = $this->conn->prepare($query);

        if (empty($this->token)) {
            $this->token = bin2hex(random_bytes(32));
        }

        $ahora = time();
        $this->fecha_solicitud = date('Y-m-d H:i:s', $ahora);
        $this->fecha_expiracion = date('Y-m-d H:i:s', $ahora + ($minutosValidez * 60));
        $this->usado = 0;

        $stmt->bindParam(":id_usuario", $this->id_usuario, PDO::PARAM_INT);
        $stmt->bindParam(":token", $this->token);
        $stmt->bindParam(":fecha_solicitud", $this->fecha_solicitud);
        $stmt->bindParam(":fecha_expiracion", $this->fecha_expiracion);

        if ($stmt->execute()) {
            $this->id_recuperacion = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    /**
     * Valida si un token existe, no ha sido usado y no ha expirado.
     * Si es valido, hidrata las propiedades de la instancia.
     *
     * @param string $token Token recibido.
     * @return bool
     */
    public function validarToken(string $token): bool {
        $ahora = date('Y-m-d H:i:s');
        $query = "SELECT * FROM " . $this->table_name . "
                  WHERE token = :token
                    AND usado = 0
                    AND fecha_expiracion >= :ahora
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":token", $token);
        $stmt->bindParam(":ahora", $ahora);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->id_recuperacion = $row['id_recuperacion'];
            $this->id_usuario = $row['id_usuario'];
            $this->token = $row['token'];
            $this->fecha_solicitud = $row['fecha_solicitud'];
            $this->fecha_expiracion = $row['fecha_expiracion'];
            $this->usado = $row['usado'];
            return true;
        }
        return false;
    }

    /**
     * Marca el token actual como utilizado (usado = 1).
     *
     * @return bool
     */
    public function marcarComoUsado(): bool {
        $query = "UPDATE " . $this->table_name . "
                  SET usado = 1
                  WHERE id_recuperacion = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id_recuperacion, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
?>
