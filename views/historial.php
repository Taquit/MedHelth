<?php
include_once __DIR__ . '/../includes/auth.php';
include_once __DIR__ . '/../models/HistorialToma.php';

requireLogin('login.html');

$database = new Database();
$db = $database->getConnection();

$id_paciente = obtenerIdPacienteActual($db);

$historialModel = new HistorialToma($db);
$stmt_historial = $historialModel->leerPorPaciente($id_paciente);
$num_registros = $stmt_historial->rowCount();
$activePage = 'historial';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial - MedHealth</title>
    <link rel="stylesheet" href="../assets/css/medhealth.css">
</head>
<body>

    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <header class="page-header">
        <div class="page-header-text">
            <h1>Historial de tomas</h1>
            <p class="cuerpo text-muted">Consulta todas tus tomas registradas, de la más reciente a la más antigua.</p>
        </div>
        <a href="reporte.php" class="btn btn-primario">
            <?php echo medhealthIcon('file-text', 24); ?>
            <span>Ver reporte PDF</span>
        </a>
    </header>

    <?php if ($num_registros > 0): ?>
        <div class="tabla-contenedor">
            <div class="tabla-scroll">
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Medicamento</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Observaciones</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $stmt_historial->fetch(PDO::FETCH_ASSOC)): ?>
                            <?php
                                $timestamp = strtotime($row['fecha_hora']);
                                $fecha_formato = formatearFechaEspanol($row['fecha_hora']);
                                $hora_formato = date('H:i', $timestamp);
                                $esTomado = ($row['estado'] === 'tomado');
                            ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($row['nombre_medicamento']); ?></strong></td>
                                <td><?php echo htmlspecialchars($fecha_formato); ?></td>
                                <td><?php echo htmlspecialchars($hora_formato); ?></td>
                                <td class="text-muted">
                                    <?php echo !empty($row['observaciones']) ? htmlspecialchars($row['observaciones']) : '---'; ?>
                                </td>
                                <td>
                                    <?php if ($esTomado): ?>
                                        <span class="chip chip-exito">
                                            <?php echo medhealthIcon('check', 18); ?>
                                            <span>Tomado</span>
                                        </span>
                                    <?php elseif ($row['estado'] === 'no_tomado'): ?>
                                        <span class="chip chip-error">
                                            <?php echo medhealthIcon('x', 18); ?>
                                            <span>No tomado</span>
                                        </span>
                                    <?php else: ?>
                                        <span class="chip chip-alerta">
                                            <?php echo medhealthIcon('clock', 18); ?>
                                            <span>Pendiente</span>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php else: ?>
        <article class="tarjeta empty-state">
            <span class="icon-circle icon-circle-80">
                <?php echo medhealthIcon('clock', 36); ?>
            </span>
            <h2>Aún no hay tomas registradas</h2>
            <p class="cuerpo text-muted">Cuando confirmes tus medicinas en la sección Hoy, aparecerán aquí.</p>
            <a href="dashboard.php" class="btn btn-primario">Ir a Hoy</a>
        </article>
    <?php endif; ?>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
