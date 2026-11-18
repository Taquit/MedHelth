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
    $id_paciente_actual = obtenerIdPacienteActual($db);
    if ($id_paciente_actual === 0) {
        die("Error: No se encontró el perfil del paciente activo.");
    }

    $usuario->correo = $_POST['email'];
    $usuario->password = $_POST['password'];
    $usuario->rol = 'cuidador';

    if ($usuario->emailExiste()) {
        echo "<script>
                alert('El correo ingresado para el cuidador ya existe.');
                window.history.back();
              </script>";
        exit;
    }

    $db->beginTransaction();

    try {
        if ($usuario->registrar()) {
            $cuidador->id_usuario = $usuario->id_usuario;
            $cuidador->nombre = $_POST['nombre'];
            $cuidador->apellido_paterno = $_POST['paterno'];
            $cuidador->apellido_materno = $_POST['materno'] ?? '';
            $cuidador->telefono = $_POST['telefono'];
            $cuidador->parentesco = $_POST['parentesco'];

            if ($cuidador->crear()) {
                if ($cuidador->asignarPaciente($id_paciente_actual)) {
                    $db->commit();

                    echo "<script>
                            alert('Cuidador registrado correctamente.');
                            window.location.href = '../views/perfil.php';
                          </script>";
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
        echo "<script>
                alert('Ocurrió un error: " . addslashes($e->getMessage()) . "');
                window.history.back();
              </script>";
    }
} else {
    header("Location: ../views/perfil.php");
    exit;
}
?>
