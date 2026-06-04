<?php
include_once __DIR__ . '/Reserva.php';

// Manejo de Borrado de Cookies y Sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['borrar'])) {
    // Destruir sesión
    session_unset();
    session_destroy();
    
    // Destruir cookies
    setcookie('cliente_nombre', '', time() - 3600, '/');
    setcookie('cliente_pelicula', '', time() - 3600, '/');
    
    // Recargar la página para limpiar los datos POST
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Variables iniciales
$reservaGuardada = null;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservar'])) {
    try {
        $cliente = $_POST['cliente'] ?? '';
        $edad = isset($_POST['edad']) ? (int)$_POST['edad'] : 0;
        $sala = $_POST['sala'] ?? '';
        $tipo_pelicula = $_POST['tipo_pelicula'] ?? '';
        $cantidad_boletos = isset($_POST['cantidad_boletos']) ? (int)$_POST['cantidad_boletos'] : 1;
        $tiene_combo = isset($_POST['combo_comida']) ? true : false;
        
        // Validaciones
        if (empty($cliente)) throw new Exception("El nombre del cliente es obligatorio.");
        if (!preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/', $cliente)) throw new Exception("El nombre del cliente solo puede contener letras y espacios.");
        if ($edad < 4 || $edad > 120) throw new Exception("La edad debe ser mayor o igual a 4 años.");
        if ($cantidad_boletos < 1 || $cantidad_boletos > 8) throw new Exception("Puede comprar entre 1 y 8 boletos máximo.");
        
        // Crear objeto Reserva
        $reserva = new Reserva($cliente, $edad, $sala, $tipo_pelicula, $cantidad_boletos, $tiene_combo);
        $detalleFactura = $reserva->getDetalleFactura();
        
        // Guardar COOKIES EN 1 hora
        setcookie('cliente_nombre', $cliente, time() + 3600, '/');
        setcookie('cliente_pelicula', $tipo_pelicula, time() + 3600, '/');
        
        // Actualizar array de cookies manual para la vista inmediata
        $_COOKIE['cliente_nombre'] = $cliente;
        $_COOKIE['cliente_pelicula'] = $tipo_pelicula;
        
        // Guardar SESIÓN
        $_SESSION['reserva_completa'] = $detalleFactura;
        $_SESSION['cantidad_boletos'] = $cantidad_boletos;
        $_SESSION['total_compra'] = $detalleFactura['total'];
        
        $reservaGuardada = true;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!-- Vistas separadas-->
<div class="row">
    <div class="col-lg-6 mb-4 d-flex flex-column">
        <?php include __DIR__ . '/../html/formulario.php'; ?>
    </div>
    
    <div class="col-lg-6 mb-4 d-flex flex-column">
        <h4 class="mb-3 text-secondary"><i class="bi bi-clipboard-data me-2"></i>Resultados</h4>
        
        <?php if (isset($_SESSION['reserva_completa'])): ?>
            <?php 
            $factura = $_SESSION['reserva_completa']; 
            include __DIR__ . '/../html/factura.php'; 
            ?>
        <?php else: ?>
            <div class="card border-0 shadow-sm bg-white text-center p-5 d-flex justify-content-center flex-grow-1">
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="display-1 text-primary mb-3 opacity-25"><i class="bi bi-receipt"></i></div>
                    <h5 class="text-secondary fw-semibold">Sin reservas activas</h5>
                    <p class="text-muted mb-0">Complete el formulario para visualizar su factura y los datos almacenados en sesión y cookies.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if (isset($_SESSION['reserva_completa'])): ?>
    <?php include __DIR__ . '/../html/arrays.php'; ?>
<?php endif; ?>
