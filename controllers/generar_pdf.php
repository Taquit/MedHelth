<?php
include_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../libs/fpdf/fpdf.php';
include_once __DIR__ . '/../models/HistorialToma.php';
include_once __DIR__ . '/../models/Medicamento.php';
include_once __DIR__ . '/../models/Reporte.php';

requireLogin('../views/login.html');

$database = new Database();
$db = $database->getConnection();

$row_pac = obtenerPacienteActual($db);
$nombre_paciente = $row_pac ? $row_pac['nombre'] . " " . $row_pac['apellido_paterno'] : "Usuario";
$id_paciente = $row_pac ? (int) $row_pac['id_paciente'] : 0;

if ($id_paciente > 0) {
    $idMedicamentoReporte = null;
    if (isset($_GET['id_medicamento'])) {
        $candidatoId = (int) $_GET['id_medicamento'];
        $medicamentoModel = new Medicamento($db);
        if ($medicamentoModel->perteneceAPaciente($candidatoId, $id_paciente)) {
            $idMedicamentoReporte = $candidatoId;
        }
    }

    try {
        $reporteModel = new Reporte($db);
        $reporteModel->id_paciente = $id_paciente;
        $reporteModel->id_medicamento = $idMedicamentoReporte;
        $reporteModel->generado_por = $_SESSION['rol'] ?? 'paciente';
        $reporteModel->crear();
    } catch (PDOException $e) {
        // Continua generando el PDF aunque la columna id_medicamento aun no acepte NULL en BD local
    }
}

$historialModel = new HistorialToma($db);
$stmt_hist = $historialModel->leerPorPaciente($id_paciente);

function medhealthPdfText(string $str): string {
    if (function_exists('mb_convert_encoding')) {
        return mb_convert_encoding($str, 'ISO-8859-1', 'UTF-8');
    }
    if (function_exists('iconv')) {
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $str);
    }
    return @utf8_decode($str);
}

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 15);
        $this->SetTextColor(0, 119, 182);
        $this->Cell(80);
        $this->Cell(30, 10, 'MedHealth Reporte de Adherencia', 0, 0, 'C');
        $this->Ln(20);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(128);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 10, medhealthPdfText('Paciente: ' . $nombre_paciente), 0, 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 10, 'Fecha de reporte: ' . date('d/m/Y'), 0, 1);
$pdf->Ln(5);

$pdf->SetFillColor(0, 119, 182);
$pdf->SetTextColor(255);
$pdf->SetFont('Arial', 'B', 10);

$w = array(50, 35, 25, 30, 50);

$pdf->Cell($w[0], 7, 'Medicamento', 1, 0, 'C', true);
$pdf->Cell($w[1], 7, 'Fecha', 1, 0, 'C', true);
$pdf->Cell($w[2], 7, 'Hora', 1, 0, 'C', true);
$pdf->Cell($w[3], 7, 'Estado', 1, 0, 'C', true);
$pdf->Cell($w[4], 7, 'Observaciones', 1, 0, 'C', true);
$pdf->Ln();

$pdf->SetTextColor(0);
$pdf->SetFont('Arial', '', 9);

if ($stmt_hist->rowCount() > 0) {
    while ($row = $stmt_hist->fetch(PDO::FETCH_ASSOC)) {
        $timestamp = strtotime($row['fecha_hora']);
        $fecha = date('d/m/Y', $timestamp);
        $hora = date('H:i', $timestamp);

        $estado = ucfirst($row['estado']);
        $obs = $row['observaciones'];

        $pdf->Cell($w[0], 6, medhealthPdfText((string) $row['nombre_medicamento']), 1);
        $pdf->Cell($w[1], 6, $fecha, 1, 0, 'C');
        $pdf->Cell($w[2], 6, $hora, 1, 0, 'C');

        if ($row['estado'] === 'tomado') {
            $pdf->SetTextColor(27, 111, 101);
        } else {
            $pdf->SetTextColor(192, 57, 43);
        }

        $pdf->Cell($w[3], 6, medhealthPdfText((string) $estado), 1, 0, 'C');

        $pdf->SetTextColor(0);
        $pdf->Cell($w[4], 6, medhealthPdfText((string) $obs), 1);
        $pdf->Ln();
    }
} else {
    $pdf->Cell(array_sum($w), 10, 'No hay registros disponibles.', 1, 0, 'C');
}

$pdf->Output('D', 'Reporte_MedHealth.pdf');
?>
