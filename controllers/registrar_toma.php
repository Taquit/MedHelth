<?php
include_once __DIR__ . '/../includes/auth.php';
include_once __DIR__ . '/../models/Medicamento.php';
include_once __DIR__ . '/../models/HistorialToma.php';
include_once __DIR__ . '/../models/Notificacion.php';

requireLogin('../views/login.html');

if (isset($_GET['id']) && isset($_GET['status'])) {
    $database = new Database();
    $db = $database->getConnection();

    $id_paciente = obtenerIdPacienteActual($db);
    $id_medicamento = (int) $_GET['id'];

    $medicamentoModel = new Medicamento($db);
    if (!$medicamentoModel->perteneceAPaciente($id_medicamento, $id_paciente)) {
        header("Location: ../views/dashboard.php");
        exit;
    }

    $historial = new HistorialToma($db);
    $historial->id_medicamento = $id_medicamento;
    $historial->estado = $_GET['status'];

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['motivo'])) {
        $historial->observaciones = $_POST['motivo'];
    } else {
        $historial->observaciones = "Registro rápido desde Dashboard";
    }

    if ($historial->registrar()) {
        try {
            $notificacion = new Notificacion($db);
            $notificacion->resolverPorMedicamento($id_medicamento, (int) $historial->id_toma);
        } catch (PDOException $e) {
            // Continua aunque la tabla notificaciones aun no exista en entornos legacy
        }

        if ($historial->estado === 'tomado') {
            header("Location: ../views/notificacion_tomada.html");
        } else {
            header("Location: ../views/dashboard.php");
        }
        exit;
    } else {
        echo "Error al registrar la toma.";
    }
} else {
    header("Location: ../views/dashboard.php");
    exit;
}
?>
