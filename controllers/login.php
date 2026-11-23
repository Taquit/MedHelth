<?php
session_start();

include_once __DIR__ . '/../config/Database.php';
include_once __DIR__ . '/../models/Usuario.php';
include_once __DIR__ . '/../includes/auth.php';

$database = new Database();
$db = $database->getConnection();

$usuario = new Usuario($db);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($email === '' || $password === '') {
        header("Location: ../views/login.html?error=credenciales");
        exit;
    }

    $usuario->correo = $email;

    if ($usuario->emailExiste()) {
        if ($usuario->login($password)) {
            $_SESSION['id_usuario'] = $usuario->id_usuario;
            $_SESSION['correo'] = $usuario->correo;
            $_SESSION['rol'] = $usuario->rol;

            $pacienteRow = obtenerPacienteActual($db);
            if ($pacienteRow && !empty($pacienteRow['nombre'])) {
                $_SESSION['nombre_usuario'] = htmlspecialchars_decode(
                    trim($pacienteRow['nombre'] . ' ' . ($pacienteRow['apellido_paterno'] ?? '')),
                    ENT_QUOTES
                );
            }

            header("Location: ../views/dashboard.php");
            exit;
        } else {
            header("Location: ../views/login.html?error=credenciales");
            exit;
        }
    } else {
        header("Location: ../views/login.html?error=no_registrado");
        exit;
    }
} else {
    header("Location: ../views/login.html");
    exit;
}
?>
