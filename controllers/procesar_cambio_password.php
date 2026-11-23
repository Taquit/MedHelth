<?php
include_once __DIR__ . '/../includes/auth.php';
include_once __DIR__ . '/../models/Usuario.php';

requireLogin('../views/login.html');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $database = new Database();
    $db = $database->getConnection();
    $usuario = new Usuario($db);
    $usuario->id_usuario = $_SESSION['id_usuario'];

    $pass_actual = $_POST['current_password'] ?? '';
    $pass_nueva = $_POST['new_password'] ?? '';
    $pass_confirm = $_POST['confirm_password'] ?? null;

    if ($pass_actual === '' || $pass_nueva === '') {
        header("Location: ../views/cambiar_contrasena.php?error=error_actualizar");
        exit;
    }

    if ($pass_confirm !== null && $pass_nueva !== $pass_confirm) {
        header("Location: ../views/cambiar_contrasena.php?error=no_coincide");
        exit;
    }

    $hash_actual_bd = $usuario->obtenerPasswordActual();

    if ($hash_actual_bd && password_verify($pass_actual, $hash_actual_bd)) {
        if ($usuario->actualizarPassword($pass_nueva)) {
            header("Location: ../views/perfil.php?exito=password");
            exit;
        } else {
            header("Location: ../views/cambiar_contrasena.php?error=error_actualizar");
            exit;
        }
    } else {
        header("Location: ../views/cambiar_contrasena.php?error=actual_incorrecta");
        exit;
    }
} else {
    header("Location: ../views/cambiar_contrasena.php");
    exit;
}
?>
