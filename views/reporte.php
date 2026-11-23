<?php
include_once __DIR__ . '/../includes/auth.php';
include_once __DIR__ . '/../models/HistorialToma.php';

requireLogin('login.html');

$database = new Database();
$db = $database->getConnection();

$row_pac = obtenerPacienteActual($db);
$id_paciente = $row_pac ? (int) $row_pac['id_paciente'] : 0;
$nombre_paciente = $row_pac
    ? trim($row_pac['nombre'] . ' ' . $row_pac['apellido_paterno'])
    : obtenerNombreUsuarioActual($db);

$historialModel = new HistorialToma($db);
$stmt_hist = $historialModel->leerPorPaciente($id_paciente);
$registros = $stmt_hist->fetchAll(PDO::FETCH_ASSOC);

$fechaEmision = obtenerFechaActualEspanol();
$activePage = 'reporte';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte PDF - MedHealth</title>
    <link rel="stylesheet" href="../assets/css/medhealth.css">
</head>
<body>

    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <header class="page-header">
        <div class="page-header-text">
            <h1>Reporte PDF</h1>
            <p class="cuerpo text-muted">Previsualiza tu historial y descarga un archivo listo para imprimir o compartir.</p>
        </div>
    </header>

    <div class="reporte-grid">
        <section class="tarjeta" style="display: flex; flex-direction: column; gap: 20px;">
            <span class="icon-circle icon-circle-64">
                <?php echo medhealthIcon('file-text', 30); ?>
            </span>
            <h2>Descarga tu avance en un documento PDF</h2>
            <p class="cuerpo text-muted">
                Este documento reúne las tomas registradas hasta el día de hoy con su fecha, hora, observaciones y estado, para que puedas entregarlo en tu consulta médica.
            </p>
            <a href="../controllers/generar_pdf.php" class="btn btn-primario btn-block">
                <?php echo medhealthIcon('file-text', 24); ?>
                <span>Descargar reporte PDF</span>
            </a>
        </section>

        <section class="hoja-preview" aria-label="Vista previa del reporte">
            <div class="hoja-preview-header">
                <div>
                    <p class="etiqueta" style="color: var(--primario-700); text-transform: uppercase;">MedHealth · Reporte de Adherencia</p>
                    <h2 style="margin-top: 6px;">Paciente: <?php echo htmlspecialchars($nombre_paciente); ?></h2>
                </div>
                <p class="cuerpo text-muted">Emisión: <?php echo htmlspecialchars($fechaEmision); ?></p>
            </div>

            <?php if (count($registros) > 0): ?>
                <div class="tabla-contenedor">
                    <div class="tabla-scroll">
                        <table class="tabla">
                            <thead>
                                <tr>
                                    <th>Medicamento</th>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($registros, 0, 8) as $row): ?>
                                    <?php
                                        $ts = strtotime($row['fecha_hora']);
                                        $esTomado = ($row['estado'] === 'tomado');
                                    ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($row['nombre_medicamento']); ?></strong></td>
                                        <td><?php echo htmlspecialchars(date('d/m/Y', $ts)); ?></td>
                                        <td><?php echo htmlspecialchars(date('H:i', $ts)); ?></td>
                                        <td>
                                            <?php if ($esTomado): ?>
                                                <span class="chip chip-exito">
                                                    <?php echo medhealthIcon('check', 18); ?>
                                                    <span>Tomado</span>
                                                </span>
                                            <?php else: ?>
                                                <span class="chip chip-error">
                                                    <?php echo medhealthIcon('x', 18); ?>
                                                    <span>No tomado</span>
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
                <div class="aviso aviso-info">
                    <?php echo medhealthIcon('clock', 24); ?>
                    <span>Aún no tienes registros en tu historial para mostrar en el reporte.</span>
                </div>
            <?php endif; ?>
        </section>
    </div>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
