<?php
include_once __DIR__ . '/../includes/auth.php';

requireLogin('login.html');
$activePage = 'medicamentos';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Medicamento - Cuidate+</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/agregar_medicamento.css">
</head>
<body>

    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <main class="overlay-container">
        <div class="modal-card">
            <a href="medicamentos.php" class="close-btn">&#10005;</a>

            <div class="modal-header">
                <h2>Datos del medicamento</h2>
            </div>

            <form action="../controllers/guardar_medicamento.php" method="POST">
                <div class="form-group">
                    <label for="nombre">Nombre del Medicamento</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ingresa el nombre" required>
                </div>

                <div class="form-group">
                    <label for="dosis">Dosis</label>
                    <input type="text" id="dosis" name="dosis" placeholder="Ej. 1/2 tableta" required>
                </div>

                <div class="form-group">
                    <label for="via">Vía de administración</label>
                    <input type="text" id="via" name="via" placeholder="Ej. Oral, Tópica" required>
                </div>

                <div class="form-group">
                    <label for="hora_inicio">Hora de la primera toma</label>
                    <div class="time-input-container">
                        <input type="time" id="hora_inicio" name="hora_inicio" class="digital-clock-input" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Frecuencia (Cada cuánto)</label>
                    <div class="split-inputs">
                        <input type="number" name="frecuencia_cant" placeholder="Cant." class="input-short" required>
                        <select name="frecuencia_unidad" class="input-medium">
                            <option value="horas">Horas</option>
                            <option value="minutos">Minutos</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Duración del tratamiento</label>
                    <div class="split-inputs">
                        <input type="number" name="duracion_cant" placeholder="Cant." class="input-short" required>
                        <select name="duracion_unidad" class="input-medium">
                            <option value="dias">Días</option>
                            <option value="semanas">Semanas</option>
                            <option value="meses">Meses</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="indicaciones">Indicaciones específicas</label>
                    <input type="text" id="indicaciones" name="indicaciones" placeholder="Ej. Tomar con agua" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-agregar">Agregar</button>
                </div>
            </form>
        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
