<?php
include_once __DIR__ . '/../includes/auth.php';

requireLogin('login.html');

$id_medicamento = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id_medicamento <= 0) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motivo no tomado - Cuidate+</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/dashboard.css">
    <link rel="stylesheet" href="../CSS/notificaciones.css">
</head>
<body>
    <div class="background-content"></div>

    <div class="notification-overlay">
        <div class="notification-card">
            <h3>Da los motivos de porqué no se ingirió.</h3>

            <form action="../controllers/registrar_toma.php?id=<?php echo $id_medicamento; ?>&status=no_tomado" method="POST">
                <textarea class="reason-input" name="motivo" placeholder="Indica los motivos..." rows="4" required></textarea>
                <button type="submit" class="btn-save-reason">Guardar</button>
            </form>
            
            <br>
            <a href="dashboard.php" style="color: #666; text-decoration: none; font-size: 0.9rem;">Cancelar</a>
        </div>
    </div>
</body>
</html>
