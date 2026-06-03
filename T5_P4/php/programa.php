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
        // Al menos uno de los tres debe estar seleccionado
        if (empty($plato) && empty($bebida) && $postre === 'Ninguno')
            throw new Exception("Debe seleccionar al menos un plato, bebida o postre.");
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

<div class="row">

    <!-- Columna izquierda: formulario -->
    <div class="col-lg-6 mb-4 d-flex flex-column">
        <?php include __DIR__ . '/../html/formulario.php'; ?>
    </div>

    <!-- Columna derecha: factura o estado vacío -->
    <div class="col-lg-6 mb-4 d-flex flex-column">
        <h4 class="mb-3 text-secondary"><i class="bi bi-clipboard-data me-2"></i>Resultados</h4>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['factura'])): ?>
            <?php
            $factura = $_SESSION['factura'];
            include __DIR__ . '/../html/factura.php';
            ?>
        <?php else: ?>
            <div class="card border-0 shadow-sm bg-white text-center p-5 d-flex justify-content-center flex-grow-1">
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="display-1 text-danger mb-3 opacity-25"><i class="bi bi-receipt"></i></div>
                    <h5 class="text-secondary fw-semibold">Sin pedidos activos</h5>
                    <p class="text-muted mb-0">Complete el formulario para visualizar su factura y los datos almacenados en sesión y cookies.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if (isset($_SESSION['pedido'])): ?>
    <?php include __DIR__ . '/../html/arrays.php'; ?>
<?php endif; ?>
