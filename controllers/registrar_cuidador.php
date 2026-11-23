<?php
include_once __DIR__ . '/../includes/auth.php';
include_once __DIR__ . '/../models/Usuario.php';
include_once __DIR__ . '/../models/Cuidador.php';

requireLogin('../views/login.html');

$database = new Database();
$db = $database->getConnection();

$usuario = new Usuario($db);
$cuidador = new Cuidador($db);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $origen = ($_POST['origen'] ?? 'alta') === 'inicial' ? 'inicial' : 'alta';
    $vistaOrigen = ($origen === 'inicial')
        ? '../views/registro_cuidador_inicial.html'
        : '../views/alta_cuidador.php';
    $vistaExito = ($origen === 'inicial')
        ? '../views/dashboard.php'
        : '../views/perfil_cuidador.php?exito=1';

    $id_paciente_actual = obtenerIdPacienteActual($db);
    if ($id_paciente_actual === 0) {
        header("Location: " . $vistaOrigen . "?error=registro");
        exit;
    }

    $usuario->correo = trim($_POST['email'] ?? '');
    $usuario->password = $_POST['password'] ?? '';
    $usuario->rol = 'cuidador';

    if ($usuario->correo === '' || $usuario->password === '' || trim($_POST['nombre'] ?? '') === '') {
        header("Location: " . $vistaOrigen . "?error=registro");
        exit;
    }

    if ($usuario->emailExiste()) {
        header("Location: " . $vistaOrigen . "?error=correo_duplicado");
        exit;
    }

    $db->beginTransaction();

    try {
        if ($usuario->registrar()) {
            $cuidador->id_usuario = $usuario->id_usuario;
            $cuidador->nombre = trim($_POST['nombre'] ?? '');
            $cuidador->apellido_paterno = trim($_POST['paterno'] ?? '');
            $cuidador->apellido_materno = trim($_POST['materno'] ?? '');
            $cuidador->telefono = trim($_POST['telefono'] ?? '');
            $cuidador->parentesco = trim($_POST['parentesco'] ?? '');

            if ($cuidador->crear()) {
                if ($cuidador->asignarPaciente($id_paciente_actual)) {
                    $db->commit();
                    header("Location: " . $vistaExito);
                    exit;
                } else {
                    throw new Exception("Error al asignar el cuidador al paciente.");
                }
            } else {
                throw new Exception("Error al guardar datos personales del cuidador.");
            }
        } else {
            throw new Exception("Error al crear el usuario/login.");
        }
    } catch (Exception $e) {
        $db->rollBack();
        header("Location: " . $vistaOrigen . "?error=registro");
        exit;
    }
} else {
    header("Location: ../views/perfil_cuidador.php");
    exit;
}
?>
