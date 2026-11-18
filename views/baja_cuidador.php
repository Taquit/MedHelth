<?php
include_once __DIR__ . '/../includes/auth.php';

requireLogin('login.html');
$activePage = 'perfil';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dar de Baja - Cuidate+</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/perfil_cuidador.css">
    <link rel="stylesheet" href="../CSS/baja_cuidador.css">
</head>
<body>

    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <div class="background-content">
        <main class="profile-container">
            <aside class="profile-sidebar">
                <a href="perfil.php" class="sidebar-btn">Datos Paciente</a>
                <button class="sidebar-btn active">Cuidador</button>
            </aside>
            <section class="profile-card">
                <h2>Datos del Cuidador</h2>
                <div class="profile-layout" style="filter: blur(4px);">
                    <div class="avatar-column">
                        <div class="avatar-circle"></div>
                    </div>
                    <div class="form-column">
                        <div class="form-group"><label>Nombre</label><input type="text" readonly></div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <div class="confirmation-overlay" style="display:flex;">
        <div class="confirm-card">
            <h3>¿Estás seguro que deseas dar de baja al cuidador actual?</h3>
            <p style="font-size: 0.9rem; color: #666; margin-bottom: 20px;">
                Esta acción moverá al cuidador al historial y dejará de tener acceso a tus datos.
            </p>
            
            <div class="confirm-buttons">
                <a href="../controllers/procesar_baja_cuidador.php" class="btn-confirm btn-yes">Sí, dar de baja</a>
                <a href="perfil_cuidador.php" class="btn-confirm btn-no">No, cancelar</a>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
