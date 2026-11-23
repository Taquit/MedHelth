<?php
include_once __DIR__ . '/../includes/auth.php';
include_once __DIR__ . '/../models/Medicamento.php';

requireLogin('login.html');

$id_medicamento = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id_medicamento <= 0) {
    header("Location: dashboard.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();
$id_paciente = obtenerIdPacienteActual($db);

$medicamentoModel = new Medicamento($db);
$medicamentoModel->id_medicamento = $id_medicamento;
if (!$medicamentoModel->leerUno($id_paciente)) {
    header("Location: dashboard.php");
    exit;
}

$activePage = 'hoy';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motivo de toma omitida - MedHealth</title>
    <link rel="stylesheet" href="../assets/css/medhealth.css">
</head>
<body>

    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <header class="page-header">
        <div class="page-header-text">
            <h1>Registro de toma</h1>
            <p class="cuerpo-grande text-muted">Confirma el motivo por el que no tomaste tu medicamento.</p>
        </div>
    </header>

    <div class="modal-scrim" role="dialog" aria-modal="true" aria-labelledby="modal-titulo-omitida">
        <div class="modal-tarjeta">
            <div>
                <h2 id="modal-titulo-omitida" class="text-h1">¿Por qué no la tomaste?</h2>
                <p class="cuerpo-grande text-muted" style="margin-top: 8px;">
                    Medicamento: <strong><?php echo htmlspecialchars($medicamentoModel->nombre); ?></strong>
                </p>
            </div>

            <form action="../controllers/registrar_toma.php?id=<?php echo $id_medicamento; ?>&status=no_tomado" method="POST" class="form-stack">
                <div class="campo">
                    <label for="motivo">Motivo (opcional)</label>
                    <textarea id="motivo" name="motivo" placeholder="Por ejemplo: se terminó la caja, malestar estomacal, olvido..."></textarea>
                </div>

                <div class="form-actions-row">
                    <button type="submit" class="btn btn-primario">Guardar</button>
                    <a href="dashboard.php" class="btn btn-secundario" data-modal-cancel>Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
