<?php
include_once __DIR__ . '/Pedido.php';

// Si el usuario presiona el botón borrar, se limpia la sesión y las cookies
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['borrar'])) {
    session_unset();
    session_destroy();

    // Se eliminan las cookies pasando una fecha en el pasado
    setcookie('cliente_nombre', '', time() - 3600, '/');
    setcookie('cliente_plato',  '', time() - 3600, '/');

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$pedidoGuardado = null;
$error = '';

// Procesar el formulario cuando se envía el pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pedir'])) {
    try {
        // Recoger y sanitizar los datos del formulario
        $cliente     = trim($_POST['cliente']     ?? '');
        $edad        = isset($_POST['edad'])     ? (int)$_POST['edad']     : 0;
        $plato       = $_POST['plato']           ?? '';
        $bebida      = $_POST['bebida']          ?? '';
        $postre      = $_POST['postre']          ?? 'Ninguno';
        $cantidad    = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 1;
        $tipo_pago   = $_POST['tipo_pago']       ?? '';
        $comentarios = trim($_POST['comentarios'] ?? '');

        // Validaciones del lado del servidor
        if (empty($cliente))
            throw new Exception("El nombre del cliente es obligatorio.");
        if (!preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/', $cliente))
            throw new Exception("El nombre solo puede contener letras y espacios.");
        if ($edad < 10 || $edad > 120)
            throw new Exception("La edad debe ser entre 10 y 120 años.");
        if (empty($plato))
            throw new Exception("Debe seleccionar un plato principal.");
        if (empty($bebida))
            throw new Exception("Debe seleccionar una bebida.");
        if ($cantidad < 1 || $cantidad > 10)
            throw new Exception("La cantidad debe ser entre 1 y 10.");
        if (empty($tipo_pago))
            throw new Exception("Debe seleccionar un tipo de pago.");

        // Crear el objeto Pedido y obtener el detalle calculado
        $pedido  = new Pedido($cliente, $edad, $plato, $bebida, $postre, $cantidad, $tipo_pago, $comentarios);
        $detalle = $pedido->getDetalleFactura();

        // Guardar cookies con duración de 1 hora (nombre del cliente y plato favorito)
        setcookie('cliente_nombre', $cliente, time() + 3600, '/');
        setcookie('cliente_plato',  $plato,   time() + 3600, '/');

        // Actualizar $_COOKIE para que la vista las lea en la misma petición
        $_COOKIE['cliente_nombre'] = $cliente;
        $_COOKIE['cliente_plato']  = $plato;

        // Guardar el pedido, la factura y el total en sesión
        $_SESSION['pedido']  = $detalle;
        $_SESSION['factura'] = $detalle;
        $_SESSION['total']   = $detalle['total'];

        $pedidoGuardado = true;

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!-- ───────────────────────────────────────────
     FILA 1: Formulario centrado
──────────────────────────────────────────── -->
<div class="row justify-content-center mb-4">
    <div class="col-lg-8 col-xl-7 d-flex flex-column">
        <?php include __DIR__ . '/../html/formulario.php'; ?>
    </div>
</div>

<?php if (!empty($error)): ?>
<div class="row justify-content-center mb-3">
    <div class="col-lg-8 col-xl-7">
        <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ───────────────────────────────────────────
     FILA 2: Factura centrada (solo si existe)
──────────────────────────────────────────── -->
<?php if (isset($_SESSION['factura'])): ?>
<div class="row justify-content-center mb-4">
    <div class="col-lg-8 col-xl-7 d-flex flex-column">
        <h4 class="mb-3 text-secondary"><i class="bi bi-clipboard-data me-2"></i>Resultados</h4>
        <?php
        $factura = $_SESSION['factura'];
        include __DIR__ . '/../html/factura.php';
        ?>
    </div>
</div>
<?php else: ?>
<div class="row justify-content-center mb-4">
    <div class="col-lg-8 col-xl-7">
        <div class="card border-0 shadow-sm bg-white text-center p-5">
            <div class="card-body d-flex flex-column justify-content-center">
                <div class="display-1 text-danger mb-3 opacity-25"><i class="bi bi-receipt"></i></div>
                <h5 class="text-secondary fw-semibold">Sin pedidos activos</h5>
                <p class="text-muted mb-0">Complete el formulario para visualizar su factura y los datos almacenados en sesión y cookies.</p>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ───────────────────────────────────────────
     FILA 3: Acordeones $_SESSION y $_COOKIE
──────────────────────────────────────────── -->
<?php if (isset($_SESSION['pedido'])): ?>
<div class="row justify-content-center mb-5">
    <div class="col-lg-8 col-xl-7">
        <h5 class="text-secondary fw-semibold mb-3"><i class="bi bi-code-slash me-2"></i>Datos Almacenados</h5>

        <div class="accordion accordion-flush shadow-sm rounded-3 overflow-hidden" id="acordeonDatos">

            <!-- Acordeón $_SESSION -->
            <div class="accordion-item border-0">
                <h2 class="accordion-header" id="headingSession">
                    <button class="accordion-button collapsed fw-bold text-danger bg-white" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseSession"
                            aria-expanded="false" aria-controls="collapseSession">
                        <i class="bi bi-box-seam-fill me-2"></i>Array $_SESSION
                    </button>
                </h2>
                <div id="collapseSession" class="accordion-collapse collapse"
                     aria-labelledby="headingSession" data-bs-parent="#acordeonDatos">
                    <div class="accordion-body p-0">
                        <pre class="m-0 p-3 bg-light small text-dark" style="overflow-x:auto;"><code><?= htmlspecialchars(print_r($_SESSION, true)) ?></code></pre>
                    </div>
                </div>
            </div>

            <!-- Acordeón $_COOKIE -->
            <div class="accordion-item border-0 border-top">
                <h2 class="accordion-header" id="headingCookie">
                    <button class="accordion-button collapsed fw-bold text-warning bg-white" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseCookie"
                            aria-expanded="false" aria-controls="collapseCookie">
                        <i class="bi bi-cookie me-2"></i>Array $_COOKIE
                    </button>
                </h2>
                <div id="collapseCookie" class="accordion-collapse collapse"
                     aria-labelledby="headingCookie" data-bs-parent="#acordeonDatos">
                    <div class="accordion-body p-0">
                        <pre class="m-0 p-3 bg-light small text-dark" style="overflow-x:auto;"><code><?= htmlspecialchars(print_r($_COOKIE, true)) ?></code></pre>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php endif; ?>