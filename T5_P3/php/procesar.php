<?php

/**
 * procesar.php - Lógica de Negocio y Control de Arrays
 * Se encarga del procesamiento algorítmico sin mezclar interfaces HTML directas.
 * Al terminar los ciclos e iteraciones, incluye la vista del resultado.
 */

session_start();
require_once __DIR__ . '/cursos.php';
require_once __DIR__ . '/validar_correo.php';

// Función auxiliar para limpieza de datos
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
       1. RECEPCIÓN Y LLENADO DE ARRAYS CON CICLOS
       ══════════════════════════════════════════ */
    $estudiante = [
        "nombre"   => "",
        "correo"   => "",
        "telefono" => ""
    ];
    $clavesEstudiante = array_keys($estudiante);

    for ($i = 0; $i < count($clavesEstudiante); $i++) {
        $clave = $clavesEstudiante[$i];
        if (isset($_POST[$clave])) {
            $estudiante[$clave] = limpiar($_POST[$clave]);
        }
    }

    $inscripcion = [
        "curso"     => "",
        "modulos"   => 0,
        "turno"     => "",
        "modalidad" => ""
    ];
    $clavesInscripcion = array_keys($inscripcion);

    for ($i = 0; $i < count($clavesInscripcion); $i++) {
        $clave = $clavesInscripcion[$i];
        if (isset($_POST[$clave])) {
            if ($clave === 'modulos') {
                $inscripcion[$clave] = (int)$_POST[$clave];
            } else {
                $inscripcion[$clave] = limpiar($_POST[$clave]);
            }
        }
    }

    /* ══════════════════════════════════════════
       2. VALIDACIONES ESTRICTAS (REPLICANDO EL HTML)
       ══════════════════════════════════════════ */

    // Validación de Nombre: Solo letras y espacios (Nombre Apellido). Sin números ni símbolos.
    // Expresión equivalente al pattern HTML: ^[A-Za-záéíóúÁÉÍÓÚñÑ]+(\s[A-Za-záéíóúÁÉÍÓÚñÑ]+)+$
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

    $modulosRaw = isset($_POST['modulos']) ? trim((string) $_POST['modulos']) : '';
    if ($modulosRaw === '') {
        $errores[] = 'La cantidad de módulos es obligatoria.';
    } elseif (!ctype_digit($modulosRaw) || (int) $modulosRaw < 1 || (int) $modulosRaw > 12) {
        $errores[] = 'La cantidad de módulos debe estar entre 1 y 12.';
    } else {
        $inscripcion['modulos'] = (int) $modulosRaw;
    }

    $cursoValido = false;
    $nombreCurso = $inscripcion['curso'];
    foreach ($cursos as $nombreDelCursoDisponible => $precio) {
        if ($nombreDelCursoDisponible === $nombreCurso) {
            $cursoValido = true;
            break;
        }
    }

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
       3. PROCESAMIENTO Y CÁLCULOS UTILIZANDO ARRAYS
       ══════════════════════════════════════════ */
    $precio_modulo = 0;
    foreach ($cursos as $nombreDelCurso => $precio) {
        if ($nombreDelCurso === $inscripcion['curso']) {
            $precio_modulo = $precio;
            break;
        }
    }

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
   4. PERSISTENCIA: COOKIES Y SESIONES PASO A PASO
   ══════════════════════════════════════════ */
    $datosCookie = [
        'nombre_estudiante'   => $estudiante['nombre'],
        'correo_estudiante'   => $estudiante['correo'],
        'telefono_estudiante' => $estudiante['telefono']
    ];

    $clavesCookies = array_keys($datosCookie);
    for ($i = 0; $i < count($clavesCookies); $i++) {
        $nombreCookie = $clavesCookies[$i];
        $valorCookie  = $datosCookie[$nombreCookie];

        // Guarda dinámicamente solo las 3 cookies personales en el navegador por 1 hora
        setcookie($nombreCookie, $valorCookie, time() + 3600, '/');
        // setcookie no actualiza $_COOKIE en la misma petición; reflejar para la vista
        $_COOKIE[$nombreCookie] = $valorCookie;
    }

    $_SESSION["datos"] = [
        "estudiante"  => [],
        "inscripcion" => [],
        "factura"     => []
    ];

    foreach ($estudiante as $clave => $valor) {
        $_SESSION["datos"]["estudiante"][$clave] = $valor;
    }
    foreach ($inscripcion as $clave => $valor) {
        $_SESSION["datos"]["inscripcion"][$clave] = $valor;
    }
    foreach ($factura as $clave => $valor) {
        $_SESSION["datos"]["factura"][$clave] = number_format($valor, 2);
    }

    // 5. INCLUSIÓN DE LA VISTA DE RESULTADO (ÉXITO)
    $pageTitle  = 'P3 — Factura de Inscripción | Taller 5';
    $activePage = 'p3';
    $rootPath   = '../../';
    $footerType = 'programa';
    $p3_return_link = isset($_SESSION['p3_entry']) ? $_SESSION['p3_entry'] : '../../T5_P3.php';
    include __DIR__ . '/../html/resultado.html';
