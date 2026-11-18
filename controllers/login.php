<?php
session_start();

include_once __DIR__ . '/../config/Database.php';
include_once __DIR__ . '/../models/Usuario.php';

$database = new Database();
$db = $database->getConnection();

$usuario = new Usuario($db);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = isset($_POST['email']) ? $_POST['email'] : die();
    $password = isset($_POST['password']) ? $_POST['password'] : die();

    $usuario->correo = $email;

    if ($usuario->emailExiste()) {
        if ($usuario->login($password)) {
            $_SESSION['id_usuario'] = $usuario->id_usuario;
            $_SESSION['correo'] = $usuario->correo;
            $_SESSION['rol'] = $usuario->rol;

            header("Location: ../views/dashboard.php");
            exit;
        } else {
            echo "<script>
                    alert('Contraseña incorrecta.');
                    window.location.href = '../views/login.html';
                  </script>";
        }
    } else {
        echo "<script>
                alert('El correo no está registrado.');
                window.location.href = '../views/login.html';
              </script>";
    }
} else {
    header("Location: ../views/login.html");
}
?>
