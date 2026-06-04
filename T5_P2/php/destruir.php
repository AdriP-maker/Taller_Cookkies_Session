<?php
session_start();

/* ═══════════════════════════════════════════════════════
   DESTRUIR SESIÓN (lado servidor)
═══════════════════════════════════════════════════════ */

// 1. Vaciar el array de sesión
$_SESSION = [];

// 2. Expirar la cookie de sesión PHP (PHPSESSID)
if (ini_get("session.use_cookies")) {
  $params = session_get_cookie_params();
  setcookie(
    session_name(),
    '',
    time() - 42000,
    $params["path"],
    $params["domain"],
    $params["secure"],
    $params["httponly"]
  );
}

// 3. Destruir la sesión en el servidor
session_destroy();

/* ═══════════════════════════════════════════════════════
   DESTRUIR COOKIES DE FARMACIA (lado servidor)
   Nota: Las cookies del cliente también se eliminan
   en destruir.html mediante scripts.js (deleteCookie)
═══════════════════════════════════════════════════════ */
setcookie('nombre_cliente', '', time() - 3600, '/');
setcookie('tipo_entrega', '', time() - 3600, '/');
// Variables del sistema general
$pageTitle = 'P2 — Sesión Destruida | Taller 5';
$activePage = 'p2';
$rootPath = '../../';
$footerType = 'programa';

// Determinar el retorno dinámico basado en el referer antes de destruir sesión por completo
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
$p2_return_link = (strpos($referer, 'T5_P2.php') !== false) ? '../../T5_P2.php' : '../../T5_P2.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $pageTitle ?></title>
  <link
    href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="../../css/styles.css">
  <link rel="stylesheet" href="../css/p2.css">
</head>

<body class="p2-app">

  <?php include '../../php/nav.php'; ?>

  <!-- Contenido centrado -->
  <div class="container py-5 d-flex justify-content-center">
    <div class="card-destroy w-100" style="max-width: 520px;">

      <div class="icon-circle">
        <i class="bi bi-trash3-fill"></i>
      </div>

      <h2>Sesión Destruida</h2>
      <p class="text-muted mt-1">Los datos temporales han sido eliminados correctamente.</p>

      <div class="status-list">
        <!-- Ítems fijos del servidor -->
        <div class="status-item">
          <i class="bi bi-check-circle-fill status-icon-ok"></i>
          <span>Variables de sesión <strong>limpiadas</strong> (<code>$_SESSION = []</code>)</span>
        </div>
        <div class="status-item">
          <i class="bi bi-check-circle-fill status-icon-ok"></i>
          <span>Sesión <strong>destruida</strong> (<code>session_destroy()</code>)</span>
        </div>
        <div class="status-item">
          <i class="bi bi-check-circle-fill status-icon-ok"></i>
          <span>Cookie de sesión <strong>expirada</strong> (<code>PHPSESSID</code>)</span>
        </div>
        <div class="status-item">
          <i class="bi bi-check-circle-fill status-icon-ok"></i>
          <span>Cookie <strong>nombre_cliente</strong> expirada (servidor)</span>
        </div>
        <div class="status-item">
          <i class="bi bi-check-circle-fill status-icon-ok"></i>
          <span>Cookie <strong>tipo_entrega</strong> expirada (servidor)</span>
        </div>
        <!-- Cookies del lado cliente eliminadas por scripts.js -->
        <div id="cookies-eliminadas"></div>
      </div>

      <a href="<?= htmlspecialchars($p2_return_link) ?>"
        class="btn-volver text-white text-decoration-none px-4 py-2 d-inline-block" style="border-radius:12px;">
        <i class="bi bi-arrow-left me-2"></i>Volver al formulario
      </a>

    </div>
  </div>

  <?php include '../../php/footer.php'; ?>
  <script src="../js/scripts.js"></script>
</body>

</html>