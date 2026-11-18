<?php
include_once __DIR__ . '/../includes/auth.php';
include_once __DIR__ . '/../includes/helpers.php';
include_once __DIR__ . '/../models/Medicamento.php';

requireLogin('../views/login.html');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $database = new Database();
    $db = $database->getConnection();

    $id_paciente = obtenerIdPacienteActual($db);
    $medicamento = new Medicamento($db);

    $medicamento->id_medicamento = $_POST['id_medicamento'];
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

    if ($medicamento->actualizar($id_paciente)) {
        header("Location: ../views/medicamentos.php");
        exit;
    } else {
        echo "Error al actualizar el medicamento.";
    }
} else {
    header("Location: ../views/medicamentos.php");
    exit;
}
?>
