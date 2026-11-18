<?php
include_once __DIR__ . '/../includes/auth.php';

requireLogin('login.html');

$correo_usuario = $_SESSION['correo'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña - Cuidate+</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/cambiar_contrasena.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">Cuidate+</div>
    </nav>

    <main class="overlay-container">
        <div class="modal-card">
            <a href="perfil.php" class="close-btn">&#10005;</a>
            <div class="modal-header">
                <h2>Cambiar contraseña</h2>
            </div>

            <form action="../controllers/procesar_cambio_password.php" method="POST">
                <div class="form-group">
                    <label for="email">Confirme su correo</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($correo_usuario); ?>" readonly style="background-color: #e9ecef;">
                </div>

                <div class="form-group">
                    <label for="current-pass">Contraseña actual</label>
                    <input type="password" id="current-pass" name="current_password" placeholder="Contraseña actual" required>
                </div>

                <div class="form-group">
                    <label for="new-pass">Nueva Contraseña</label>
                    <input type="password" id="new-pass" name="new_password" placeholder="Nueva contraseña" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-save">Finalizar cambio</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
