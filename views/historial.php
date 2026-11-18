<?php
include_once __DIR__ . '/../includes/auth.php';
include_once __DIR__ . '/../includes/helpers.php';
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
    <title>Historial - Cuidate+</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/historial.css">
</head>
<body>

    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <main class="history-container">
        <h2 class="page-title">Historial de medicamentos</h2>

        <div class="table-card">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Medicamento</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Observaciones</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($num_registros > 0): ?>
                    <?php $contador = 1; ?>
                    <?php while ($row = $stmt_historial->fetch(PDO::FETCH_ASSOC)): ?>
                        <?php
                            $timestamp = strtotime($row['fecha_hora']);
                            $fecha_formato = formatearFechaEspanol($row['fecha_hora']);
                            $hora_formato = date('H:i', $timestamp);

                            $badge_class = 'badge-pendiente';
                            $estado_texto = 'Pendiente';

                            if ($row['estado'] === 'tomado') {
                                $badge_class = 'badge-tomado';
                                $estado_texto = 'Tomado';
                            } elseif ($row['estado'] === 'no_tomado') {
                                $badge_class = 'badge-omitido';
                                $estado_texto = 'No Tomado';
                            }
                        ?>
                        <tr>
                            <td class="col-id"><?php echo $contador++; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['nombre_medicamento']); ?></strong></td>
                            <td><?php echo htmlspecialchars($fecha_formato); ?></td>
                            <td><?php echo htmlspecialchars($hora_formato); ?></td>
                            <td class="text-muted">
                                <?php echo !empty($row['observaciones']) ? htmlspecialchars($row['observaciones']) : '---'; ?>
                            </td>
                            <td>
                                <span class="badge <?php echo $badge_class; ?>">
                                    <?php echo $estado_texto; ?>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center; padding: 2rem; color: #888;">
                            No hay registros en el historial aún.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="action-area">
            <a href="reporte.php" class="btn-reporte">Generar reporte</a>
        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
