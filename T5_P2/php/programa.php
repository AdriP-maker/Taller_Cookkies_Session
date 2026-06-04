<?php
// Evitar múltiples session_start si ya está activa
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Guardar la página de entrada de P2 en la sesión para retornar adecuadamente después de procesar
$_SESSION['p2_entry'] = $_SERVER['REQUEST_URI'];

$errores = isset($_SESSION['errores']) ? $_SESSION['errores'] : [];
unset($_SESSION['errores']);

// Leer cookies para pre-llenar el formulario
$nombre_cookie = isset($_COOKIE['nombre_cliente']) ? htmlspecialchars($_COOKIE['nombre_cliente']) : '';
$entrega_cookie = isset($_COOKIE['tipo_entrega']) ? htmlspecialchars($_COOKIE['tipo_entrega']) : '';

// Las rutas siempre asumen que se ejecutan desde el portal general T5_P2.php
$p2FormAction = 'T5_P2/php/procesar.php';
$p2DestruirLink = 'T5_P2/php/destruir.php';
?>

<div class="p2-app">
  <!-- ═══ HERO ══════════════════════════════════════════════════ -->
  <div class="p2-hero mb-4 rounded-4">
    <div class="container py-2">
      <h2 class="h1 fw-bold text-white mb-2" style="font-family: 'DM Serif Display', serif;">Pedido de Medicamentos</h2>
      <p class="mb-0 opacity-85">Registre su pedido con entrega a domicilio o retiro en tienda. Máximo <strong>3
          cajas</strong> por cliente.</p>
    </div>
  </div>

  <!-- Aviso cookie (visible solo si JS o PHP detecta cookie guardada) -->
  <div id="aviso-cookie" class="cookie-alert mb-4 align-items-center gap-3"
    style="display: <?php echo (!empty($nombre_cookie) || !empty($entrega_cookie)) ? 'flex' : 'none'; ?>;">
    <i class="bi bi-cookie fs-4 text-warning"></i>
    <div>
      <strong>¡Bienvenido de vuelta, <span
          id="cookie-nombre"><?php echo !empty($nombre_cookie) ? $nombre_cookie : 'Cliente'; ?></span>!</strong><br>
      Detectamos sus preferencias guardadas: entrega preferida =
      <span id="cookie-entrega"
        class="fw-bold"><?php echo !empty($entrega_cookie) ? $entrega_cookie : 'Ninguna'; ?></span>.
      El formulario fue pre-llenado.
    </div>
  </div>

  <div class="row g-4">

    <!-- ── COLUMNA IZQUIERDA ── -->
    <div class="col-lg-8">

      <!-- Mostrar errores de validación de PHP si existen -->
      <?php if (!empty($errores)): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert"
          style="border-radius:12px; box-shadow: 0 4px 15px rgba(220,53,69,.15);">
          <div class="fw-bold mb-1">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>Por favor corrija los siguientes errores:
          </div>
          <ul class="mb-0 ps-3">
            <?php foreach ($errores as $error): ?>
              <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <!-- Tabla de precios -->
      <div class="card-main p-4 mb-4">
        <div class="section-title"><i class="bi bi-tag me-2"></i>Precios de Medicamentos</div>
        <div class="table-responsive">
          <table class="table price-table mb-0 rounded overflow-hidden">
            <thead>
              <tr>
                <th>Medicamento</th>
                <th>Precio por Caja</th>
                <th>Categoría</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><i class="bi bi-capsule me-1 text-success"></i>Paracetamol</td>
                <td><strong>$3.00</strong></td>
                <td><span class="badge bg-secondary">Genérico</span></td>
              </tr>
              <tr>
                <td><i class="bi bi-capsule me-1 text-primary"></i>Ibuprofeno</td>
                <td><strong>$20.00</strong></td>
                <td><span class="badge bg-secondary">Antiinflamatorio</span></td>
              </tr>
              <tr>
                <td><i class="bi bi-capsule me-1 text-warning"></i>Vitamina C en tabletas</td>
                <td><strong>$15.00</strong></td>
                <td><span class="badge bg-secondary">Suplemento</span></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="mt-2 d-flex flex-wrap gap-2">
          <span class="badge bg-warning text-dark">
            <i class="bi bi-person-badge me-1"></i>10% descuento tercera edad (60+ años)
          </span>
          <span class="badge bg-info text-dark">
            <i class="bi bi-truck me-1"></i>Delivery: +$3.00 de recargo
          </span>
          <span class="badge bg-danger">
            <i class="bi bi-box me-1"></i>Máximo 3 cajas por cliente
          </span>
        </div>
      </div>

      <!-- Formulario -->
      <div class="card-main p-4">
        <div class="section-title"><i class="bi bi-clipboard2-pulse me-2"></i>Datos del Pedido</div>

        <form action="<?= $p2FormAction ?>" method="POST" novalidate>
          <!-- Redirección dinámica en caso de error de validación -->
          <input type="hidden" name="redirect_to" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">

          <!-- Fila 1: Nombre + Edad -->
          <div class="row g-3 mb-3">
            <div class="col-sm-7">
              <label class="form-label" for="nombre">Nombre Completo</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej. María González"
                  value="<?php echo $nombre_cookie; ?>" required>
              </div>
            </div>
            <div class="col-sm-5">
              <label class="form-label" for="edad">Edad</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                <input type="number" class="form-control" id="edad" name="edad" placeholder="Años" min="1" max="120"
                  required>
              </div>
            </div>
          </div>

          <!-- Fila 2: Medicamento + Cantidad -->
          <div class="row g-3 mb-3">
            <div class="col-sm-7">
              <label class="form-label" for="medicamento">Medicamento</label>
              <select class="form-select" id="medicamento" name="medicamento" required>
                <option value="" disabled selected>-- Seleccione --</option>
                <option value="Paracetamol">Paracetamol – $3.00/caja</option>
                <option value="Ibuprofeno">Ibuprofeno – $20.00/caja</option>
                <option value="Vitamina C en tabletas">Vitamina C en tabletas – $15.00/caja</option>
              </select>
            </div>
            <div class="col-sm-5">
              <label class="form-label" for="cantidad">Cantidad (cajas)</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-boxes"></i></span>
                <input type="number" class="form-control" id="cantidad" name="cantidad" placeholder="1 – 3" min="1"
                  max="3" required>
              </div>
              <div class="form-text text-danger">Máximo 3 cajas.</div>
            </div>
          </div>

          <!-- Fila 3: Tipo entrega + Teléfono -->
          <div class="row g-3 mb-3">
            <div class="col-sm-6">
              <label class="form-label" for="tipo_entrega">Tipo de Entrega</label>
              <select class="form-select" id="tipo_entrega" name="tipo_entrega" required>
                <option value="" disabled <?php echo empty($entrega_cookie) ? 'selected' : ''; ?>>-- Seleccione --
                </option>
                <option value="Retiro en tienda" <?php echo ($entrega_cookie === 'Retiro en tienda') ? 'selected' : ''; ?>>🏪 Retiro en tienda</option>
                <option value="Delivery" <?php echo ($entrega_cookie === 'Delivery') ? 'selected' : ''; ?>>🚚 Delivery
                  (+$3.00)</option>
              </select>
            </div>
            <div class="col-sm-6">
              <label class="form-label" for="telefono">Teléfono</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="Ej. 68001234"
                  oninput="this.value = this.value.replace(/[^0-9]/g, '')" min="6" max="15" required>
              </div>
            </div>
          </div>

          <!-- Fila 4: Fecha de retiro -->
          <div class="row g-3 mb-4">
            <div class="col-sm-6">
              <label class="form-label" for="fecha_retiro">Fecha de Retiro</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-calendar-check"></i></span>
                <input type="date" class="form-control" id="fecha_retiro" name="fecha_retiro" required>
              </div>
            </div>
          </div>

          <!-- Botones -->
          <div class="d-flex flex-wrap gap-3 align-items-center">
            <button type="submit" class="btn btn-primary-custom text-white">
              <i class="bi bi-cart-check me-2"></i>Registrar Pedido
            </button>
            <button type="reset" class="btn btn-outline-secondary" style="border-radius:12px;">
              <i class="bi bi-arrow-counterclockwise me-1"></i>Limpiar
            </button>
          </div>

        </form>
      </div><!-- /card formulario -->
    </div><!-- /col-lg-8 -->

    <!-- ── COLUMNA DERECHA ── -->
    <div class="col-lg-4">

      <!-- Estado de cookies -->
      <div class="card-main p-4 mb-4">
        <div class="section-title"><i class="bi bi-cookie me-2"></i>Cookies Activas</div>
        <div id="panel-cookies">
          <p class="text-muted mb-0" style="font-size:.88rem;">
            <i class="bi bi-info-circle me-1"></i>No hay cookies guardadas aún.
          </p>
        </div>
      </div>

      <!-- Ayuda -->
      <div class="card-main p-4" style="background:var(--gris-suave);">
        <div class="section-title"><i class="bi bi-question-circle me-2"></i>¿Cómo funciona?</div>
        <ol style="font-size:.84rem;padding-left:1.2rem;line-height:1.8;">
          <li>Complete el formulario y envíe.</li>
          <li>Su <strong>nombre</strong> y <strong>tipo de entrega</strong> se guardan en
            <em>cookies</em> (30 días).
          </li>
          <li>El <strong>pedido completo</strong> y la <strong>factura</strong> se guardan
            en <em>sesión</em>.</li>
          <li>Use el botón <em>"Destruir Sesión"</em> para limpiar todo.</li>
        </ol>
        <a href="<?= $p2DestruirLink ?>" class="btn btn-danger-custom text-white w-100 mt-2">
          <i class="bi bi-trash3 me-2"></i>Destruir Sesión y Cookies
        </a>
      </div>

    </div><!-- /col-lg-4 -->
  </div><!-- /row -->
</div>