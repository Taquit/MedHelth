<?php
$activePage = $activePage ?? '';
$nombreUsuarioSidebar = obtenerNombreUsuarioActual($db ?? null);

$menuItems = [
    ['id' => 'hoy', 'label' => 'Hoy', 'href' => 'dashboard.php', 'icon' => 'home'],
    ['id' => 'medicamentos', 'label' => 'Mis medicamentos', 'href' => 'medicamentos.php', 'icon' => 'pill'],
    ['id' => 'historial', 'label' => 'Historial', 'href' => 'historial.php', 'icon' => 'clock'],
    ['id' => 'reporte', 'label' => 'Reporte PDF', 'href' => 'reporte.php', 'icon' => 'file-text'],
    ['id' => 'cuidador', 'label' => 'Mi cuidador', 'href' => 'perfil_cuidador.php', 'icon' => 'heart'],
    ['id' => 'perfil', 'label' => 'Mi perfil', 'href' => 'perfil.php', 'icon' => 'user'],
];
?>
<div class="app-shell">
    <aside class="sidebar" aria-label="Menú principal">
        <div class="sidebar-top">
            <a href="dashboard.php" class="sidebar-logo">
                <span class="sidebar-logo-badge">
                    <?php echo medhealthIcon('pill', 26); ?>
                </span>
                <span>
                    MedHealth
                    <small>Seguimiento diario</small>
                </span>
            </a>

            <nav class="sidebar-nav">
                <?php foreach ($menuItems as $item): ?>
                    <?php $isActive = ($activePage === $item['id']); ?>
                    <a href="<?php echo $item['href']; ?>"
                       class="sidebar-item<?php echo $isActive ? ' active' : ''; ?>"
                       <?php echo $isActive ? 'aria-current="page"' : ''; ?>>
                        <?php echo medhealthIcon($item['icon'], 26); ?>
                        <span><?php echo htmlspecialchars($item['label']); ?></span>
                    </a>
                <?php endforeach; ?>
            </nav>
        </div>

        <div class="sidebar-footer">
            <span class="sidebar-user-label">Paciente</span>
            <div class="sidebar-user">
                <?php echo htmlspecialchars($nombreUsuarioSidebar); ?>
            </div>
            <a href="../controllers/logout.php" class="sidebar-logout">
                <?php echo medhealthIcon('log-out', 22); ?>
                <span>Cerrar sesión</span>
            </a>
        </div>
    </aside>

    <main class="app-main">
