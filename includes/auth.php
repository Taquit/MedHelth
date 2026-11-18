<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../config/Database.php';
include_once __DIR__ . '/../models/Paciente.php';

/**
 * Verifica que exista una sesion activa de usuario.
 * Si no existe, redirige a la URL de login indicada y detiene la ejecucion.
 *
 * @param string $redirectUrl Ruta relativa hacia la pagina de login.
 * @return void
 */
function requireLogin(string $redirectUrl = 'login.html'): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['id_usuario'])) {
        header("Location: " . $redirectUrl);
        exit;
    }
}

/**
 * Obtiene los datos del paciente asociado al usuario actualmente en sesion.
 *
 * @param PDO $db Conexion PDO activa.
 * @return array|null Arreglo asociativo con los datos del paciente o null si no existe.
 */
function obtenerPacienteActual(PDO $db): ?array {
    if (!isset($_SESSION['id_usuario'])) {
        return null;
    }
    $paciente = new Paciente($db);
    $paciente->id_usuario = $_SESSION['id_usuario'];
    $stmt = $paciente->leerPorUsuario();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ? $row : null;
}

/**
 * Obtiene el id_paciente del usuario autenticado o 0 si no se encuentra.
 *
 * @param PDO $db Conexion PDO activa.
 * @return int ID del paciente o 0.
 */
function obtenerIdPacienteActual(PDO $db): int {
    $row = obtenerPacienteActual($db);
    return $row ? (int) $row['id_paciente'] : 0;
}
?>
