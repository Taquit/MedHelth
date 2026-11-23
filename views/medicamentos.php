<?php
include_once __DIR__ . '/../includes/auth.php';
include_once __DIR__ . '/../models/Medicamento.php';
include_once __DIR__ . '/../models/HistorialToma.php';

requireLogin('login.html');

$database = new Database();
$db = $database->getConnection();

$id_paciente = obtenerIdPacienteActual($db);

$medicamentoModel = new Medicamento($db);
$stmt_meds = $medicamentoModel->leerPorPaciente($id_paciente);
$num_meds = $stmt_meds->rowCount();

$historialModel = new HistorialToma($db);
$diasTomadosMap = ($id_paciente > 0) ? $historialModel->contarDiasTomadosPorPaciente($id_paciente) : [];

$activePage = 'medicamentos';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis medicamentos - MedHealth</title>
    <link rel="stylesheet" href="../assets/css/medhealth.css">
</head>
<body>

    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <header class="page-header">
        <div class="page-header-text">
            <h1>Mis medicamentos</h1>
            <p class="cuerpo text-muted">Consulta, edita o administra los tratamientos registrados.</p>
        </div>
        <a href="agregar_medicamento.php" class="btn btn-primario">
            <?php echo medhealthIcon('plus', 24); ?>
            <span>Agregar medicamento</span>
        </a>
    </header>

    <?php if ($num_meds > 0): ?>
        <section class="meds-grid-2" aria-label="Listado de medicamentos">
            <?php while ($med = $stmt_meds->fetch(PDO::FETCH_ASSOC)): ?>
                <?php
                    $idMed = (int) $med['id_medicamento'];
                    $duracionDias = max(1, (int) $med['duracion_dias']);
                    $diasTomados = min($diasTomadosMap[$idMed] ?? 0, $duracionDias);
                    $porcentaje = min(100, (int) round(($diasTomados / $duracionDias) * 100));
                    $horaInicio = substr((string) ($med['hora_inicio'] ?? ''), 0, 5);
                ?>
                <article class="tarjeta med-catalog-card">
                    <div class="med-catalog-head">
                        <span class="icon-circle icon-circle-64">
                            <?php echo medhealthIcon('pill', 30); ?>
                        </span>
                        <div>
                            <h2 class="cuerpo-grande-bold"><?php echo htmlspecialchars($med['nombre']); ?></h2>
                            <p class="cuerpo text-muted">
                                <?php echo htmlspecialchars($med['dosis'] . ' · ' . $med['via_administracion']); ?>
                            </p>
                        </div>
                    </div>

                    <div class="progreso-bloque">
                        <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px;">
                            <span class="cuerpo-bold">Progreso</span>
                            <span class="cuerpo-bold" style="color: var(--acento-700);">
                                Día <?php echo $diasTomados; ?> de <?php echo $duracionDias; ?>
                            </span>
                        </div>
                        <div class="progreso-pista" role="progressbar" aria-valuenow="<?php echo $porcentaje; ?>" aria-valuemin="0" aria-valuemax="100" aria-label="Avance de tratamiento">
                            <div class="progreso-avance" style="width: <?php echo $porcentaje; ?>%;"></div>
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <p class="cuerpo">
                            <strong>Frecuencia:</strong> Cada <?php echo (int) $med['frecuencia_horas']; ?> horas
                            <?php if ($horaInicio !== ''): ?>
                                (primera toma <?php echo htmlspecialchars($horaInicio); ?>)
                            <?php endif; ?>
                        </p>
                        <p class="cuerpo">
                            <strong>Indicaciones:</strong> <?php echo htmlspecialchars($med['indicaciones']); ?>
                        </p>
                    </div>

                    <div class="form-actions-row">
                        <a href="editar_medicamento.php?id=<?php echo $idMed; ?>" class="btn btn-secundario">Editar</a>
                        <button type="button"
                                class="btn btn-peligro js-open-delete"
                                data-med-id="<?php echo $idMed; ?>"
                                data-med-name="<?php echo htmlspecialchars(htmlspecialchars_decode((string) $med['nombre'], ENT_QUOTES), ENT_QUOTES, 'UTF-8'); ?>">
                            Eliminar
                        </button>
                    </div>
                </article>
            <?php endwhile; ?>
        </section>
    <?php else: ?>
        <article class="tarjeta empty-state">
            <span class="icon-circle icon-circle-80">
                <?php echo medhealthIcon('pill', 36); ?>
            </span>
            <h2>Aún no tienes medicamentos registrados</h2>
            <p class="cuerpo text-muted">Presiona el botón superior para agregar tu primer tratamiento.</p>
            <a href="agregar_medicamento.php" class="btn btn-primario">
                <?php echo medhealthIcon('plus', 24); ?>
                <span>Agregar medicamento</span>
            </a>
        </article>
    <?php endif; ?>

    <div id="modal-eliminar" class="modal-scrim" role="dialog" aria-modal="true" aria-labelledby="modal-eliminar-titulo" hidden>
        <div class="modal-tarjeta">
            <div>
                <h2 id="modal-eliminar-titulo" class="text-h1">¿Eliminar medicamento?</h2>
                <p class="cuerpo-grande text-muted" style="margin-top: 10px;">
                    Esta acción no se puede deshacer.
                </p>
            </div>
            <div class="form-actions-row">
                <a id="btn-confirmar-eliminar" href="#" class="btn btn-peligro">Eliminar</a>
                <button type="button" id="btn-cancelar-eliminar" class="btn btn-secundario" data-modal-cancel>Cancelar</button>
            </div>
        </div>
    </div>

    <script>
    (function () {
        var modal = document.getElementById('modal-eliminar');
        var titulo = document.getElementById('modal-eliminar-titulo');
        var confirmarBtn = document.getElementById('btn-confirmar-eliminar');
        var cancelarBtn = document.getElementById('btn-cancelar-eliminar');
        var lastTrigger = null;

        document.querySelectorAll('.js-open-delete').forEach(function (btn) {
            btn.addEventListener('click', function () {
                lastTrigger = btn;
                var id = btn.getAttribute('data-med-id');
                var name = btn.getAttribute('data-med-name') || 'este medicamento';
                titulo.textContent = '¿Eliminar ' + name + '?';
                confirmarBtn.setAttribute('href', '../controllers/eliminar_medicamento.php?id=' + encodeURIComponent(id));
                modal.hidden = false;
                cancelarBtn.focus();
            });
        });

        cancelarBtn.addEventListener('click', function () {
            modal.hidden = true;
            if (lastTrigger) {
                lastTrigger.focus();
            }
        });
    })();
    </script>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
