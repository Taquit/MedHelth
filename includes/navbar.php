<?php
$activePage = $activePage ?? '';
?>
<nav class="navbar">
    <div class="logo">
        <img src="../Recursos/logo.png" alt="Logo Cuidate+">
        Cuidate+
    </div>
    <div class="nav-links">
        <a href="dashboard.php" class="nav-item<?php echo ($activePage === 'inicio') ? ' active' : ''; ?>">INICIO</a>
        <a href="medicamentos.php" class="nav-item<?php echo ($activePage === 'medicamentos') ? ' active' : ''; ?>">MEDICAMENTOS</a>
        <a href="historial.php" class="nav-item<?php echo ($activePage === 'historial') ? ' active' : ''; ?>">HISTORIAL</a>
    </div>
    <div class="nav-right">
        <a href="perfil.php" class="profile-link<?php echo ($activePage === 'perfil') ? ' active-profile' : ''; ?>">
            <span class="profile-text">PERFIL</span>
            <div class="profile-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            </div>
        </a>
    </div>
</nav>
