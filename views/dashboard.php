<?php
include_once __DIR__ . '/../includes/auth.php';
include_once __DIR__ . '/../models/Medicamento.php';
include_once __DIR__ . '/../models/HistorialToma.php';

requireLogin('login.html');

$database = new Database();
$db = $database->getConnection();

$row_paciente = obtenerPacienteActual($db);
if ($row_paciente) {
    $id_paciente = (int) $row_paciente['id_paciente'];
    $nombre_mostrar = $row_paciente['nombre'];
} else {
    $id_paciente = 0;
    $nombre_mostrar = 'Usuario';
}

$medicamentoModel = new Medicamento($db);
$stmt_meds = $medicamentoModel->leerPorPaciente($id_paciente);
$medicamentos = $stmt_meds->fetchAll(PDO::FETCH_ASSOC);
$num_meds = count($medicamentos);

$historialModel = new HistorialToma($db);
$tomasHoy = ($id_paciente > 0) ? $historialModel->obtenerTomasHoyPorPaciente($id_paciente) : [];

$confirmadasHoy = 0;
$pendientesHoy = 0;
foreach ($medicamentos as $medItem) {
    $estadoItem = $tomasHoy[(int) $medItem['id_medicamento']] ?? null;
    if ($estadoItem === 'tomado') {
        $confirmadasHoy++;
    } else {
        $pendientesHoy++;
    }
}

$saludo = obtenerSaludoHora();
$fecha_actual = obtenerFechaActualEspanol();
$activePage = 'hoy';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoy - MedHealth</title>
    <link rel="stylesheet" href="../assets/css/medhealth.css">
</head>
<body>

    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <header class="page-header">
        <div class="page-header-text">
            <h1><?php echo htmlspecialchars($saludo . ', ' . $nombre_mostrar); ?></h1>
            <p class="cuerpo-grande text-muted">Hoy es <?php echo htmlspecialchars($fecha_actual); ?></p>
        </div>
        <a href="agregar_medicamento.php" class="btn btn-primario">
            <?php echo medhealthIcon('plus', 24); ?>
            <span>Agregar medicamento</span>
        </a>
    </header>

    <?php if ($num_meds > 0): ?>
        <section class="day-summary-ledger" aria-label="Resumen del día">
            <div class="ledger-cell">
                <span>Tomas de hoy</span>
                <strong><?php echo $num_meds; ?></strong>
            </div>
            <div class="ledger-cell is-ok">
                <span>Confirmadas</span>
                <strong><?php echo $confirmadasHoy; ?></strong>
            </div>
            <div class="ledger-cell is-pending">
                <span>Por confirmar</span>
                <strong><?php echo $pendientesHoy; ?></strong>
            </div>
        </section>

        <section class="timetable-list" aria-label="Horario de medicamentos del día">
            <?php $primeraPendienteMarcada = false; ?>
            <?php foreach ($medicamentos as $med): ?>
                <?php
                    $idMed = (int) $med['id_medicamento'];
                    $estadoHoy = $tomasHoy[$idMed] ?? null;
                    $estaTomadaHoy = ($estadoHoy === 'tomado');
                    $esOmitidaHoy = ($estadoHoy === 'no_tomado');
                    $esPendienteDestacada = (!$estaTomadaHoy && !$primeraPendienteMarcada);
                    if ($esPendienteDestacada) {
                        $primeraPendienteMarcada = true;
                    }
                    $horaToma = substr((string) ($med['hora_inicio'] ?? ''), 0, 5);
                    $rowClass = 'timetable-row';
                    if ($estaTomadaHoy) {
                        $rowClass .= ' is-done';
                    } elseif ($esPendienteDestacada) {
                        $rowClass .= ' is-due';
                    }
                ?>
                <article class="<?php echo $rowClass; ?>">
                    <div class="timetable-hour">
                        <?php if ($horaToma !== ''): ?>
                            <?php echo htmlspecialchars($horaToma); ?>
                        <?php else: ?>
                            <span class="timetable-hour-empty">Sin hora</span>
                        <?php endif; ?>
                    </div>

                    <div class="timetable-info">
                        <h2 class="timetable-med-title"><?php echo htmlspecialchars($med['nombre']); ?></h2>
                        <p class="cuerpo text-muted">
                            <?php echo htmlspecialchars($med['dosis'] . ' · ' . $med['via_administracion']); ?>
                        </p>
                        <?php if (!empty($med['indicaciones'])): ?>
                            <p class="cuerpo"><?php echo htmlspecialchars($med['indicaciones']); ?></p>
                        <?php endif; ?>
                        <?php if ($esPendienteDestacada && !$esOmitidaHoy): ?>
                            <div>
                                <span class="chip chip-alerta">
                                    <?php echo medhealthIcon('clock', 18); ?>
                                    <span>Toma pendiente</span>
                                </span>
                            </div>
                        <?php elseif ($esOmitidaHoy): ?>
                            <div>
                                <span class="chip chip-error">
                                    <?php echo medhealthIcon('x', 18); ?>
                                    <span>Omitido hoy</span>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="timetable-actions">
                        <?php if ($estaTomadaHoy): ?>
                            <span class="chip chip-exito" aria-live="polite">
                                <?php echo medhealthIcon('check', 20); ?>
                                <span>Tomado hoy</span>
                            </span>
                        <?php else: ?>
                            <a href="../controllers/registrar_toma.php?id=<?php echo $idMed; ?>&status=tomado"
                               class="btn btn-confirmar">
                                <?php echo medhealthIcon('check', 22); ?>
                                <span>Se tomó</span>
                            </a>

                            <a href="../controllers/posponer_toma.php?id=<?php echo $idMed; ?>"
                               class="btn btn-secundario">
                                <?php echo medhealthIcon('clock', 22); ?>
                                <span>Posponer 5 min</span>
                            </a>

                            <a href="notificacion_no_tomada.php?id=<?php echo $idMed; ?>"
                               class="btn btn-peligro-contorno">
                                <?php echo medhealthIcon('x', 22); ?>
                                <span>No se tomó</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    <?php else: ?>
        <article class="tarjeta empty-state">
            <span class="icon-circle icon-circle-80">
                <?php echo medhealthIcon('pill', 36); ?>
            </span>
            <h2>Hoy no tienes medicinas pendientes</h2>
            <p class="cuerpo text-muted">Agrega tu primer medicamento para ver aquí tu horario diario con letras grandes y botones claros.</p>
            <a href="agregar_medicamento.php" class="btn btn-primario">
                <?php echo medhealthIcon('plus', 24); ?>
                <span>Agregar medicamento</span>
            </a>
        </article>
    <?php endif; ?>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
