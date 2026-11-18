<?php
include_once __DIR__ . '/../includes/auth.php';

requireLogin('login.html');

$database = new Database();
$db = $database->getConnection();

$datos = obtenerPacienteActual($db);

$nombre = $datos['nombre'] ?? '';
$paterno = $datos['apellido_paterno'] ?? '';
$materno = $datos['apellido_materno'] ?? '';
$correo = $datos['correo'] ?? '';
$activePage = 'perfil';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Cuidate+</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/perfil.css">
</head>
<body>

    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <main class="profile-container">
        <aside class="profile-sidebar">
            <button class="sidebar-btn active">Datos Paciente</button>
            <a href="perfil_cuidador.php" class="sidebar-btn">Cuidador</a>
        </aside>

        <section class="profile-card">
            <h2>Datos del Paciente</h2>

            <div class="profile-layout">
                <div class="avatar-column">
                    <div class="avatar-circle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </div>
                </div>

                <div class="form-column">
                    <form action="#">
                        <div class="form-group">
                            <label>Nombre(s)</label>
                            <input type="text" value="<?php echo htmlspecialchars($nombre); ?>" readonly>
                        </div>

                        <div class="row-group">
                            <div class="form-group">
                                <label>Apellido Paterno</label>
                                <input type="text" value="<?php echo htmlspecialchars($paterno); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Apellido Materno</label>
                                <input type="text" value="<?php echo htmlspecialchars($materno); ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Correo</label>
                            <input type="email" value="<?php echo htmlspecialchars($correo); ?>" readonly>
                        </div>

                        <div class="profile-actions">
                            <a href="cambiar_contrasena.php" class="link-password">Cambiar contraseña</a>
                            <a href="../controllers/logout.php" class="btn-logout">Cerrar sesión</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
