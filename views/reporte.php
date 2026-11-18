<?php
include_once __DIR__ . '/../includes/auth.php';

requireLogin('login.html');
$activePage = 'historial';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Generado - Cuidate+</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/historial.css">
    <link rel="stylesheet" href="../CSS/reporte.css">
</head>
<body>

    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <div class="background-content">
        <main class="history-container">
            <h2 class="page-title">Historial de medicamentos</h2>
            <div class="table-card">
                <table class="history-table">
                    <thead><tr><th>#</th><th>Medicamento</th><th>Fecha</th><th>Estado</th></tr></thead>
                    <tbody>
                        <tr><td>1</td><td>Paracetamol</td><td>Hoy</td><td><span class="badge badge-tomado">Tomado</span></td></tr>
                        <tr><td>2</td><td>Vitamina C</td><td>Ayer</td><td><span class="badge badge-pendiente">Pendiente</span></td></tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <div class="report-overlay">
        <div class="report-modal">
            <a href="historial.php" class="close-icon">&#10005;</a>

            <div class="report-content">
                <h2>Reporte Generado</h2>
                
                <div class="document-placeholder">
                    <div class="doc-icon">[PDF]</div>
                    <p class="doc-title">Reporte de Adherencia</p>
                    <p class="doc-date">Generado el: <?php echo date("d/m/Y"); ?></p>
                    <small>Este documento contiene el historial de tomas.</small>
                </div>

                <div class="modal-actions">
                    <a href="../controllers/generar_pdf.php" class="btn-download" style="text-decoration:none; display:inline-block;">
                        Descargar PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
