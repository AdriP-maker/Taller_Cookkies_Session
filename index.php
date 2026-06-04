<?php
$pageTitle  = 'Taller 5 — Cookies y Sesiones en PHP';
$activePage = 'index';
$rootPath   = '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $pageTitle ?></title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<?php include 'php/nav.php'; ?>

<!-- Hero -->
<section class="hero-section text-white py-5">
  <div class="container py-4 text-center">
    <h1 class="display-4 fw-bold mb-2">
      Taller <span class="text-warning">5</span>
    </h1>
    <p class="lead mb-0">Investigación Aplicada: Cookies y Sesiones en PHP</p>
  </div>
</section>

<!-- Programas -->
<main class="container py-5">
  <div class="text-center mb-5">
    <h2 class="fw-bold">Programas</h2>
    <p class="text-muted">Selecciona un programa para acceder a su implementación</p>
  </div>

  <div class="row g-4">

    <!-- P1: Reserva de Cine -->
    <div class="col-sm-6 col-xl-3">
      <div class="card h-100 shadow-sm border-0 card-prog-1">
        <div class="card-body d-flex flex-column p-4">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div class="prog-icon bg-primary bg-opacity-10 text-primary">🎬</div>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle">PHP</span>
          </div>
          <span class="badge bg-secondary-subtle text-secondary mb-2 align-self-start">Programa 01</span>
          <h5 class="card-title fw-bold">Reserva de Cine</h5>
          <p class="card-text text-muted flex-grow-1 small">
            Un cine almacena temporalmente las preferencias del cliente durante la reserva.
            Cookies guardan nombre y película favorita (1 hora); la sesión guarda la reserva
            completa, boletos y total con ITBMS y descuento por tercera edad.
          </p>
          <a href="T5_P1.php" class="btn btn-primary mt-3">
            Ver Programa <i class="bi bi-arrow-right ms-1"></i>
          </a>
        </div>
      </div>
    </div>

    <!-- P2: Pedido de Farmacia -->
    <div class="col-sm-6 col-xl-3">
      <div class="card h-100 shadow-sm border-0 card-prog-2">
        <div class="card-body d-flex flex-column p-4">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div class="prog-icon bg-success bg-opacity-10 text-success">💊</div>
            <span class="badge bg-success-subtle text-success border border-success-subtle">PHP</span>
          </div>
          <span class="badge bg-secondary-subtle text-secondary mb-2 align-self-start">Programa 02</span>
          <h5 class="card-title fw-bold">Pedido de Farmacia</h5>
          <p class="card-text text-muted flex-grow-1 small">
            Una farmacia registra pedidos temporales de medicamentos y recuerda preferencias.
            Cookies almacenan nombre y tipo de entrega favorita; la sesión guarda el pedido
            completo y la factura con descuento para tercera edad.
          </p>
          <a href="T5_P2.php" class="btn btn-success mt-3">
            Ver Programa <i class="bi bi-arrow-right ms-1"></i>
          </a>
        </div>
      </div>
    </div>

    <!-- P3: Inscripción Instituto -->
    <div class="col-sm-6 col-xl-3">
      <div class="card h-100 shadow-sm border-0 card-prog-3">
        <div class="card-body d-flex flex-column p-4">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div class="prog-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-mortarboard"></i></div>
            <span class="badge bg-warning-subtle text-warning border border-warning-subtle">PHP</span>
          </div>
          <span class="badge bg-secondary-subtle text-secondary mb-2 align-self-start">Programa 03</span>
          <h5 class="card-title fw-bold">Inscripción Instituto</h5>
          <p class="card-text text-muted flex-grow-1 small">
            Un instituto almacena inscripciones a cursos técnicos (PHP, Java, Redes).
            Cookies guardan nombre y curso favorito; la sesión almacena cursos inscritos
            y total con descuento por módulos e ITBMS 7%.
          </p>
          <a href="T5_P3.php" class="btn btn-warning mt-3">
            Ver Programa <i class="bi bi-arrow-right ms-1"></i>
          </a>
        </div>
      </div>
    </div>

    <!-- P4: Pedido de Restaurante -->
    <div class="col-sm-6 col-xl-3">
      <div class="card h-100 shadow-sm border-0 card-prog-4">
        <div class="card-body d-flex flex-column p-4">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div class="prog-icon bg-danger bg-opacity-10 text-danger">🍽️</div>
            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">PHP</span>
          </div>
          <span class="badge bg-secondary-subtle text-secondary mb-2 align-self-start">Programa 04</span>
          <h5 class="card-title fw-bold">Pedido de Restaurante</h5>
          <p class="card-text text-muted flex-grow-1 small">
            Un restaurante registra pedidos temporales y recuerda preferencias del cliente.
            Cookies almacenan nombre y plato favorito; la sesión guarda el pedido, factura
            y total con ITBMS y descuento para mayores de 55 años.
          </p>
          <a href="T5_P4.php" class="btn btn-danger mt-3">
            Ver Programa <i class="bi bi-arrow-right ms-1"></i>
          </a>
        </div>
      </div>
    </div>

  </div><!-- /row -->


</main>

<?php include 'php/footer.php'; ?>
</body>
</html>
