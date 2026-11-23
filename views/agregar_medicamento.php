<?php
include_once __DIR__ . '/../includes/auth.php';

requireLogin('login.html');
$activePage = 'medicamentos';

$viasDisponibles = [
    'Oral', 'Tópica', 'Inhalada', 'Oftálmica', 'Ótica',
    'Nasal', 'Sublingual', 'Intramuscular', 'Subcutánea', 'Intravenosa', 'Otra'
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar medicamento - MedHealth</title>
    <link rel="stylesheet" href="../assets/css/medhealth.css">
</head>
<body>

    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <header class="page-header">
        <div class="page-header-text">
            <h1>Agregar medicamento</h1>
            <p class="cuerpo text-muted">Captura los datos de tu receta para programar tus tomas.</p>
        </div>
    </header>

    <section class="tarjeta tarjeta-form tarjeta-900">
        <form action="../controllers/guardar_medicamento.php" method="POST" class="form-stack">
            <div class="form-grid-2">
                <div class="campo">
                    <label for="nombre">Nombre del medicamento</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ej. Paracetamol" required>
                </div>

                <div class="campo">
                    <label for="dosis">Dosis</label>
                    <input type="text" id="dosis" name="dosis" placeholder="Ej. 1 tableta / 500 mg" required>
                </div>

                <div class="campo">
                    <label for="via">Vía de administración</label>
                    <div class="select-wrapper">
                        <select id="via" name="via" required>
                            <option value="">Selecciona una vía</option>
                            <?php foreach ($viasDisponibles as $via): ?>
                                <option value="<?php echo htmlspecialchars($via); ?>"><?php echo htmlspecialchars($via); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="select-chevron"><?php echo medhealthIcon('chevron-down', 24); ?></span>
                    </div>
                </div>

                <div class="campo">
                    <label for="hora_inicio">Hora de la primera toma</label>
                    <input type="time" id="hora_inicio" name="hora_inicio" required>
                </div>

                <div class="campo">
                    <label for="frecuencia_cant">Frecuencia (cada cuánto)</label>
                    <div class="campo-split">
                        <input type="number" id="frecuencia_cant" name="frecuencia_cant" min="1" placeholder="Ej. 8" required>
                        <div class="select-wrapper">
                            <select id="frecuencia_unidad" name="frecuencia_unidad" aria-label="Unidad de frecuencia">
                                <option value="horas">Horas</option>
                                <option value="minutos">Minutos</option>
                            </select>
                            <span class="select-chevron"><?php echo medhealthIcon('chevron-down', 24); ?></span>
                        </div>
                    </div>
                </div>

                <div class="campo">
                    <label for="duracion_cant">Duración del tratamiento</label>
                    <div class="campo-split">
                        <input type="number" id="duracion_cant" name="duracion_cant" min="1" placeholder="Ej. 7" required>
                        <div class="select-wrapper">
                            <select id="duracion_unidad" name="duracion_unidad" aria-label="Unidad de duración">
                                <option value="dias">Días</option>
                                <option value="semanas">Semanas</option>
                                <option value="meses">Meses</option>
                            </select>
                            <span class="select-chevron"><?php echo medhealthIcon('chevron-down', 24); ?></span>
                        </div>
                    </div>
                </div>

                <div class="campo col-span-2">
                    <label for="indicaciones">Indicaciones específicas</label>
                    <textarea id="indicaciones" name="indicaciones" placeholder="Ej. Tomar con un vaso de agua después de desayunar" required></textarea>
                </div>
            </div>

            <div class="form-actions-row">
                <button type="submit" class="btn btn-primario">Guardar medicamento</button>
                <a href="medicamentos.php" class="btn btn-secundario">Cancelar</a>
            </div>
        </form>
    </section>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
