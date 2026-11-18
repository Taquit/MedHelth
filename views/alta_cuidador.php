<?php
include_once __DIR__ . '/../includes/auth.php';

requireLogin('login.html');
$activePage = 'perfil';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Cuidador - Cuidate+</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/perfil_cuidador.css">
</head>
<body>

    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <div class="background-content">
        <main class="profile-container">
            <aside class="profile-sidebar">
                <a href="perfil.php" class="sidebar-btn">Datos Paciente</a>
                <button class="sidebar-btn active">Cuidador</button>
            </aside>

            <section class="profile-card">
                <h2>Registrar Nuevo Cuidador</h2>
                
                <form action="../controllers/registrar_cuidador.php" method="POST" class="profile-layout">
                    <div class="avatar-column">
                        <div class="avatar-circle">
                            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        </div>
                        <p style="text-align:center; font-size:0.9rem; margin-top:10px; color:#666;">
                            El cuidador tendrá su propio acceso al sistema.
                        </p>
                    </div>

                    <div class="form-column">
                        <div class="form-group">
                            <label for="nombre">Nombre(s)</label>
                            <input type="text" id="nombre" name="nombre" required placeholder="Ej. Juan">
                        </div>

                        <div class="row-group" style="display:flex; gap:10px;">
                            <div class="form-group" style="flex:1;">
                                <label for="paterno">Apellido Paterno</label>
                                <input type="text" id="paterno" name="paterno" required placeholder="Ej. Pérez">
                            </div>
                            <div class="form-group" style="flex:1;">
                                <label for="materno">Apellido Materno</label>
                                <input type="text" id="materno" name="materno" placeholder="Ej. López">
                            </div>
                        </div>

                        <div class="row-group" style="display:flex; gap:10px;">
                            <div class="form-group" style="flex:1;">
                                <label for="parentesco">Parentesco</label>
                                <select id="parentesco" name="parentesco" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc;">
                                    <option value="">Seleccione...</option>
                                    <option value="Familiar">Familiar</option>
                                    <option value="Enfermero/a">Enfermero/a</option>
                                    <option value="Amigo/a">Amigo/a</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                            <div class="form-group" style="flex:1;">
                                <label for="telefono">Teléfono</label>
                                <input type="tel" id="telefono" name="telefono" required placeholder="55 1234 5678">
                            </div>
                        </div>

                        <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">

                        <h4>Datos de Acceso del Cuidador</h4>
                        <div class="form-group">
                            <label for="email">Correo Electrónico</label>
                            <input type="email" id="email" name="email" required placeholder="cuidador@email.com">
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña Temporal</label>
                            <input type="password" id="password" name="password" required placeholder="********">
                        </div>

                        <div class="caregiver-actions" style="margin-top:20px;">
                            <button type="submit" class="btn-action btn-alta" style="width:100%;">Registrar y Asignar</button>
                            <a href="perfil_cuidador.php" class="btn-action btn-baja" style="text-decoration:none; text-align:center; display:inline-block; margin-top:10px;">Cancelar</a>
                        </div>
                    </div>
                </form>
            </section>
        </main>
    </div>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
