<?php
session_start();
ob_start();
$pageTitle  = 'P4 — Pedido de Restaurante | Taller 5';
$activePage = 'p4';
$rootPath   = '';
$footerType = 'programa';
$groupMembers = ['Celeste Pérez', 'Adrian Pérez', 'Josue Flores', 'Josael Zurita'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $pageTitle ?></title>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="T5_P4/css/p4.css">
</head>
<body>

<?php include 'php/nav.php'; ?>

<div class="container py-4">

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none"><i class="bi bi-house me-1"></i>Inicio</a></li>
      <li class="breadcrumb-item active">Programa 4</li>
    </ol>
  </nav>

  <!-- Encabezado -->
  <div class="d-flex align-items-center gap-3 mb-4">
    <div class="prog-icon bg-danger bg-opacity-10 text-danger flex-shrink-0">🍽️</div>
    <div>
      <span class="badge bg-danger-subtle text-danger border border-danger-subtle mb-1">Programa 04</span>
      <h1 class="h3 fw-bold mb-0">Pedido de Restaurante</h1>
      <p class="text-muted small mb-0">
        Cookies y Sesiones &bull; Arrays asociativos &bull; POO &bull; Bootstrap 5.3.3
      </p>
    </div>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom fw-semibold d-flex align-items-center gap-2">
      <i class="bi bi-code-slash text-danger"></i>Implementación
    </div>
    <div class="card-body">
      <?php include 'T5_P4/php/programa.php'; ?>
    </div>
  </div>
</div>

<?php include 'php/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
