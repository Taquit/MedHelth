<?php
include_once __DIR__ . '/../includes/auth.php';
include_once __DIR__ . '/../models/Usuario.php';

requireLogin('../views/login.html');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $database = new Database();
    $db = $database->getConnection();
    $usuario = new Usuario($db);
    $usuario->id_usuario = $_SESSION['id_usuario'];

    $pass_actual = $_POST['current_password'];
    $pass_nueva = $_POST['new_password'];

    $hash_actual_bd = $usuario->obtenerPasswordActual();

    if ($hash_actual_bd && password_verify($pass_actual, $hash_actual_bd)) {
        if ($usuario->actualizarPassword($pass_nueva)) {
            echo "<script>alert('Contraseña actualizada correctamente.'); window.location.href='../views/perfil.php';</script>";
        } else {
            echo "<script>alert('Error al actualizar.'); window.location.href='../views/cambiar_contrasena.php';</script>";
        }
    } else {
        echo "<script>alert('La contraseña actual es incorrecta.'); window.location.href='../views/cambiar_contrasena.php';</script>";
    }
} else {
    header("Location: ../views/cambiar_contrasena.php");
    exit;
}
?>
