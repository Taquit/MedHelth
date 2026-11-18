<?php
include_once __DIR__ . '/../includes/auth.php';
include_once __DIR__ . '/../includes/helpers.php';
include_once __DIR__ . '/../models/Medicamento.php';

requireLogin('login.html');

if (!isset($_GET['id'])) {
    header("Location: medicamentos.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();

$id_paciente = obtenerIdPacienteActual($db);

$medicamento = new Medicamento($db);
$medicamento->id_medicamento = (int) $_GET['id'];

if (!$medicamento->leerUno($id_paciente)) {
    header("Location: medicamentos.php");
    exit;
}

$duracionDesglosada = desglosarDuracionDias((int) $medicamento->duracion_dias);
$dur_cant = $duracionDesglosada['cantidad'];
$dur_unit = $duracionDesglosada['unidad'];

$frec_cant = (int) $medicamento->frecuencia_horas;
$frec_unit = 'horas';
$activePage = 'medicamentos';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Medicamento - Cuidate+</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/agregar_medicamento.css"> 
</head>
<body>

    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <main class="overlay-container">
        <div class="modal-card">
            <a href="medicamentos.php" class="close-btn">&#10005;</a>

            <div class="modal-header">
                <h2>Editar medicamento</h2>
            </div>

            <form action="../controllers/actualizar_medicamento.php" method="POST">
                <input type="hidden" name="id_medicamento" value="<?php echo (int) $medicamento->id_medicamento; ?>">

                <div class="form-group">
                    <label for="nombre">Nombre del Medicamento</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($medicamento->nombre); ?>" required>
                </div>

                <div class="form-group">
                    <label for="dosis">Dosis</label>
                    <input type="text" id="dosis" name="dosis" value="<?php echo htmlspecialchars($medicamento->dosis); ?>" required>
                </div>

                <div class="form-group">
                    <label for="via">Vía de administración</label>
                    <input type="text" id="via" name="via" value="<?php echo htmlspecialchars($medicamento->via_administracion); ?>" required>
                </div>

                <div class="form-group">
                    <label for="hora_inicio">Hora de la primera toma</label>
                    <div class="time-input-container">
                        <input type="time" id="hora_inicio" name="hora_inicio" class="digital-clock-input" value="<?php echo htmlspecialchars($medicamento->hora_inicio); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Frecuencia (Cada cuánto)</label>
                    <div class="split-inputs">
                        <input type="number" name="frecuencia_cant" class="input-short" value="<?php echo $frec_cant; ?>" required>
                        <select name="frecuencia_unidad" class="input-medium">
                            <option value="horas" <?php echo ($frec_unit === 'horas') ? 'selected' : ''; ?>>Horas</option>
                            <option value="minutos" <?php echo ($frec_unit === 'minutos') ? 'selected' : ''; ?>>Minutos</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Duración del tratamiento</label>
                    <div class="split-inputs">
                        <input type="number" name="duracion_cant" class="input-short" value="<?php echo $dur_cant; ?>" required>
                        <select name="duracion_unidad" class="input-medium">
                            <option value="dias" <?php echo ($dur_unit === 'dias') ? 'selected' : ''; ?>>Días</option>
                            <option value="semanas" <?php echo ($dur_unit === 'semanas') ? 'selected' : ''; ?>>Semanas</option>
                            <option value="meses" <?php echo ($dur_unit === 'meses') ? 'selected' : ''; ?>>Meses</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="indicaciones">Indicaciones específicas</label>
                    <input type="text" id="indicaciones" name="indicaciones" value="<?php echo htmlspecialchars($medicamento->indicaciones); ?>" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-agregar">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
