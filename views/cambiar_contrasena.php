<?php
include_once __DIR__ . '/../includes/auth.php';

requireLogin('login.html');

$error = $_GET['error'] ?? '';
$mensajeError = '';
if ($error === 'no_coincide') {
    $mensajeError = 'La contraseña nueva y su confirmación no coinciden.';
} elseif ($error === 'actual_incorrecta') {
    $mensajeError = 'La contraseña actual es incorrecta.';
} elseif ($error === 'error_actualizar') {
    $mensajeError = 'Ocurrió un problema al guardar tu nueva contraseña. Intenta de nuevo.';
}

$activePage = 'perfil';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar contraseña - MedHealth</title>
    <link rel="stylesheet" href="../assets/css/medhealth.css">
</head>
<body>

    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <header class="page-header">
        <div class="page-header-text">
            <h1>Cambiar contraseña</h1>
            <p class="cuerpo text-muted">Ingresa tu contraseña actual y define una nueva.</p>
        </div>
    </header>

    <section class="tarjeta tarjeta-form tarjeta-560">
        <?php if ($mensajeError !== ''): ?>
            <div class="aviso aviso-error" role="alert" aria-live="polite" style="margin-bottom: 24px;">
                <?php echo medhealthIcon('alert-triangle', 24); ?>
                <span><?php echo htmlspecialchars($mensajeError); ?></span>
            </div>
        <?php endif; ?>

        <form action="../controllers/procesar_cambio_password.php" method="POST" class="form-stack">
            <div class="campo<?php echo ($error === 'actual_incorrecta') ? ' campo-error' : ''; ?>">
                <label for="current_password">Contraseña actual</label>
                <input type="password" id="current_password" name="current_password" placeholder="Ingresa tu contraseña actual" required>
            </div>

            <div class="campo<?php echo ($error === 'no_coincide') ? ' campo-error' : ''; ?>">
                <label for="new_password">Contraseña nueva</label>
                <input type="password" id="new_password" name="new_password" placeholder="Ingresa la nueva contraseña" required>
            </div>

            <div class="campo<?php echo ($error === 'no_coincide') ? ' campo-error' : ''; ?>">
                <label for="confirm_password">Confirmar contraseña nueva</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Repite la nueva contraseña" required>
            </div>

            <div class="form-actions-row">
                <button type="submit" class="btn btn-primario">Guardar contraseña</button>
                <a href="perfil.php" class="btn btn-secundario">Cancelar</a>
            </div>
        </form>
    </section>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
