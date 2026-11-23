<?php
include_once __DIR__ . '/../includes/auth.php';
include_once __DIR__ . '/../models/Cuidador.php';

requireLogin('login.html');

$database = new Database();
$db = $database->getConnection();

$id_paciente = obtenerIdPacienteActual($db);

$cuidadorModel = new Cuidador($db);
$activo = $cuidadorModel->obtenerActivo($id_paciente);
$stmt_historial = $cuidadorModel->obtenerHistorial($id_paciente);
$historial = $stmt_historial->fetchAll(PDO::FETCH_ASSOC);

$exito = $_GET['exito'] ?? '';
$error = $_GET['error'] ?? '';
$activePage = 'cuidador';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi cuidador - MedHealth</title>
    <link rel="stylesheet" href="../assets/css/medhealth.css">
</head>
<body>

    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <header class="page-header">
        <div class="page-header-text">
            <h1>Mi cuidador</h1>
            <p class="cuerpo text-muted">Consulta a tu cuidador activo y el historial de cuidadores asignados.</p>
        </div>
        <a href="alta_cuidador.php" class="btn btn-primario">
            <?php echo medhealthIcon('plus', 24); ?>
            <span>Registrar nuevo cuidador</span>
        </a>
    </header>

    <?php if ($exito === '1'): ?>
        <div class="aviso aviso-exito" role="status" aria-live="polite">
            <?php echo medhealthIcon('check', 24); ?>
            <span>Cuidador registrado y asignado correctamente.</span>
        </div>
    <?php elseif ($exito === 'baja'): ?>
        <div class="aviso aviso-info" role="status" aria-live="polite">
            <?php echo medhealthIcon('check', 24); ?>
            <span>El cuidador fue dado de baja y se conserva en tu historial.</span>
        </div>
    <?php elseif ($error === 'baja'): ?>
        <div class="aviso aviso-error" role="alert" aria-live="polite">
            <?php echo medhealthIcon('alert-triangle', 24); ?>
            <span>No se pudo procesar la baja del cuidador. Inténtalo de nuevo.</span>
        </div>
    <?php endif; ?>

    <?php if ($activo): ?>
        <?php
            $nombreCompletoActivo = trim(
                $activo['nombre'] . ' ' . $activo['apellido_paterno'] . ' ' . ($activo['apellido_materno'] ?? '')
            );
            $iniciales = obtenerIniciales($nombreCompletoActivo);
            $desdeFecha = formatearFechaEspanol($activo['fecha_inicio'] ?? null);
        ?>
        <section class="tarjeta" aria-label="Cuidador activo">
            <div class="caregiver-card-layout">
                <div class="caregiver-identity">
                    <div class="avatar-iniciales" aria-hidden="true">
                        <?php echo htmlspecialchars($iniciales); ?>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                            <h2><?php echo htmlspecialchars($nombreCompletoActivo); ?></h2>
                            <span class="chip chip-exito">
                                <?php echo medhealthIcon('check', 18); ?>
                                <span>Activo</span>
                            </span>
                        </div>
                        <p class="cuerpo text-muted">
                            <?php echo htmlspecialchars($activo['parentesco'] ?? ''); ?> · Desde <?php echo htmlspecialchars($desdeFecha); ?>
                        </p>
                    </div>
                </div>

                <div class="med-day-actions">
                    <a href="alta_cuidador.php" class="btn btn-primario">Registrar nuevo cuidador</a>
                    <a href="baja_cuidador.php" class="btn btn-peligro">Dar de baja</a>
                </div>
            </div>

            <div class="caregiver-details-grid">
                <div class="detail-item">
                    <?php echo medhealthIcon('phone', 24); ?>
                    <div>
                        <span class="cuerpo-bold">Teléfono: </span>
                        <span><?php echo htmlspecialchars($activo['telefono'] ?? ''); ?></span>
                    </div>
                </div>
                <div class="detail-item">
                    <?php echo medhealthIcon('mail', 24); ?>
                    <div>
                        <span class="cuerpo-bold">Correo: </span>
                        <span><?php echo htmlspecialchars($activo['correo'] ?? ''); ?></span>
                    </div>
                </div>
            </div>
        </section>
    <?php else: ?>
        <section class="tarjeta empty-state" aria-label="Sin cuidador activo">
            <span class="icon-circle icon-circle-80">
                <?php echo medhealthIcon('heart', 36); ?>
            </span>
            <h2>Aún no tienes un cuidador activo</h2>
            <p class="cuerpo text-muted">Puedes registrar a un familiar o persona de confianza para acompañarte.</p>
            <a href="alta_cuidador.php" class="btn btn-primario">Registrar cuidador</a>
        </section>
    <?php endif; ?>

    <section style="display: flex; flex-direction: column; gap: 16px;">
        <h2>Historial de cuidadores</h2>

        <?php if (count($historial) > 0): ?>
            <div class="tabla-contenedor">
                <div class="tabla-scroll">
                    <table class="tabla">
                        <thead>
                            <tr>
                                <th>Cuidador</th>
                                <th>Inicio</th>
                                <th>Fin</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historial as $hist): ?>
                                <?php
                                    $esAlta = ($hist['fecha_fin'] === null);
                                    $nombreHist = trim($hist['nombre'] . ' ' . $hist['apellido_paterno']);
                                ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($nombreHist); ?></strong></td>
                                    <td><?php echo htmlspecialchars(formatearFechaEspanol($hist['fecha_inicio'])); ?></td>
                                    <td><?php echo $esAlta ? '-' : htmlspecialchars(formatearFechaEspanol($hist['fecha_fin'])); ?></td>
                                    <td>
                                        <?php if ($esAlta): ?>
                                            <span class="chip chip-exito">
                                                <?php echo medhealthIcon('check', 18); ?>
                                                <span>Alta</span>
                                            </span>
                                        <?php else: ?>
                                            <span class="chip chip-error">
                                                <?php echo medhealthIcon('x', 18); ?>
                                                <span>Baja</span>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="tarjeta">
                <p class="cuerpo text-muted">No hay historial de cuidadores disponible.</p>
            </div>
        <?php endif; ?>
    </section>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
