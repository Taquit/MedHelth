<?php
include_once __DIR__ . '/../includes/auth.php';
include_once __DIR__ . '/../models/Medicamento.php';
include_once __DIR__ . '/../models/HistorialToma.php';
include_once __DIR__ . '/../models/Notificacion.php';

requireLogin('../views/login.html');

if (isset($_GET['id']) && isset($_GET['status'])) {
    $status = (string) $_GET['status'];
    if ($status !== 'tomado' && $status !== 'no_tomado') {
        header("Location: ../views/dashboard.php");
        exit;
    }

    $database = new Database();
    $db = $database->getConnection();

    $id_paciente = obtenerIdPacienteActual($db);
    $id_medicamento = (int) $_GET['id'];

    $medicamentoModel = new Medicamento($db);
    $medicamentoModel->id_medicamento = $id_medicamento;
    if (!$medicamentoModel->leerUno($id_paciente)) {
        header("Location: ../views/dashboard.php");
        exit;
    }

    $historial = new HistorialToma($db);
    $historial->id_medicamento = $id_medicamento;
    $historial->estado = $status;

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['motivo'])) {
        $motivo = trim((string) $_POST['motivo']);
        $historial->observaciones = ($motivo !== '') ? $motivo : null;
    } else {
        $historial->observaciones = ($status === 'tomado') ? 'Registro rápido desde panel' : null;
    }

    if ($historial->registrar()) {
        try {
            $notificacion = new Notificacion($db);
            $notificacion->resolverPorMedicamento($id_medicamento, (int) $historial->id_toma);
        } catch (PDOException $e) {
            // Continua aunque la tabla notificaciones aun no exista en entornos legacy
        }

        if ($historial->estado === 'tomado') {
            $nombreUsuario = htmlspecialchars_decode(obtenerNombreUsuarioActual($db), ENT_QUOTES);
            $nombreMedicamento = htmlspecialchars_decode((string) $medicamentoModel->nombre, ENT_QUOTES);
            $query = http_build_query([
                'nombre' => $nombreUsuario,
                'medicamento' => $nombreMedicamento,
                'hora' => date('H:i'),
            ]);
            header("Location: ../views/notificacion_tomada.html?" . $query);
        } else {
            header("Location: ../views/dashboard.php");
        }
        exit;
    } else {
        header("Location: ../views/dashboard.php");
        exit;
    }
} else {
    header("Location: ../views/dashboard.php");
    exit;
}
?>
