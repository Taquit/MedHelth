<?php
date_default_timezone_set('America/Mexico_City');

/**
 * Devuelve la lista de meses en espanol.
 *
 * @return array<int, string>
 */
function obtenerMesesEspanol(): array {
    return [
        "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
    ];
}

/**
 * Obtiene la fecha actual formateada en espanol (ej. "17 de Noviembre del 2026").
 *
 * @return string
 */
function obtenerFechaActualEspanol(): string {
    $meses = obtenerMesesEspanol();
    $mesIndex = (int) date('n') - 1;
    return date('d') . " de " . $meses[$mesIndex] . " del " . date('Y');
}

/**
 * Formatea una cadena de fecha o fecha-hora en espanol (ej. "17 de Noviembre 2026").
 *
 * @param string|null $fecha Cadena de fecha compatible con strtotime.
 * @return string Fecha formateada o '-' si es nula/vacia.
 */
function formatearFechaEspanol(?string $fecha): string {
    if (empty($fecha)) {
        return '-';
    }
    $ts = strtotime($fecha);
    if ($ts === false) {
        return '-';
    }
    $meses = obtenerMesesEspanol();
    $mesIndex = (int) date('n', $ts) - 1;
    return date('d', $ts) . " de " . $meses[$mesIndex] . " " . date('Y', $ts);
}

/**
 * Devuelve un saludo acorde a la hora del dia.
 *
 * @return string
 */
function obtenerSaludoHora(): string {
    $hora = (int) date('G');
    if ($hora < 12) {
        return 'Buenos días';
    }
    if ($hora < 19) {
        return 'Buenas tardes';
    }
    return 'Buenas noches';
}

/**
 * Obtiene hasta dos iniciales en mayusculas a partir de un nombre completo.
 *
 * @param string $nombreCompleto
 * @return string
 */
function obtenerIniciales(string $nombreCompleto): string {
    $partes = preg_split('/\s+/', trim($nombreCompleto));
    if (!$partes || $partes[0] === '') {
        return 'C';
    }
    $iniciales = '';
    foreach (array_slice($partes, 0, 2) as $parte) {
        $iniciales .= function_exists('mb_substr')
            ? mb_strtoupper(mb_substr($parte, 0, 1, 'UTF-8'), 'UTF-8')
            : strtoupper(substr($parte, 0, 1));
    }
    return $iniciales;
}

/**
 * Convierte la cantidad y unidad de frecuencia a horas enteras.
 *
 * @param int $cantidad Valor numerico de frecuencia.
 * @param string $unidad Unidad ('horas' o 'minutos').
 * @return int Frecuencia expresada en horas.
 */
function convertirFrecuenciaAHoras(int $cantidad, string $unidad): int {
    if ($unidad === 'minutos') {
        return (int) ceil($cantidad / 60);
    }
    return $cantidad;
}

/**
 * Convierte la cantidad y unidad de duracion a dias enteros.
 *
 * @param int $cantidad Valor numerico de duracion.
 * @param string $unidad Unidad ('dias', 'semanas', 'meses').
 * @return int Duracion expresada en dias.
 */
function convertirDuracionADias(int $cantidad, string $unidad): int {
    switch ($unidad) {
        case 'semanas':
            return $cantidad * 7;
        case 'meses':
            return $cantidad * 30;
        default:
            return $cantidad;
    }
}

/**
 * Desglosa una cantidad de dias en una unidad amigable ('dias', 'semanas', 'meses').
 *
 * @param int $dias Total de dias almacenados en la base de datos.
 * @return array{cantidad: int, unidad: string}
 */
function desglosarDuracionDias(int $dias): array {
    if ($dias >= 30 && $dias % 30 === 0) {
        return ['cantidad' => (int) ($dias / 30), 'unidad' => 'meses'];
    }
    if ($dias >= 7 && $dias % 7 === 0) {
        return ['cantidad' => (int) ($dias / 7), 'unidad' => 'semanas'];
    }
    return ['cantidad' => $dias, 'unidad' => 'dias'];
}
?>
