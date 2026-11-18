<?php
include_once __DIR__ . '/../includes/auth.php';
include_once __DIR__ . '/../includes/helpers.php';
include_once __DIR__ . '/../models/Cuidador.php';

requireLogin('login.html');

$database = new Database();
$db = $database->getConnection();

$id_paciente = obtenerIdPacienteActual($db);

$cuidadorModel = new Cuidador($db);
$activo = $cuidadorModel->obtenerActivo($id_paciente);

$nombre_activo = $activo ? $activo['nombre'] : 'Sin asignar';
$paterno_activo = $activo ? $activo['apellido_paterno'] : '';
$materno_activo = $activo ? $activo['apellido_materno'] : '';
$telefono_activo = $activo ? $activo['telefono'] : '';
$correo_activo = $activo ? ($activo['correo'] ?? '') : '';

$stmt_historial = $cuidadorModel->obtenerHistorial($id_paciente);
$activePage = 'perfil';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Cuidador - Cuidate+</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/perfil_cuidador.css">
</head>
<body>

    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <main class="profile-container">
        <aside class="profile-sidebar">
            <a href="perfil.php" class="sidebar-btn">Datos del Paciente</a>
            <button class="sidebar-btn active">Cuidador</button>
        </aside>

        <section class="profile-card">
            <h2>Datos del Cuidador</h2>

            <div class="profile-layout">
                <div class="avatar-column">
                    <div class="avatar-circle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </div>
                    
                    <div class="caregiver-actions">
                        <a href="alta_cuidador.php" class="btn-action btn-alta" style="text-decoration:none; display:block; text-align:center;">
                            Dar de alta
                        </a>
                        
                        <?php if ($activo): ?>
                            <a href="baja_cuidador.php" class="btn-action btn-baja" style="text-decoration:none; display:block; text-align:center;">
                                Dar de baja
                            </a>
                        <?php else: ?>
                            <button class="btn-action btn-baja" style="opacity: 0.5; cursor: not-allowed;" disabled>Dar de baja</button>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-column">
                    <form action="#">
                        <div class="form-group">
                            <label>Nombre(s)</label>
                            <input type="text" value="<?php echo htmlspecialchars($nombre_activo); ?>" readonly>
                        </div>

                        <div class="row-group">
                            <div class="form-group">
                                <label>Apellido Paterno</label>
                                <input type="text" value="<?php echo htmlspecialchars($paterno_activo); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Apellido Materno</label>
                                <input type="text" value="<?php echo htmlspecialchars($materno_activo); ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Correo</label>
                            <input type="email" value="<?php echo htmlspecialchars($correo_activo); ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Teléfono de contacto</label>
                            <input type="tel" value="<?php echo htmlspecialchars($telefono_activo); ?>" readonly>
                        </div>
                    </form>
                </div>
            </div>

            <div class="caregiver-history">
                <h3>Historial del cuidador</h3>
                <div class="table-responsive">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre del cuidador</th>
                                <th>Fecha inicio</th>
                                <th>Fecha fin</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $count = 1;
                            while ($hist = $stmt_historial->fetch(PDO::FETCH_ASSOC)):
                                if ($hist['fecha_fin'] === null) {
                                    $estado = 'Alta';
                                    $clase_badge = 'badge-alta';
                                    $fecha_fin_txt = '-';
                                } else {
                                    $estado = 'Baja';
                                    $clase_badge = 'badge-baja';
                                    $fecha_fin_txt = formatearFechaEspanol($hist['fecha_fin']);
                                }
                                $nombre_completo = trim($hist['nombre'] . " " . $hist['apellido_paterno']);
                            ?>
                                <tr>
                                    <td class="col-id"><?php echo $count++; ?></td>
                                    <td><?php echo htmlspecialchars($nombre_completo); ?></td>
                                    <td><?php echo htmlspecialchars(formatearFechaEspanol($hist['fecha_inicio'])); ?></td>
                                    <td><?php echo htmlspecialchars($fecha_fin_txt); ?></td>
                                    <td><span class="badge <?php echo $clase_badge; ?>"><?php echo $estado; ?></span></td>
                                </tr>
                            <?php endwhile; ?>
                            
                            <?php if ($count === 1): ?>
                                <tr><td colspan="5" style="text-align:center; padding:1rem; color:#888;">No hay historial disponible.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
