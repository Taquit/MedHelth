<?php
include_once __DIR__ . '/../includes/auth.php';

requireLogin('login.html');

$database = new Database();
$db = $database->getConnection();

$datos = obtenerPacienteActual($db);

$nombre = $datos['nombre'] ?? '';
$paterno = $datos['apellido_paterno'] ?? '';
$materno = $datos['apellido_materno'] ?? '';
$correo = $datos['correo'] ?? ($_SESSION['correo'] ?? '');
$exito = $_GET['exito'] ?? '';
$activePage = 'perfil';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi perfil - MedHealth</title>
    <link rel="stylesheet" href="../assets/css/medhealth.css">
</head>
<body>

    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <header class="page-header">
        <div class="page-header-text">
            <h1>Mi perfil</h1>
            <p class="cuerpo text-muted">Consulta tus datos de cuenta y administra tu contraseña.</p>
        </div>
    </header>

    <?php if ($exito === 'password'): ?>
        <div class="aviso aviso-exito" role="status" aria-live="polite" style="max-width: 900px;">
            <?php echo medhealthIcon('check', 24); ?>
            <span>Tu contraseña se actualizó correctamente.</span>
        </div>
    <?php endif; ?>

    <section class="tarjeta tarjeta-form tarjeta-900">
        <div class="form-grid-2">
            <div class="campo col-span-2">
                <label for="perfil-nombre">Nombre(s)</label>
                <input type="text" id="perfil-nombre" value="<?php echo htmlspecialchars($nombre); ?>" readonly>
            </div>

            <div class="campo">
                <label for="perfil-paterno">Apellido paterno</label>
                <input type="text" id="perfil-paterno" value="<?php echo htmlspecialchars($paterno); ?>" readonly>
            </div>

            <div class="campo">
                <label for="perfil-materno">Apellido materno</label>
                <input type="text" id="perfil-materno" value="<?php echo htmlspecialchars($materno); ?>" readonly>
            </div>

            <div class="campo col-span-2">
                <label for="perfil-correo">Correo electrónico</label>
                <input type="email" id="perfil-correo" value="<?php echo htmlspecialchars($correo); ?>" readonly>
            </div>
        </div>

        <div class="form-actions-row" style="margin-top: 28px;">
            <a href="cambiar_contrasena.php" class="btn btn-secundario">
                <?php echo medhealthIcon('lock', 24); ?>
                <span>Cambiar contraseña</span>
            </a>
            <a href="../controllers/logout.php" class="btn btn-peligro-contorno">
                <?php echo medhealthIcon('log-out', 24); ?>
                <span>Cerrar sesión</span>
            </a>
        </div>
    </section>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
