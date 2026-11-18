<?php
include_once __DIR__ . '/../includes/auth.php';
include_once __DIR__ . '/../models/Medicamento.php';
include_once __DIR__ . '/../models/Notificacion.php';

requireLogin('../views/login.html');

if (isset($_GET['id'])) {
    $database = new Database();
    $db = $database->getConnection();

    $id_paciente = obtenerIdPacienteActual($db);
    $id_medicamento = (int) $_GET['id'];

    $medicamentoModel = new Medicamento($db);
    if ($medicamentoModel->perteneceAPaciente($id_medicamento, $id_paciente)) {
        try {
            $notificacion = new Notificacion($db);
            $notificacion->posponerPorMedicamento($id_medicamento, 5);
        } catch (PDOException $e) {
            // Continua hacia la vista de confirmacion aunque la tabla notificaciones no exista aun
        }
    }
}

header("Location: ../views/notificacion_posponer.html");
exit;
?>
