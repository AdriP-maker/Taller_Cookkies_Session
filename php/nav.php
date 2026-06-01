<?php
$pageTitle   = $pageTitle   ?? 'Taller 5';
$activePage  = $activePage  ?? 'index';
$rootPath    = $rootPath    ?? '';
$studentName = 'Adrian Pérez';
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<nav class="navbar navbar-expand-lg bg-white shadow-sm border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand" href="<?= htmlspecialchars($rootPath) ?>index.php">
      <span class="badge bg-primary px-2 py-1 fs-6">T5</span>
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Menú">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarMain">
      <ul class="navbar-nav mx-auto gap-1">
        <li class="nav-item">
          <a class="nav-link rounded <?= $activePage==='index' ? 'active fw-semibold' : '' ?>" href="<?= htmlspecialchars($rootPath) ?>index.php">
            <i class="bi bi-house me-1"></i>Inicio
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link rounded <?= $activePage==='p1' ? 'active fw-semibold' : '' ?>" href="<?= htmlspecialchars($rootPath) ?>T5_P1.php">
            <i class="bi bi-camera-reels me-1"></i>Programa 1
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link rounded <?= $activePage==='p2' ? 'active fw-semibold' : '' ?>" href="<?= htmlspecialchars($rootPath) ?>T5_P2.php">
            <i class="bi bi-capsule me-1"></i>Programa 2
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link rounded <?= $activePage==='p3' ? 'active fw-semibold' : '' ?>" href="<?= htmlspecialchars($rootPath) ?>T5_P3.php">
            <i class="bi bi-mortarboard me-1"></i>Programa 3
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link rounded <?= $activePage==='p4' ? 'active fw-semibold' : '' ?>" href="<?= htmlspecialchars($rootPath) ?>T5_P4.php">
            <i class="bi bi-egg-fried me-1"></i>Programa 4
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
