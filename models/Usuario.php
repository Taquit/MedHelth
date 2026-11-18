<?php
class Usuario {
    private $conn;
    private $table_name = "usuarios";

    public $id_usuario;
    public $correo;
    public $password;
    public $rol;
    public $fecha_registro;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Registra un nuevo usuario con contrasena encriptada en BCRYPT.
     *
     * @return bool
     */
    public function registrar() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET correo = :correo, password = :password, rol = :rol";

        $stmt = $this->conn->prepare($query);

        $this->correo = htmlspecialchars(strip_tags((string) $this->correo));
        $this->rol = htmlspecialchars(strip_tags((string) $this->rol));

        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);

        $stmt->bindParam(":correo", $this->correo);
        $stmt->bindParam(":password", $password_hash);
        $stmt->bindParam(":rol", $this->rol);

        if ($stmt->execute()) {
            $this->id_usuario = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    /**
     * Verifica si el correo ya existe y carga id_usuario, password (hash) y rol.
     *
     * @return bool
     */
    public function emailExiste() {
        $query = "SELECT id_usuario, password, rol FROM " . $this->table_name . " WHERE correo = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->correo);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id_usuario = $row['id_usuario'];
            $this->password = $row['password'];
            $this->rol = $row['rol'];
            return true;
        }
        return false;
    }

    /**
     * Compara una contrasena en texto plano contra el hash almacenado en la instancia.
     *
     * @param string $password_ingresada
     * @return bool
     */
    public function login($password_ingresada) {
        return password_verify($password_ingresada, (string) $this->password);
    }

    /**
     * Actualiza la contrasena del usuario actual.
     *
     * @param string $nuevo_password
     * @return bool
     */
    public function actualizarPassword($nuevo_password) {
        $query = "UPDATE " . $this->table_name . " 
                  SET password = :password 
                  WHERE id_usuario = :id";

        $stmt = $this->conn->prepare($query);
        $password_hash = password_hash($nuevo_password, PASSWORD_BCRYPT);

        $stmt->bindParam(":password", $password_hash);
        $stmt->bindParam(":id", $this->id_usuario);

        return $stmt->execute();
    }

    /**
     * Obtiene el hash de la contrasena actual desde la base de datos.
     *
     * @return string|null
     */
    public function obtenerPasswordActual() {
        $query = "SELECT password FROM " . $this->table_name . " WHERE id_usuario = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_usuario);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['password'] : null;
    }
}
?>
