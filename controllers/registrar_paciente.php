<?php
session_start();

include_once __DIR__ . '/../config/Database.php';
include_once __DIR__ . '/../models/Usuario.php';
include_once __DIR__ . '/../models/Paciente.php';

$database = new Database();
$db = $database->getConnection();

$usuario = new Usuario($db);
$paciente = new Paciente($db);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario->correo = trim($_POST['email'] ?? '');
    $usuario->password = $_POST['password'] ?? '';
    $usuario->rol = 'paciente';

    if ($usuario->correo === '' || $usuario->password === '') {
        header("Location: ../views/registro_paciente.html?error=registro");
        exit;
    }

    if ($usuario->emailExiste()) {
        header("Location: ../views/registro_paciente.html?error=correo_duplicado");
        exit;
    }

    if ($usuario->registrar()) {
        $paciente->id_usuario = $usuario->id_usuario;
        $paciente->nombre = trim($_POST['nombres'] ?? '');
        $paciente->apellido_paterno = trim($_POST['paterno'] ?? '');
        $paciente->apellido_materno = trim($_POST['materno'] ?? '');
        $paciente->telefono = trim($_POST['telefono'] ?? '');

        if ($paciente->crear()) {
            $nombrePlano = htmlspecialchars_decode($paciente->nombre, ENT_QUOTES);
            $paternoPlano = htmlspecialchars_decode($paciente->apellido_paterno, ENT_QUOTES);
            $_SESSION['id_usuario'] = $usuario->id_usuario;
            $_SESSION['correo'] = $usuario->correo;
            $_SESSION['rol'] = $usuario->rol;
            $_SESSION['nombre_usuario'] = trim($nombrePlano . ' ' . $paternoPlano);

            header("Location: ../views/pregunta_cuidador.html?nombre=" . urlencode($nombrePlano));
            exit;
        } else {
            header("Location: ../views/registro_paciente.html?error=registro");
            exit;
        }
    } else {
        header("Location: ../views/registro_paciente.html?error=registro");
        exit;
    }
} else {
    header("Location: ../views/registro_paciente.html");
    exit;
}
?>
