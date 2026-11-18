<?php
include_once __DIR__ . '/../includes/auth.php';
include_once __DIR__ . '/../models/Medicamento.php';

requireLogin('../views/login.html');

if (isset($_GET['id'])) {
    $database = new Database();
    $db = $database->getConnection();

    $id_paciente = obtenerIdPacienteActual($db);
    $medicamento = new Medicamento($db);
    $medicamento->id_medicamento = $_GET['id'];

    if ($medicamento->eliminar($id_paciente)) {
        header("Location: ../views/medicamentos.php");
        exit;
    } else {
        echo "Error: No se pudo eliminar el medicamento.";
    }
} else {
    header("Location: ../views/medicamentos.php");
    exit;
}
?>
