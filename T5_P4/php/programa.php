<?php
// Incluye la clase Pedido para poder usarla en este archivo
include_once __DIR__ . '/Pedido.php';

// Si el usuario presiona el boton borrar, se limpia la sesion y las cookies
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['borrar'])) {
    session_unset();
    session_destroy();

    // Se eliminan las cookies pasando una fecha en el pasado
    setcookie('cliente_nombre', '', time() - 3600, '/');
    setcookie('cliente_plato',  '', time() - 3600, '/');

    // Redirige a la misma pagina para refrescar el estado
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$pedidoGuardado = null;
$error = '';

// Procesar el formulario cuando se envia el pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pedir'])) {
    try {
        // Recoger y sanitizar los datos del formulario
        $cliente     = trim($_POST['cliente']     ?? '');
        $edad        = isset($_POST['edad'])     ? (int)$_POST['edad']     : 0;
        $plato           = $_POST['plato']                   ?? '';
        $bebida          = $_POST['bebida']                  ?? '';
        $postre          = $_POST['postre']                  ?? 'Ninguno';
        $cantidad        = isset($_POST['cantidad'])         ? (int)$_POST['cantidad'] : 1;
        $cantidad_bebida = isset($_POST['cantidad_bebida'])  ? (int)$_POST['cantidad_bebida'] : 1;
        $cantidad_postre = isset($_POST['cantidad_postre'])  ? (int)$_POST['cantidad_postre'] : 1;
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
        if ($cantidad < 1 || $cantidad > 10 || $cantidad_bebida < 1 || $cantidad_bebida > 10 || $cantidad_postre < 1 || $cantidad_postre > 10)
            throw new Exception("Las cantidades deben ser entre 1 y 10.");
        if (empty($tipo_pago))
            throw new Exception("Debe seleccionar un tipo de pago.");

        // Crear el objeto Pedido y obtener el detalle calculado
        $pedido  = new Pedido($cliente, $edad, $plato, $bebida, $postre, $cantidad, $cantidad_bebida, $cantidad_postre, $tipo_pago, $comentarios);
        $detalle = $pedido->getDetalleFactura();

        // Guardar cookies con duracion de 1 hora (nombre del cliente y plato favorito)
        setcookie('cliente_nombre', $cliente, time() + 3600, '/');
        setcookie('cliente_plato',  $plato,   time() + 3600, '/');

        // Actualizar $_COOKIE para que la vista las lea en la misma peticion
        $_COOKIE['cliente_nombre'] = $cliente;
        $_COOKIE['cliente_plato']  = $plato;

        // Guardar el pedido, la factura y el total en sesion
        $_SESSION['pedido']  = $detalle;
        $_SESSION['factura'] = $detalle;
        $_SESSION['total']   = $detalle['total'];

        $pedidoGuardado = true;

    } catch (Exception $e) {
        // Si algo falla, se guarda el mensaje de error para mostrarlo en pantalla
        $error = $e->getMessage();
    }
}
?>

<!-- Fila 1: Formulario centrado -->
<div class="row justify-content-center mb-4">
    <div class="col-lg-8 col-xl-7 d-flex flex-column">
        <?php include __DIR__ . '/../html/formulario.php'; ?>
    </div>
</div>

<!-- Muestra el error si hubo algun problema con el formulario -->
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

<!-- Fila 2: Factura centrada (solo si existe un pedido en sesion) -->
<?php if (isset($_SESSION['factura'])): ?>
<div class="row justify-content-center mb-4">
    <div class="col-lg-8 col-xl-7 d-flex flex-column">
        <h4 class="mb-3 text-secondary"><i class="bi bi-clipboard-data me-2"></i>Resultados</h4>
        <?php
        // Pasa los datos de sesion a la vista de la factura
        $factura = $_SESSION['factura'];
        include __DIR__ . '/../html/factura.php';
        ?>
    </div>
</div>
<?php else: ?>
<!-- Mensaje de estado vacio cuando no hay pedido activo -->
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

<!-- Fila 3: Acordeones que muestran el contenido de $_SESSION y $_COOKIE (solo si hay un pedido) -->
<?php if (isset($_SESSION['pedido'])): ?>
<div class="row justify-content-center mb-5">
    <div class="col-lg-8 col-xl-7">
        <h5 class="text-secondary fw-semibold mb-3"><i class="bi bi-code-slash me-2"></i>Datos Almacenados</h5>

        <div class="accordion accordion-flush shadow-sm rounded-3 overflow-hidden" id="acordeonDatos">

            <!-- Acordeon que muestra el contenido de $_SESSION -->
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

            <!-- Acordeon que muestra el contenido de $_COOKIE -->
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