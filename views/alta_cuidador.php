<?php
include_once __DIR__ . '/../includes/auth.php';

requireLogin('login.html');

$error = $_GET['error'] ?? '';
$mensajeError = '';
if ($error === 'correo_duplicado') {
    $mensajeError = 'El correo ingresado para el cuidador ya existe en el sistema.';
} elseif ($error === 'registro') {
    $mensajeError = 'Ocurrió un problema al guardar al cuidador. Verifica los datos e inténtalo de nuevo.';
}

$activePage = 'cuidador';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar cuidador - MedHealth</title>
    <link rel="stylesheet" href="../assets/css/medhealth.css">
</head>
<body>

    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <header class="page-header">
        <div class="page-header-text">
            <h1>Registrar nuevo cuidador</h1>
            <p class="cuerpo text-muted">Captura los datos de contacto y crea su cuenta de acceso.</p>
        </div>
    </header>

    <section class="tarjeta tarjeta-form tarjeta-900">
        <div class="aviso aviso-alerta" style="margin-bottom: 24px;">
            <?php echo medhealthIcon('alert-triangle', 24); ?>
            <span>Si ya tienes un cuidador activo, se dará de baja automáticamente al registrar uno nuevo.</span>
        </div>

        <?php if ($mensajeError !== ''): ?>
            <div class="aviso aviso-error" role="alert" aria-live="polite" style="margin-bottom: 24px;">
                <?php echo medhealthIcon('alert-triangle', 24); ?>
                <span><?php echo htmlspecialchars($mensajeError); ?></span>
            </div>
        <?php endif; ?>

        <form action="../controllers/registrar_cuidador.php" method="POST" class="form-stack">
            <input type="hidden" name="origen" value="alta">

            <div class="form-grid-2">
                <div class="campo">
                    <label for="nombre">Nombre(s)</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ej. Juan Carlos" required>
                </div>

                <div class="campo">
                    <label for="paterno">Apellido paterno</label>
                    <input type="text" id="paterno" name="paterno" placeholder="Ej. Pérez" required>
                </div>

                <div class="campo">
                    <label for="materno">Apellido materno</label>
                    <input type="text" id="materno" name="materno" placeholder="Ej. López">
                </div>

                <div class="campo">
                    <label for="parentesco">Parentesco</label>
                    <div class="select-wrapper">
                        <select id="parentesco" name="parentesco" required>
                            <option value="">Selecciona una opción</option>
                            <option value="Familiar">Familiar</option>
                            <option value="Enfermero/a">Enfermero/a</option>
                            <option value="Amigo/a">Amigo/a</option>
                            <option value="Otro">Otro</option>
                        </select>
                        <span class="select-chevron"><?php echo medhealthIcon('chevron-down', 24); ?></span>
                    </div>
                </div>

                <div class="campo">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" placeholder="Ej. 55 1234 5678" required>
                </div>

                <div class="campo<?php echo ($error === 'correo_duplicado') ? ' campo-error' : ''; ?>">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" placeholder="cuidador@correo.com" required>
                </div>

                <div class="campo col-span-2">
                    <label for="password">Contraseña para su cuenta</label>
                    <input type="password" id="password" name="password" placeholder="Crea una contraseña temporal" required>
                    <span class="campo-ayuda">Compártela con tu cuidador para que pueda entrar.</span>
                </div>
            </div>

            <div class="form-actions-row">
                <button type="submit" class="btn btn-primario">Guardar cuidador</button>
                <a href="perfil_cuidador.php" class="btn btn-secundario">Cancelar</a>
            </div>
        </form>
    </section>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
