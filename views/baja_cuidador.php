<?php
include_once __DIR__ . '/../includes/auth.php';
include_once __DIR__ . '/../models/Cuidador.php';

requireLogin('login.html');

$database = new Database();
$db = $database->getConnection();
$id_paciente = obtenerIdPacienteActual($db);

$cuidadorModel = new Cuidador($db);
$activo = $cuidadorModel->obtenerActivo($id_paciente);
if (!$activo) {
    header("Location: perfil_cuidador.php");
    exit;
}

$nombreCuidador = trim($activo['nombre'] . ' ' . $activo['apellido_paterno']);
$activePage = 'cuidador';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dar de baja cuidador - MedHealth</title>
    <link rel="stylesheet" href="../assets/css/medhealth.css">
</head>
<body>

    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <header class="page-header">
        <div class="page-header-text">
            <h1>Mi cuidador</h1>
            <p class="cuerpo text-muted">Confirmación de baja del cuidador actual.</p>
        </div>
    </header>

    <div class="modal-scrim" role="dialog" aria-modal="true" aria-labelledby="modal-baja-titulo">
        <div class="modal-tarjeta">
            <div>
                <h2 id="modal-baja-titulo" class="text-h1">
                    ¿Dar de baja a <?php echo htmlspecialchars($nombreCuidador); ?>?
                </h2>
                <p class="cuerpo-grande text-muted" style="margin-top: 10px;">
                    Se conservará en tu historial de cuidadores.
                </p>
            </div>

            <div class="form-actions-row">
                <a href="../controllers/procesar_baja_cuidador.php" class="btn btn-peligro">Dar de baja</a>
                <a href="perfil_cuidador.php" class="btn btn-secundario" data-modal-cancel>Cancelar</a>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
