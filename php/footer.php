<?php
$footerType   = $footerType   ?? 'index';
// Para páginas de programa: agrega nombres aquí o déjalos vacíos
$groupMembers = $groupMembers ?? [];
$rootPath     = $rootPath     ?? '';
?>
<footer class="bg-dark text-light py-4 mt-5">
  <div class="container text-center">
    <p class="mb-1">
      <strong>Universidad Tecnológica de Panamá</strong> &mdash; Centro Regional de Coclé
    </p>
    <p class="mb-1 text-secondary small">Facultad de Ingeniería en Sistemas</p>
    <p class="mb-1 small">
      Desarrollo de Software VII &copy; <?= date('Y') ?> &mdash;
      Facilitadora del Curso: <strong>Ing. María Y. Tejedor M. de Fernández</strong>
    </p>
    <?php if ($footerType === 'programa'): ?>
      <p class="mb-0 small">
        Integrantes del grupo:
        <?php if (!empty($groupMembers)): ?>
          <em><?= implode(' &bull; ', array_map('htmlspecialchars', $groupMembers)) ?></em>
        <?php else: ?>
          <em class="text-secondary">___________________________ &bull; ___________________________ &bull; ___________________________ &bull; ___________________________</em>
        <?php endif; ?>
      </p>
    <?php else: ?>
      <p class="mb-0 small">
        Asignación desarrollada por: <em>Estudiantes de Desarrollo de Software VII</em>
      </p>
    <?php endif; ?>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc4s9bIOgUxi8T/jzmE6+3hW0CAGcQJFiSyHSN3KQBU" crossorigin="anonymous"></script>
<script src="<?= htmlspecialchars($rootPath) ?>js/main.js"></script>
