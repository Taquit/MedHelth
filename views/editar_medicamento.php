<?php
include_once __DIR__ . '/../includes/auth.php';
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

$viasDisponibles = [
    'Oral', 'Tópica', 'Inhalada', 'Oftálmica', 'Ótica',
    'Nasal', 'Sublingual', 'Intramuscular', 'Subcutánea', 'Intravenosa', 'Otra'
];
$viaActual = trim((string) $medicamento->via_administracion);
if ($viaActual !== '' && !in_array($viaActual, $viasDisponibles, true)) {
    array_unshift($viasDisponibles, $viaActual);
}

$activePage = 'medicamentos';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar <?php echo htmlspecialchars($medicamento->nombre); ?> - MedHealth</title>
    <link rel="stylesheet" href="../assets/css/medhealth.css">
</head>
<body>

    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <header class="page-header">
        <div class="page-header-text">
            <h1>Editar <?php echo htmlspecialchars($medicamento->nombre); ?></h1>
            <p class="cuerpo text-muted">Actualiza la dosis, horario o indicaciones de tu medicamento.</p>
        </div>
    </header>

    <section class="tarjeta tarjeta-form tarjeta-900">
        <form action="../controllers/actualizar_medicamento.php" method="POST" class="form-stack">
            <input type="hidden" name="id_medicamento" value="<?php echo (int) $medicamento->id_medicamento; ?>">

            <div class="form-grid-2">
                <div class="campo">
                    <label for="nombre">Nombre del medicamento</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($medicamento->nombre); ?>" required>
                </div>

                <div class="campo">
                    <label for="dosis">Dosis</label>
                    <input type="text" id="dosis" name="dosis" value="<?php echo htmlspecialchars($medicamento->dosis); ?>" required>
                </div>

                <div class="campo">
                    <label for="via">Vía de administración</label>
                    <div class="select-wrapper">
                        <select id="via" name="via" required>
                            <?php foreach ($viasDisponibles as $via): ?>
                                <option value="<?php echo htmlspecialchars($via); ?>" <?php echo ($viaActual === $via) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($via); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="select-chevron"><?php echo medhealthIcon('chevron-down', 24); ?></span>
                    </div>
                </div>

                <div class="campo">
                    <label for="hora_inicio">Hora de la primera toma</label>
                    <input type="time" id="hora_inicio" name="hora_inicio" value="<?php echo htmlspecialchars($medicamento->hora_inicio); ?>" required>
                </div>

                <div class="campo">
                    <label for="frecuencia_cant">Frecuencia (cada cuánto)</label>
                    <div class="campo-split">
                        <input type="number" id="frecuencia_cant" name="frecuencia_cant" min="1" value="<?php echo $frec_cant; ?>" required>
                        <div class="select-wrapper">
                            <select id="frecuencia_unidad" name="frecuencia_unidad" aria-label="Unidad de frecuencia">
                                <option value="horas" <?php echo ($frec_unit === 'horas') ? 'selected' : ''; ?>>Horas</option>
                                <option value="minutos" <?php echo ($frec_unit === 'minutos') ? 'selected' : ''; ?>>Minutos</option>
                            </select>
                            <span class="select-chevron"><?php echo medhealthIcon('chevron-down', 24); ?></span>
                        </div>
                    </div>
                </div>

                <div class="campo">
                    <label for="duracion_cant">Duración del tratamiento</label>
                    <div class="campo-split">
                        <input type="number" id="duracion_cant" name="duracion_cant" min="1" value="<?php echo $dur_cant; ?>" required>
                        <div class="select-wrapper">
                            <select id="duracion_unidad" name="duracion_unidad" aria-label="Unidad de duración">
                                <option value="dias" <?php echo ($dur_unit === 'dias') ? 'selected' : ''; ?>>Días</option>
                                <option value="semanas" <?php echo ($dur_unit === 'semanas') ? 'selected' : ''; ?>>Semanas</option>
                                <option value="meses" <?php echo ($dur_unit === 'meses') ? 'selected' : ''; ?>>Meses</option>
                            </select>
                            <span class="select-chevron"><?php echo medhealthIcon('chevron-down', 24); ?></span>
                        </div>
                    </div>
                </div>

                <div class="campo col-span-2">
                    <label for="indicaciones">Indicaciones específicas</label>
                    <textarea id="indicaciones" name="indicaciones" required><?php echo htmlspecialchars($medicamento->indicaciones); ?></textarea>
                </div>
            </div>

            <div class="form-actions-row">
                <button type="submit" class="btn btn-primario">Guardar cambios</button>
                <a href="medicamentos.php" class="btn btn-secundario">Cancelar</a>
            </div>
        </form>
    </section>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
