<?php
include_once __DIR__ . '/../includes/auth.php';
include_once __DIR__ . '/../includes/helpers.php';
include_once __DIR__ . '/../models/Medicamento.php';

requireLogin('login.html');

$database = new Database();
$db = $database->getConnection();

$row_paciente = obtenerPacienteActual($db);
if ($row_paciente) {
    $id_paciente = (int) $row_paciente['id_paciente'];
    $nombre_mostrar = $row_paciente['nombre'];
} else {
    $nombre_mostrar = "Usuario";
    $id_paciente = 0;
}

$medicamentoModel = new Medicamento($db);
$stmt_meds = $medicamentoModel->leerPorPaciente($id_paciente);
$num_meds = $stmt_meds->rowCount();

$fecha_actual = obtenerFechaActualEspanol();
$activePage = 'inicio';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Cuidate+</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/dashboard.css">
    <style>
        .empty-message {
            text-align: center;
            color: var(--texto-form);
            font-size: 1.2rem;
            padding: 3rem;
            font-style: italic;
        }
        .btn-add-link {
            display: inline-block;
            margin-top: 1rem;
            color: var(--azul-fuerte);
            text-decoration: underline;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <main class="dashboard-container">
        <header class="dashboard-header">
            <h1>¡ Hola, <?php echo htmlspecialchars($nombre_mostrar); ?> !</h1>
            <p class="date-display">Hoy es <?php echo htmlspecialchars($fecha_actual); ?></p>
            <h2>Medicamentos del día de hoy</h2>
        </header>

        <section class="medication-list">
            <?php if ($num_meds > 0): ?>
                <?php while ($med = $stmt_meds->fetch(PDO::FETCH_ASSOC)): ?>
                    <article class="med-card">
                        <div class="med-info">
                            <div class="med-title-row">
                                <h3><?php echo htmlspecialchars($med['nombre']); ?></h3>
                                <span class="med-indications">Indicaciones: <?php echo htmlspecialchars($med['indicaciones']); ?></span>
                            </div>
                            <div class="med-details-grid">
                                <p><strong>Dosis:</strong> <?php echo htmlspecialchars($med['dosis']); ?></p>
                                <p><strong>Vía:</strong> <?php echo htmlspecialchars($med['via_administracion']); ?></p>
                                <p><strong>Progreso:</strong> 0 / <?php echo htmlspecialchars((string) $med['duracion_dias']); ?></p>
                                <p><strong>Duración:</strong> <?php echo htmlspecialchars((string) $med['duracion_dias']); ?> días</p>
                            </div>
                        </div>

                        <aside class="med-actions">
                            <h4>Confirmación</h4>
                            <div class="action-buttons">
                                <a href="../controllers/registrar_toma.php?id=<?php echo (int) $med['id_medicamento']; ?>&status=tomado" 
                                   class="btn-action btn-take">
                                    <span class="icon">&#10004;</span> Se tomo
                                </a>

                                <a href="../controllers/posponer_toma.php?id=<?php echo (int) $med['id_medicamento']; ?>" 
                                   class="btn-action btn-postpone">
                                    <span class="icon">&#9200;</span> Posponer
                                </a>

                                <a href="notificacion_no_tomada.php?id=<?php echo (int) $med['id_medicamento']; ?>" 
                                   class="btn-action btn-skip">
                                    <span class="icon">&#10006;</span> No se tomo
                                </a>
                            </div>
                        </aside>
                    </article>
                <?php endwhile; ?>
            <?php else: ?>
                <article class="med-card">
                    <div style="width: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <div class="empty-message">
                            Aun no tienes medicamentos registrados
                            <br>
                            <a href="medicamentos.php" class="btn-add-link">+ Registrar uno ahora</a>
                        </div>
                    </div>
                </article>
            <?php endif; ?>
        </section>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
