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
        throw new Exception("No se encontró el paciente activo.");
    }

    if ($cuidador->darBaja($id_paciente)) {
        header("Location: ../views/perfil_cuidador.php?exito=baja");
        exit;
    } else {
        throw new Exception("No se pudo procesar la baja en la base de datos.");
    }
} catch (Exception $e) {
    header("Location: ../views/perfil_cuidador.php?error=baja");
    exit;
}
?>
