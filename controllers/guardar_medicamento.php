<?php
include_once __DIR__ . '/../includes/auth.php';
include_once __DIR__ . '/../includes/helpers.php';
include_once __DIR__ . '/../models/Receta.php';
include_once __DIR__ . '/../models/Medicamento.php';
include_once __DIR__ . '/../models/Notificacion.php';

requireLogin('../views/login.html');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $database = new Database();
    $db = $database->getConnection();

    $id_paciente = obtenerIdPacienteActual($db);
    if ($id_paciente === 0) {
        die("Error: Paciente no encontrado.");
    }

    $receta = new Receta($db);
    $receta->id_paciente = $id_paciente;
    $id_receta_creada = $receta->crear();

    if ($id_receta_creada) {
        $medicamento = new Medicamento($db);
        $medicamento->id_receta = $id_receta_creada;
        $medicamento->nombre = $_POST['nombre'];
        $medicamento->dosis = $_POST['dosis'];
        $medicamento->via_administracion = $_POST['via'];
        $medicamento->hora_inicio = $_POST['hora_inicio'];
        $medicamento->indicaciones = $_POST['indicaciones'];

        $medicamento->frecuencia_horas = convertirFrecuenciaAHoras(
            intval($_POST['frecuencia_cant']),
            $_POST['frecuencia_unidad'] ?? 'horas'
        );

        $medicamento->duracion_dias = convertirDuracionADias(
            intval($_POST['duracion_cant']),
            $_POST['duracion_unidad'] ?? 'dias'
        );

        if ($medicamento->crear()) {
            try {
                $notificacion = new Notificacion($db);
                $notificacion->id_medicamento = (int) $medicamento->id_medicamento;
                $notificacion->id_toma = null;
                $notificacion->fecha_hora_programada = date('Y-m-d') . ' ' . $medicamento->hora_inicio;
                $notificacion->pospuesta_veces = 0;
                $notificacion->estado_alerta = 'pendiente';
                $notificacion->notificar_cuidador = 0;
                $notificacion->crear();
            } catch (PDOException $e) {
                // Continua aunque la tabla notificaciones aun no exista en entornos legacy
            }

            header("Location: ../views/medicamentos.php");
            exit;
        } else {
            echo "Error al guardar el medicamento.";
        }
    } else {
        echo "Error al crear la receta base.";
    }
} else {
    header("Location: ../views/medicamentos.php");
    exit;
}
?>
