<?php

/**
 * destruir.php
 * Elimina las cookies del estudiante y destruye la sesión actual.
 */
session_start();

/* ══════════════════════════════════════════
   1. DESTRUIR LA SESIÓN
   ══════════════════════════════════════════ */
$_SESSION = [];

if (ini_get('session.use_cookies')) {
   $params = session_get_cookie_params();
   setcookie(
      session_name(),
      '',
      time() - 42000,
      $params['path'],
      $params['domain'],
      $params['secure'],
      $params['httponly']
   );
}

session_destroy();

/* ══════════════════════════════════════════
   2. ELIMINAR COOKIES DEL ESTUDIANTE
   ══════════════════════════════════════════ */
setcookie('nombre_estudiante', '', time() - 3600, '/');
setcookie('correo_estudiante', '', time() - 3600, '/');
setcookie('telefono_estudiante', '', time() - 3600, '/');
setcookie('curso_favorito', '', time() - 3600, '/');

$pageTitle  = 'P3 — Sesión Destruida | Taller 5';
$activePage = 'p3';
$rootPath   = '../../';
$footerType = 'programa';

$referer = $_SERVER['HTTP_REFERER'] ?? '';
$p3_return_link = (strpos($referer, 'T5_P3.php') !== false)
   ? '../../T5_P3.php'
   : '../../T5_P3.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?= htmlspecialchars($pageTitle) ?></title>
   <link rel="stylesheet" href="../../css/styles.css">
</head>

<body>

   <?php include __DIR__ . '/../../php/nav.php'; ?>

   <div class="container py-5">
      <div class="card border-0 shadow-sm mx-auto" style="max-width: 520px;">
         <div class="card-body p-4 text-center">
            <div class="text-warning mb-3"><i class="bi bi-trash3-fill fs-1"></i></div>
            <h2 class="h4 fw-bold mb-2">Sesión y cookies eliminadas</h2>
            <p class="text-muted small mb-4">Los datos temporales del Programa 3 fueron borrados correctamente.</p>

            <ul class="list-unstyled text-start small mb-4">
               <li class="mb-2">Variables de sesión limpiadas (<code>$_SESSION</code>)</li>
               <li class="mb-2">Sesión destruida (<code>session_destroy()</code>)</li>
               <li class="mb-2">Cookie de sesión expirada (<code>PHPSESSID</code>)</li>
               <li class="mb-2">Cookie <strong>nombre_estudiante</strong> expirada</li>
               <li class="mb-2">Cookie <strong>correo_estudiante</strong> expirada</li>
               <li class="mb-2">Cookie <strong>telefono_estudiante</strong> expirada</li>
            </ul>

            <a href="<?= htmlspecialchars($p3_return_link) ?>" class="btn btn-warning w-100">
               ← Volver al formulario
            </a>
         </div>
      </div>
   </div>

   <?php include __DIR__ . '/../../php/footer.php'; ?>

</body>

</html>