<?php if (!empty($_COOKIE['nombre_estudiante'])): ?>
    <div class="alerta alerta-ok">
        Bienvenido de nuevo, <strong><?= htmlspecialchars($_COOKIE['nombre_estudiante']) ?></strong>.
        <?php if (!empty($_COOKIE['curso_favorito'])): ?>
            Tu curso favorito registrado: <strong><?= htmlspecialchars($_COOKIE['curso_favorito']) ?></strong>.
        <?php endif; ?>
    </div>
<?php endif; ?>
