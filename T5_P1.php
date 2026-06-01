<?php
$pageTitle  = 'P1 — Reserva de Cine | Taller 5';
$activePage = 'p1';
$rootPath   = '';
$footerType = 'programa';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $pageTitle ?></title>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="T5_P1/css/p1.css">
</head>
<body>

<?php include 'php/nav.php'; ?>

<div class="container py-4">

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none"><i class="bi bi-house me-1"></i>Inicio</a></li>
      <li class="breadcrumb-item active">Programa 1</li>
    </ol>
  </nav>

  <!-- Encabezado -->
  <div class="d-flex align-items-center gap-3 mb-4">
    <div class="prog-icon bg-primary bg-opacity-10 text-primary flex-shrink-0">🎬</div>
    <div>
      <span class="badge bg-primary-subtle text-primary border border-primary-subtle mb-1">Programa 01</span>
      <h1 class="h3 fw-bold mb-0">Reserva de Cine</h1>
      <p class="text-muted small mb-0">
        Cookies y Sesiones &bull; Arrays asociativos &bull; POO &bull; Bootstrap 5.3.3
      </p>
    </div>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom fw-semibold d-flex align-items-center gap-2">
      <i class="bi bi-code-slash text-primary"></i>Implementación
    </div>
    <div class="card-body">
      <?php include 'T5_P1/php/programa.php'; ?>
    </div>
  </div>
</div>

<?php include 'php/footer.php'; ?>
</body>
</html>
