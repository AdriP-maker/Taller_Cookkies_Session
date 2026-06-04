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
   DEFINICIÓN DE CLASES (POO)
═══════════════════════════════════════════════════════ */

/**
 * Clase que representa el Pedido de medicamentos de un cliente.
 */
class Pedido {
    private $nombre;
    private $edad;
    private $medicamento;
    private $cantidad;
    private $tipoEntrega;
    private $telefono;
    private $fechaRetiro;

    public function __construct($nombre, $edad, $medicamento, $cantidad, $tipoEntrega, $telefono, $fechaRetiro) {
        $this->nombre = $nombre;
        $this->edad = $edad;
        $this->medicamento = $medicamento;
        $this->cantidad = $cantidad;
        $this->tipoEntrega = $tipoEntrega;
        $this->telefono = $telefono;
        $this->fechaRetiro = $fechaRetiro;
    }

    // Getters
    public function getNombre() { return $this->nombre; }
    public function getEdad() { return $this->edad; }
    public function getMedicamento() { return $this->medicamento; }
    public function getCantidad() { return $this->cantidad; }
    public function getTipoEntrega() { return $this->tipoEntrega; }
    public function getTelefono() { return $this->telefono; }
    public function getFechaRetiro() { return $this->fechaRetiro; }

    /**
     * Determina si el cliente pertenece a la tercera edad (60+ años).
     */
    public function esTerceraEdad() {
        return $this->edad >= 60;
    }

    /**
     * Valida los atributos del pedido.
     * Retorna un array con los mensajes de error encontrados.
     */
    public function validar($preciosValidos) {
        $errores = [];

        if (empty($this->nombre)) {
            $errores[] = 'El nombre es obligatorio.';
        }

        if ($this->edad < 1 || $this->edad > 120) {
            $errores[] = 'Ingrese una edad válida (1–120).';
        }

        if (!array_key_exists($this->medicamento, $preciosValidos)) {
            $errores[] = 'Seleccione un medicamento válido.';
        }

        if ($this->cantidad < 1 || $this->cantidad > 3) {
            $errores[] = 'La cantidad debe ser entre 1 y 3 cajas.';
        }

        if (empty($this->tipoEntrega)) {
            $errores[] = 'Seleccione un tipo de entrega.';
        }

        if (empty($this->telefono)) {
            $errores[] = 'El teléfono es obligatorio.';
        } elseif (!preg_match('/^[0-9]+$/', $this->telefono)) {
            $errores[] = 'El teléfono solo debe contener números enteros (sin letras ni caracteres especiales).';
        } elseif (strlen($this->telefono) !== 8) {
            $errores[] = 'el formato de numero debe tener 8 digitos';
        }

        if (empty($this->fechaRetiro)) {
            $errores[] = 'Seleccione una fecha de retiro.';
        }

        return $errores;
    }

    /**
     * Exporta los datos del objeto como un array asociativo.
     */
    public function toArray() {
        return [
            'nombre'       => $this->nombre,
            'edad'         => $this->edad,
            'medicamento'  => $this->medicamento,
            'cantidad'     => $this->cantidad,
            'tipo_entrega' => $this->tipoEntrega,
            'telefono'     => $this->telefono,
            'fecha_retiro' => $this->fechaRetiro,
        ];
    }
}

/**
 * Clase que gestiona los cálculos e importes de la Factura.
 */
class Factura {
    private $pedido;
    private $precioUnitario;
    private $subtotal;
    private $descuento;
    private $recargoDelivery;
    private $total;

    public function __construct(Pedido $pedido, $precios) {
        $this->pedido = $pedido;
        $this->precioUnitario = isset($precios[$pedido->getMedicamento()]) ? $precios[$pedido->getMedicamento()] : 0.0;
        $this->calcular();
    }

    /**
     * Realiza todos los cálculos del subtotal, descuento, delivery y total.
     */
    private function calcular() {
        $this->subtotal = $this->precioUnitario * $this->pedido->getCantidad();
        
        $porcentajeDesc = $this->pedido->esTerceraEdad() ? 10 : 0;
        $this->descuento = round($this->subtotal * ($porcentajeDesc / 100), 2);
        
        $subtotalConDesc = $this->subtotal - $this->descuento;
        $this->recargoDelivery = ($this->pedido->getTipoEntrega() === 'Delivery') ? 3.00 : 0.00;
        
        $this->total = $subtotalConDesc + $this->recargoDelivery;
    }

    /**
     * Exporta los detalles de la factura como un array asociativo.
     */
    public function toArray() {
        return [
            'precio_unitario'  => $this->precioUnitario,
            'subtotal'         => $this->subtotal,
            'es_tercera_edad'  => $this->pedido->esTerceraEdad(),
            'porcentaje_desc'  => $this->pedido->esTerceraEdad() ? 10 : 0,
            'descuento'        => $this->descuento,
            'recargo_delivery' => $this->recargoDelivery,
            'total'            => $this->total,
        ];
    }
}

/* ═══════════════════════════════════════════════════════
   PROCESAMIENTO DE DATOS (POO)
═══════════════════════════════════════════════════════ */

// Captura de datos POST
$nombre       = isset($_POST['nombre']) ? trim(htmlspecialchars($_POST['nombre'])) : '';
$edad         = isset($_POST['edad']) ? intval($_POST['edad']) : 0;
$medicamento  = isset($_POST['medicamento']) ? trim($_POST['medicamento']) : '';
$cantidad     = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 0;
$tipo_entrega = isset($_POST['tipo_entrega']) ? trim($_POST['tipo_entrega']) : '';
$telefono     = isset($_POST['telefono']) ? trim(htmlspecialchars($_POST['telefono'])) : '';
$fecha_retiro = isset($_POST['fecha_retiro']) ? trim($_POST['fecha_retiro']) : '';

// 1. Instanciar el objeto Pedido
$pedidoObj = new Pedido($nombre, $edad, $medicamento, $cantidad, $tipo_entrega, $telefono, $fecha_retiro);

// 2. Validar el pedido con el método del objeto
$errores = $pedidoObj->validar($precios);

/* ── Redirigir si hay errores ── */
if (!empty($errores)) {
    $_SESSION['errores'] = $errores;
    $redirect_to = isset($_POST['redirect_to']) ? $_POST['redirect_to'] : '../../T5_P2.php';
    header('Location: ' . $redirect_to);
    exit;
}

// 3. Instanciar la Factura pasando el objeto Pedido (Composición)
$facturaObj = new Factura($pedidoObj, $precios);

// 4. Convertir a arrays para mantener compatibilidad y almacenamiento
$pedido  = $pedidoObj->toArray();
$factura = $facturaObj->toArray();

/* ═══════════════════════════════════════════════════════
   COOKIES – nombre y tipo de entrega favorita (30 días)
═══════════════════════════════════════════════════════ */
setcookie('nombre_cliente', $pedido['nombre'],       time() + (86400 * 30), '/');
setcookie('tipo_entrega',   $pedido['tipo_entrega'], time() + (86400 * 30), '/');

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
$groupMembers = ['José Ortega', 'Jesús Rodríguez', 'Jaseth Castillo', 'Eliam Fernández'];

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
