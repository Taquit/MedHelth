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
    $usuario->correo = $_POST['email'];
    $usuario->password = $_POST['password'];
    $usuario->rol = 'paciente';

    if ($usuario->emailExiste()) {
        echo "<script>
                alert('El correo ya está registrado. Intenta con otro.');
                window.location.href = '../views/registro_paciente.html';
              </script>";
        exit;
    }

    if ($usuario->registrar()) {
        $paciente->id_usuario = $usuario->id_usuario;
        $paciente->nombre = $_POST['nombres'];
        $paciente->apellido_paterno = $_POST['paterno'];
        $paciente->apellido_materno = $_POST['materno'];
        $paciente->telefono = isset($_POST['telefono']) ? $_POST['telefono'] : '';

        if ($paciente->crear()) {
            $_SESSION['id_usuario'] = $usuario->id_usuario;
            $_SESSION['correo'] = $usuario->correo;
            $_SESSION['rol'] = $usuario->rol;

            header("Location: ../views/pregunta_cuidador.html");
            exit;
        } else {
            echo "Error al guardar datos del paciente.";
        }
    } else {
        echo "Error al registrar usuario.";
    }
} else {
    header("Location: ../views/registro_paciente.html");
}
?>
