<?php
class Database {
    // Cambia estos datos por los de tu servidor local (XAMPP/WAMP)
    private $host = "localhost";
    private $db_name = "seguimiento_medicamentos";
    private $username = "root";
    private $password = ""; // En XAMPP suele ser vacío, en MAMP es "root"
    public $conn;

    // Método para obtener la conexión
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            // Configurar codificación de caracteres
            $this->conn->exec("set names utf8mb4");
            // Configurar manejo de errores
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>