<?php
session_start();

/* ═══════════════════════════════════════════════════════
   PRECIOS DE MEDICAMENTOS
═══════════════════════════════════════════════════════ */
$precios = [
    'Paracetamol' => 3.00,
    'Ibuprofeno'  => 20.00,
    'Vitamina C en tabletas' => 15.00,
];

/* ═══════════════════════════════════════════════════════
   VALIDAR DATOS DEL FORMULARIO
═══════════════════════════════════════════════════════ */
$errores = [];

$nombre = isset($_POST['nombre']) ? trim(htmlspecialchars($_POST['nombre'])) : '';
if (empty($nombre)) $errores[] = 'El nombre es obligatorio.';

$edad = isset($_POST['edad']) ? intval($_POST['edad']) : 0;
if ($edad < 1 || $edad > 120) $errores[] = 'Ingrese una edad válida (1–120).';

$medicamento = isset($_POST['medicamento']) ? trim($_POST['medicamento']) : '';
if (!array_key_exists($medicamento, $precios)) $errores[] = 'Seleccione un medicamento válido.';

$cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 0;
if ($cantidad < 1 || $cantidad > 3) $errores[] = 'La cantidad debe ser entre 1 y 3 cajas.';

$tipo_entrega = isset($_POST['tipo_entrega']) ? trim($_POST['tipo_entrega']) : '';
if (empty($tipo_entrega)) $errores[] = 'Seleccione un tipo de entrega.';

$telefono = isset($_POST['telefono']) ? trim(htmlspecialchars($_POST['telefono'])) : '';
if (empty($telefono)) {
    $errores[] = 'El teléfono es obligatorio.';
} elseif (!preg_match('/^[0-9]+$/', $telefono)) {
    $errores[] = 'El teléfono solo debe contener números.';
}

$fecha_retiro = isset($_POST['fecha_retiro']) ? trim($_POST['fecha_retiro']) : '';
if (empty($fecha_retiro)) $errores[] = 'Seleccione una fecha de retiro.';

/* ── Redirigir si hay errores ── */
if (!empty($errores)) {
    $_SESSION['errores'] = $errores;
    $redirect_to = isset($_POST['redirect_to']) ? $_POST['redirect_to'] : '../../T5_P2.php';
    header('Location: ' . $redirect_to);
    exit;
}

/* ═══════════════════════════════════════════════════════
   CÁLCULO DE FACTURA
═══════════════════════════════════════════════════════ */
$precio_unitario  = $precios[$medicamento];
$subtotal         = $precio_unitario * $cantidad;

$es_tercera_edad  = ($edad >= 60);
$porcentaje_desc  = $es_tercera_edad ? 10 : 0;
$descuento        = round($subtotal * ($porcentaje_desc / 100), 2);
$subtotal_desc    = $subtotal - $descuento;

$recargo_delivery = ($tipo_entrega === 'Delivery') ? 3.00 : 0.00;
$total            = $subtotal_desc + $recargo_delivery;

/* ═══════════════════════════════════════════════════════
   ARRAY $pedido
═══════════════════════════════════════════════════════ */
$pedido = [
    'nombre'       => $nombre,
    'edad'         => $edad,
    'medicamento'  => $medicamento,
    'cantidad'     => $cantidad,
    'tipo_entrega' => $tipo_entrega,
    'telefono'     => $telefono,
    'fecha_retiro' => $fecha_retiro,
];

/* ═══════════════════════════════════════════════════════
   ARRAY $factura
═══════════════════════════════════════════════════════ */
$factura = [
    'precio_unitario'  => $precio_unitario,
    'subtotal'         => $subtotal,
    'es_tercera_edad'  => $es_tercera_edad,
    'porcentaje_desc'  => $porcentaje_desc,
    'descuento'        => $descuento,
    'recargo_delivery' => $recargo_delivery,
    'total'            => $total,
];

/* ═══════════════════════════════════════════════════════
   COOKIES – nombre y tipo de entrega favorita (30 días)
═══════════════════════════════════════════════════════ */
setcookie('nombre_cliente', $nombre,       time() + (86400 * 30), '/');
setcookie('tipo_entrega',   $tipo_entrega, time() + (86400 * 30), '/');

/* ═══════════════════════════════════════════════════════
   SESIÓN – pedido completo + factura
═══════════════════════════════════════════════════════ */
$_SESSION['pedido']  = $pedido;
$_SESSION['factura'] = $factura;

// Variables del sistema general
$pageTitle  = 'P2 — Confirmación de Pedido | Taller 5';
$activePage = 'p2';
$rootPath   = '../../';
$footerType = 'programa';

// Enlace de retorno dinámico (portal vs standalone)
$p2_return_link = isset($_SESSION['p2_entry']) ? $_SESSION['p2_entry'] : '../../T5_P2.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $pageTitle ?></title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../css/styles.css">
  <link rel="stylesheet" href="../css/p2.css">
</head>
<body class="p2-app">

<?php include '../../php/nav.php'; ?>

<!-- Banner éxito -->
<div class="banner-success d-flex align-items-center gap-3 px-4">
  <div class="checkmark"><i class="bi bi-check-lg"></i></div>
  <div>
    <h2>¡Pedido Registrado!</h2>
    <p class="mb-0" style="opacity:.85;">
      Hola <strong><?= htmlspecialchars($pedido['nombre']) ?></strong>, su pedido fue procesado correctamente.
    </p>
    <div class="mt-2 d-flex flex-wrap gap-2">
      <span class="badge-cookie"><i class="bi bi-cookie me-1"></i>Cookie guardada: nombre + tipo de entrega</span>
      <span class="badge-sesion"><i class="bi bi-clock me-1"></i>Sesión activa: pedido + factura</span>
    </div>
  </div>
</div>

<!-- CONTENIDO -->
<div class="container py-4">
  <div class="row g-4">

    <!-- Columna izquierda: Pedido + Factura -->
    <div class="col-lg-7">

      <div class="card-section p-4 mb-4">
        <div class="section-title"><i class="bi bi-bag me-2"></i>Detalle del Pedido</div>
        <table class="table tbl-data mb-0">
          <tbody>
            <tr>
              <td class="lbl">Nombre</td>
              <td><strong><?= htmlspecialchars($pedido['nombre']) ?></strong></td>
            </tr>
            <tr>
              <td class="lbl">Edad</td>
              <td>
                <strong><?= $pedido['edad'] ?> años</strong>
                <?php if ($factura['es_tercera_edad']): ?>
                  <span class="badge bg-warning text-dark ms-2"><i class="bi bi-person-badge me-1"></i>Tercera Edad</span>
                <?php endif; ?>
              </td>
            </tr>
            <tr>
              <td class="lbl">Medicamento</td>
              <td><strong><?= htmlspecialchars($pedido['medicamento']) ?></strong></td>
            </tr>
            <tr>
              <td class="lbl">Cantidad</td>
              <td><strong><?= $pedido['cantidad'] ?> caja(s)</strong></td>
            </tr>
            <tr>
              <td class="lbl">Tipo de Entrega</td>
              <td>
                <strong><?= htmlspecialchars($pedido['tipo_entrega']) ?></strong>
                <?php if ($pedido['tipo_entrega'] === 'Delivery'): ?>
                  <span class="badge bg-info text-dark ms-1">+$3.00</span>
                <?php endif; ?>
              </td>
            </tr>
            <tr>
              <td class="lbl">Teléfono</td>
              <td><strong><?= htmlspecialchars($pedido['telefono']) ?></strong></td>
            </tr>
            <tr>
              <td class="lbl">Fecha de Retiro</td>
              <td><strong><?php 
                $partes = explode('-', $pedido['fecha_retiro']);
                echo isset($partes[2]) ? "{$partes[2]}/{$partes[1]}/{$partes[0]}" : htmlspecialchars($pedido['fecha_retiro']);
              ?></strong></td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="card-section p-4">
        <div class="section-title"><i class="bi bi-receipt me-2"></i>Factura</div>
        <div class="text-center mb-3 pb-3" style="border-bottom:2px dashed #d0e8e4;">
          <div style="font-family:'DM Serif Display',serif;font-size:1.3rem;color:var(--verde-oscuro);">
            <i class="bi bi-plus-circle-fill me-1"></i> FarmaPlus
          </div>
          <div style="font-size:.78rem;color:#888;">
            Fecha: <?= date('d/m/Y H:i') ?>
          </div>
        </div>
        
        <div id="lineas-factura">
          <div class="factura-row">
            <span><?= htmlspecialchars($pedido['medicamento']) ?> × <?= $pedido['cantidad'] ?></span>
            <span>$<?= number_format($factura['precio_unitario'], 2) ?> c/u</span>
          </div>
          <div class="factura-row">
            <span>Subtotal</span>
            <span><strong>$<?= number_format($factura['subtotal'], 2) ?></strong></span>
          </div>
          <?php if ($factura['descuento'] > 0): ?>
            <div class="factura-row factura-desc">
              <span><i class="bi bi-tag me-1"></i>Descuento Tercera Edad (<?= $factura['porcentaje_desc'] ?>%)</span>
              <span>– $<?= number_format($factura['descuento'], 2) ?></span>
            </div>
          <?php endif; ?>
          <?php if ($factura['recargo_delivery'] > 0): ?>
            <div class="factura-row factura-recargo">
              <span><i class="bi bi-truck me-1"></i>Recargo Delivery</span>
              <span>+ $<?= number_format($factura['recargo_delivery'], 2) ?></span>
            </div>
          <?php endif; ?>
          <div class="factura-row mt-2 pt-2" style="border-top:2px dashed #d0e8e4;border-bottom:none;">
            <span class="factura-total">TOTAL A PAGAR</span>
            <span class="factura-total">$<?= number_format($factura['total'], 2) ?></span>
          </div>
        </div>

        <?php if ($factura['es_tercera_edad']): ?>
          <div class="alert alert-warning py-2 mt-3 mb-0" style="border-radius:10px;font-size:.85rem;">
            <i class="bi bi-person-badge me-1"></i>
            Se aplicó <strong>10% de descuento</strong> por ser cliente de tercera edad.
            Ahorro: <strong>$<?= number_format($factura['descuento'], 2) ?></strong>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Columna derecha: Arrays -->
    <div class="col-lg-5">

      <div class="card-section p-4 mb-4">
        <div class="section-title"><i class="bi bi-code-square me-2"></i>Array <code>$pedido</code></div>
        <div class="array-box" id="dump-pedido">
          <pre class="mb-0 text-success" style="font-family: inherit; font-size: inherit; color: inherit; background: transparent; border: none; padding: 0; margin: 0;"><?php print_r($pedido); ?></pre>
        </div>
      </div>

      <div class="card-section p-4 mb-4">
        <div class="section-title"><i class="bi bi-code-square me-2"></i>Array <code>$factura</code></div>
        <div class="array-box" id="dump-factura">
          <pre class="mb-0 text-success" style="font-family: inherit; font-size: inherit; color: inherit; background: transparent; border: none; padding: 0; margin: 0;"><?php print_r($factura); ?></pre>
        </div>
      </div>

      <div class="card-section p-4">
        <div class="section-title"><i class="bi bi-info-circle me-2"></i>Almacenamiento</div>
        <div style="font-size:.85rem;">
          <div class="mb-3">
            <div class="fw-bold mb-1"><i class="bi bi-cookie text-warning me-1"></i>Cookie (30 días)</div>
            <div class="array-box" id="dump-cookies" style="font-size:.78rem;">
              [<span class="array-key">nombre_cliente</span>] = <span class="array-val"><?= htmlspecialchars($pedido['nombre']) ?></span><br>
              [<span class="array-key">tipo_entrega</span>] = <span class="array-val"><?= htmlspecialchars($pedido['tipo_entrega']) ?></span>
            </div>
          </div>
          <div>
            <div class="fw-bold mb-1"><i class="bi bi-clock text-info me-1"></i>Sesión (temporal)</div>
            <div class="array-box" style="font-size:.78rem;">
              <span class="array-key">$_SESSION['pedido']</span> = <span class="array-val">array (7 elementos)</span><br>
              <span class="array-key">$_SESSION['factura']</span> = <span class="array-val">array (7 elementos)</span>
            </div>
          </div>
        </div>
        <div class="d-grid gap-2 mt-3">
          <a href="<?= htmlspecialchars($p2_return_link) ?>" class="btn btn-volver text-white">
            <i class="bi bi-arrow-left me-2"></i>Nuevo Pedido
          </a>
          <a href="destruir.php" class="btn btn-outline-danger" style="border-radius:12px;">
            <i class="bi bi-trash3 me-2"></i>Destruir Sesión y Cookies
          </a>
        </div>
      </div>

    </div>
  </div>
</div>

<?php include '../../php/footer.php'; ?>
<script src="../js/scripts.js"></script>
</body>
</html>
