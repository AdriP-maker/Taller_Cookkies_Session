<?php

session_start();
require_once __DIR__ . '/cursos.php';
require_once __DIR__ . '/validar_correo.php';

function limpiar(string $valor): string
{
    return htmlspecialchars(trim($valor), ENT_QUOTES, 'UTF-8');
}

function p3_redirect_errores(array $errores): void
{
    $_SESSION['p3_errores'] = array_values(array_unique($errores));
    $redirect = $_POST['redirect_to'] ?? ($_SESSION['p3_entry'] ?? 'T5_P3.php');
    header('Location: ' . $redirect);
    exit;
}

$errores = [];

/* ══════════════════════════════════════════
   1. RECEPCIÓN Y LLENADO DE ARRAYS
   ══════════════════════════════════════════ */
$estudiante = [
    "nombre"   => limpiar($_POST['nombre']   ?? ''),
    "correo"   => limpiar($_POST['correo']   ?? ''),
    "telefono" => limpiar($_POST['telefono'] ?? '')
];

$inscripcion = [
    "curso"     => limpiar($_POST['curso']     ?? ''),
    "modulos"   => (int)($_POST['modulos']     ?? 0),
    "turno"     => limpiar($_POST['turno']     ?? ''),
    "modalidad" => limpiar($_POST['modalidad'] ?? '')
];

/* ══════════════════════════════════════════
   2. VALIDACIONES ESTRICTAS (REPLICANDO EL HTML)
   ══════════════════════════════════════════ */
if ($estudiante['nombre'] === '') {
    $errores[] = 'El nombre es obligatorio.';
} elseif (!preg_match('/^[A-Za-záéíóúÁÉÍÓÚñÑ]+(\s[A-Za-záéíóúÁÉÍÓÚñÑ]+)+$/', $estudiante['nombre'])) {
    $errores[] = 'Formato de nombre inválido. Debe ingresar Nombre y Apellido (solo letras y espacios).';
}

$errorCorreo = p3_error_correo($estudiante['correo']);
if ($errorCorreo !== null) {
    $errores[] = $errorCorreo;
}

if ($estudiante['telefono'] === '') {
    $errores[] = 'El teléfono es obligatorio.';
} elseif (!preg_match('/^[0-9]{4}-?[0-9]{4}$/', $estudiante['telefono'])) {
    $errores[] = 'El formato de teléfono debe ser válido para Panamá (8 números, ej. 6000-0000 o 60000000).';
}

$modulosRaw = trim((string)($_POST['modulos'] ?? ''));
if ($modulosRaw === '') {
    $errores[] = 'La cantidad de módulos es obligatoria.';
} elseif (!ctype_digit($modulosRaw) || (int)$modulosRaw < 1 || (int)$modulosRaw > 12) {
    $errores[] = 'La cantidad de módulos debe estar entre 1 y 12.';
} else {
    $inscripcion['modulos'] = (int)$modulosRaw;
}

$cursoValido = array_key_exists($inscripcion['curso'], $cursos);

if ($inscripcion['curso'] === '') {
    $errores[] = 'Debe seleccionar un curso.';
} elseif (!$cursoValido) {
    $errores[] = 'El curso seleccionado no es válido.';
}

if (empty($inscripcion['turno'])) {
    $errores[] = 'Debe seleccionar un turno (Diurno o Nocturno).';
}
if (empty($inscripcion['modalidad'])) {
    $errores[] = 'Debe seleccionar una modalidad (Virtual o Presencial).';
}

if (!empty($errores)) {
    p3_redirect_errores($errores);
}

/* ══════════════════════════════════════════
   3. PROCESAMIENTO Y CÁLCULOS
   ══════════════════════════════════════════ */
$precio_modulo = $cursos[$inscripcion['curso']];

$subtotal_raw  = $precio_modulo * $inscripcion['modulos'];
$descuento_raw = ($inscripcion['modulos'] > 3) ? ($subtotal_raw * 0.20) : 0;
$itbms_raw     = $subtotal_raw * 0.07;
$total_raw     = $subtotal_raw - $descuento_raw + $itbms_raw;

$factura = [
    "precio_modulo" => $precio_modulo,
    "subtotal"      => $subtotal_raw,
    "descuento"     => $descuento_raw,
    "itbms"         => $itbms_raw,
    "total"         => $total_raw
];

/* ══════════════════════════════════════════
   4. PERSISTENCIA: COOKIES Y SESIÓN
   ══════════════════════════════════════════ */
$datosCookie = [
    'nombre_estudiante'   => $estudiante['nombre'],
    'correo_estudiante'   => $estudiante['correo'],
    'telefono_estudiante' => $estudiante['telefono']
];

foreach ($datosCookie as $nombreCookie => $valorCookie) {
    setcookie($nombreCookie, $valorCookie, time() + 3600, '/');
    $_COOKIE[$nombreCookie] = $valorCookie;
}

$_SESSION["datos"] = [
    "estudiante"  => $estudiante,
    "inscripcion" => $inscripcion,
    "factura"     => array_map(fn($v) => number_format($v, 2), $factura)
];

/* ══════════════════════════════════════════
   5. INCLUSIÓN DE LA VISTA DE RESULTADO
   ══════════════════════════════════════════ */
$pageTitle      = 'P3 — Factura de Inscripción | Taller 5';
$activePage     = 'p3';
$rootPath       = '../../';
$footerType     = 'programa';
$p3_return_link = $_SESSION['p3_entry'] ?? '../../T5_P3.php';
include __DIR__ . '/../html/resultado.html';
