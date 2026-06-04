<?php
// =============================================
//  Programa 3 — Autenticación
// =============================================
?>
<h2 class="p3-title">Formulario de Inscripción <span class="sub">Completa todos los campos</span></h2>

<?php
require __DIR__ . '/regreso.php';
/**
 * Formulario de inscripción (incluido desde T5_P3.php).
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['p3_entry'] = $_SERVER['REQUEST_URI'];
require_once __DIR__ . '/cursos.php';

$p3FormAction = 'T5_P3/php/procesar.php';
$p3_errores = $_SESSION['p3_errores'] ?? [];
unset($_SESSION['p3_errores']);
$p3_redirect_to = $_SERVER['REQUEST_URI'] ?? 'T5_P3.php';
?>

<?php if (!empty($p3_errores)): ?>
    <div class="alert alert-danger mb-4" role="alert">
        <?php if (count($p3_errores) === 1): ?>
            <div class="fw-bold mb-0"><?= htmlspecialchars($p3_errores[0]) ?></div>
        <?php else: ?>
            <div class="fw-bold mb-2">Corrija estos campos:</div>
            <ul class="mb-0 ps-3">
                <?php foreach ($p3_errores as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
<?php endif; ?>

<form method="POST" action="<?= htmlspecialchars($p3FormAction) ?>" novalidate>
    <input type="hidden" name="redirect_to" value="<?= htmlspecialchars($p3_redirect_to) ?>">

    <div class="p3-card">
        <h3>Datos del Estudiante</h3>
        <div class="p3-row">
            <div class="field">
                <label for="nombre">Nombre completo</label>
                <input type="text" id="nombre" name="nombre" pattern="^[A-Za-záéíóúÁÉÍÓÚñÑ]+(\s[A-Za-záéíóúÁÉÍÓÚñÑ]+)+$"
                    title="Por favor, ingresa tu Nombre y Apellido (solo letras y un espacio entre ellos)."
                    placeholder="Ej. María López"
                    value="<?php echo isset($_COOKIE['nombre_estudiante']) ? htmlspecialchars($_COOKIE['nombre_estudiante']) : ''; ?>"
                    required>
            </div>
            <div class="field">
                <label for="correo">Correo electrónico</label>
                <input type="email" id="correo" name="correo" placeholder="correo@ejemplo.com"
                    value="<?php echo isset($_COOKIE['correo_estudiante']) ? htmlspecialchars($_COOKIE['correo_estudiante']) : ''; ?>"
                    required>
            </div>
        </div>
        <div class="p3-row">
            <div class="field" style="max-width:260px">
                <label for="telefono">Teléfono</label>
                <input type="tel" id="telefono" name="telefono" pattern="^[0-9]{4}-[0-9]{4}$"
                    title="El formato debe ser de 8 números con un guion. Ejemplo: 6000-0000" placeholder="6000-0000"
                    value="<?php echo isset($_COOKIE['telefono_estudiante']) ? htmlspecialchars($_COOKIE['telefono_estudiante']) : ''; ?>"
                    required>
            </div>
        </div>
    </div>

    <div class="p3-card">
        <h3>Configuración del Curso</h3>
        <div class="p3-row">
            <div class="field">
                <label for="curso">Curso</label>
                <select id="curso" name="curso" required>
                    <option value=""> Seleccionar </option>
                    <?php foreach ($cursos as $nombre => $precio): ?>
                        <option value="<?= $nombre ?>">
                            <?= $nombre ?> $
                            <?= number_format($precio, 2) ?>/módulo
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field">
                <label for="modulos">Cantidad de módulos</label>
                <input type="number" id="modulos" name="modulos" min="1" max="12" placeholder="Ej. 4" required>
            </div>
        </div>
        <div class="p3-row">
            <div class="field">
                <label>Turno</label>
                <div class="radio-group">
                    <input type="radio" name="turno" id="diurno" value="Diurno">
                    <label for="diurno">Diurno</label>
                    <input type="radio" name="turno" id="nocturno" value="Nocturno">
                    <label for="nocturno"> Nocturno</label>
                </div>
            </div>
            <div class="field">
                <label>Modalidad</label>
                <div class="radio-group">
                    <input type="radio" name="modalidad" id="virtual" value="Virtual">
                    <label for="virtual">Virtual</label>
                    <input type="radio" name="modalidad" id="presencial" value="Presencial">
                    <label for="presencial">Presencial</label>
                </div>
            </div>
        </div>

        <table class="p3-table">
            <thead>
                <tr>
                    <th>Curso</th>
                    <th>Precio / módulo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cursos as $nombre => $precio): ?>
                    <tr>
                        <td>
                            <?= $nombre ?>
                        </td>
                        <td class="precio">$
                            <?= number_format($precio, 2) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p class="note">
            <strong>Descuento 20%</strong> si tomas más de 3 módulos &nbsp;·&nbsp;
            <strong>ITBMS 7%</strong> aplica sobre toda inscripción
        </p>
    </div>

    <div class="btn-row">
        <button type="submit" class="p3-btn p3-btn-primary">Inscribirse</button>
        <button type="reset" class="p3-btn p3-btn-danger">Limpiar</button>
    </div>

</form>