<?php
include_once __DIR__ . '/../config/Database.php';
include_once __DIR__ . '/../includes/helpers.php';
include_once __DIR__ . '/../models/Usuario.php';
include_once __DIR__ . '/../models/RecuperacionPassword.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST['email'])) {
    $database = new Database();
    $db = $database->getConnection();

    $usuario = new Usuario($db);
    $usuario->correo = trim($_POST['email']);

    if ($usuario->emailExiste()) {
        try {
            $recuperacion = new RecuperacionPassword($db);
            $recuperacion->id_usuario = (int) $usuario->id_usuario;
            $recuperacion->crear(60);
        } catch (PDOException $e) {
            // Continua mostrando mensaje estandar si la tabla recuperacion_password aun no ha sido migrada
        }
    }

    header("Location: ../views/login.html?recuperacion=enviada");
    exit;
} else {
    header("Location: ../views/olvide_contrasena.html");
    exit;
}
?>
