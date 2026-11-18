<?php
include_once __DIR__ . '/../includes/auth.php';
include_once __DIR__ . '/../models/Cuidador.php';

requireLogin('../views/login.html');

$database = new Database();
$db = $database->getConnection();
$cuidador = new Cuidador($db);

try {
    $id_paciente = obtenerIdPacienteActual($db);
    if ($id_paciente === 0) {
        throw new Exception("Error: No se encontró el paciente activo.");
    }

    if ($cuidador->darBaja($id_paciente)) {
        echo "<script>
                alert('El cuidador ha sido dado de baja correctamente.');
                window.location.href = '../views/perfil.php';
              </script>";
        exit;
    } else {
        throw new Exception("No se pudo procesar la baja en la base de datos.");
    }
} catch (Exception $e) {
    echo "<script>
            alert('Ocurrió un error: " . addslashes($e->getMessage()) . "');
            window.location.href = '../views/perfil.php';
          </script>";
}
?>
