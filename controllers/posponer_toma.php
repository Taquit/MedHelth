<?php
include_once __DIR__ . '/../includes/auth.php';
include_once __DIR__ . '/../models/Medicamento.php';
include_once __DIR__ . '/../models/Notificacion.php';

requireLogin('../views/login.html');

if (!isset($_GET['id'])) {
    header("Location: ../views/dashboard.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();

$id_paciente = obtenerIdPacienteActual($db);
$id_medicamento = (int) $_GET['id'];

$medicamentoModel = new Medicamento($db);
if (!$medicamentoModel->perteneceAPaciente($id_medicamento, $id_paciente)) {
    header("Location: ../views/dashboard.php");
    exit;
}

try {
    $notificacion = new Notificacion($db);
    $notificacion->posponerPorMedicamento($id_medicamento, 5);
} catch (PDOException $e) {
    // Continua hacia la vista de confirmacion aunque la tabla notificaciones no exista aun
}

$nuevaHora = date('H:i', time() + 300);
header("Location: ../views/notificacion_posponer.html?hora=" . urlencode($nuevaHora));
exit;
?>
