<?php
include_once __DIR__ . '/../includes/auth.php';
include_once __DIR__ . '/../models/Medicamento.php';

requireLogin('login.html');

$database = new Database();
$db = $database->getConnection();

$id_paciente = obtenerIdPacienteActual($db);

$medicamentoModel = new Medicamento($db);
$stmt_meds = $medicamentoModel->leerPorPaciente($id_paciente);
$num_meds = $stmt_meds->rowCount();
$activePage = 'medicamentos';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Medicamentos - Cuidate+</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/medicamentos.css">
</head>
<body>

    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <main class="meds-container">
        <div class="top-action">
            <a href="agregar_medicamento.php" class="btn-add-med">
                + Agregar medicamento
            </a>
        </div>

        <section class="meds-grid">
            <?php if ($num_meds > 0): ?>
                <?php while ($med = $stmt_meds->fetch(PDO::FETCH_ASSOC)): ?>
                    <article class="med-item-card">
                        <h3><?php echo htmlspecialchars($med['nombre']); ?></h3>
                        
                        <div class="med-details">
                            <p><strong>Dosis:</strong> <?php echo htmlspecialchars($med['dosis']); ?></p>
                            <p><strong>Vía:</strong> <?php echo htmlspecialchars($med['via_administracion']); ?></p>
                            <p><strong>Progreso:</strong> 0 / <?php echo htmlspecialchars((string) $med['duracion_dias']); ?></p>
                            <p><strong>Duración:</strong> <?php echo htmlspecialchars((string) $med['duracion_dias']); ?> días</p>
                            <p><strong>Indicaciones:</strong> <?php echo htmlspecialchars($med['indicaciones']); ?></p>
                        </div>

                        <div class="card-actions">
                            <a href="editar_medicamento.php?id=<?php echo (int) $med['id_medicamento']; ?>" class="btn-card-action btn-edit">
                                Editar
                            </a>
                            
                            <a href="../controllers/eliminar_medicamento.php?id=<?php echo (int) $med['id_medicamento']; ?>" 
                               class="btn-card-action btn-delete"
                               onclick="return confirm('¿Estás seguro de que deseas eliminar este medicamento?');">
                                Eliminar
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: #888;">
                    <p>No tienes medicamentos registrados aún.</p>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
