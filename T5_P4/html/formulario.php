<!-- Formulario para ingresar un nuevo pedido de restaurante -->
<h4 class="mb-3 text-danger"><i class="bi bi-pencil-square me-2"></i>Nuevo Pedido</h4>

<form method="POST" action="" id="formPedido" class="p-4 border-0 rounded-4 shadow-sm bg-white form-pedido position-relative overflow-hidden flex-grow-1 d-flex flex-column justify-content-center" onsubmit="return validarPedido(event)">
    <div class="form-decorative-bg"></div>
    <div class="position-relative z-1">

        <!-- Nombre del cliente y edad -->
        <div class="row mb-3 g-3">
            <div class="col-md-7">
                <label for="cliente" class="form-label fw-semibold">Nombre del Cliente</label>
                <!-- El value se prellenara con la cookie si el cliente ya ordeno antes -->
                <input type="text" class="form-control bg-light border-0" id="cliente" name="cliente"
                       required minlength="3" maxlength="60"
                       pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                       title="Solo letras y espacios"
                       placeholder="Ej. Juan Pérez"
                       value="<?= htmlspecialchars($_COOKIE['cliente_nombre'] ?? '') ?>">
            </div>
            <div class="col-md-5">
                <label for="edad" class="form-label fw-semibold">Edad</label>
                <!-- Se bloquean caracteres invalidos con onkeydown y se limita el largo con oninput -->
                <input type="number" class="form-control bg-light border-0" id="edad" name="edad"
                       required min="10" max="120"
                       maxlength="3"
                       onkeydown="return !['e','E','+','-','.'].includes(event.key);"
                       oninput="if(this.value.length > 3) this.value = this.value.slice(0,3);"
                       placeholder="Ej. 30">
                <div class="form-text small text-muted"><i class="bi bi-info-circle me-1"></i>55+ años reciben 15% desc.</div>
            </div>
        </div>

        <!-- Plato principal y cantidad -->
        <div class="row mb-3 g-3">
            <div class="col-md-8">
                <label for="plato" class="form-label fw-semibold">Plato Principal</label>
                <select class="form-select bg-light border-0" id="plato" name="plato">
                    <option value="">Seleccione...</option>
                    <!-- Si hay cookie con el plato anterior, se marca como seleccionado -->
                    <option value="Hamburguesa" <?= (($_COOKIE['cliente_plato'] ?? '') === 'Hamburguesa') ? 'selected' : '' ?>>🍔 Hamburguesa — $8.00</option>
                    <option value="Pizza"       <?= (($_COOKIE['cliente_plato'] ?? '') === 'Pizza')       ? 'selected' : '' ?>>🍕 Pizza — $10.00</option>
                    <option value="Pasta"       <?= (($_COOKIE['cliente_plato'] ?? '') === 'Pasta')       ? 'selected' : '' ?>>🍝 Pasta — $12.00</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="cantidad" class="form-label fw-semibold">Cantidad</label>
                <input type="number" class="form-control bg-light border-0" id="cantidad" name="cantidad"
                       required min="1" max="10" value="1"
                       onkeydown="return !['e','E','+','-','.'].includes(event.key);"
                       oninput="if(this.value.length > 2) this.value = this.value.slice(0,2);">
            </div>
        </div>

        <!-- Bebida y cantidad -->
        <div class="row mb-3 g-3">
            <div class="col-md-8">
                <label for="bebida" class="form-label fw-semibold">Bebida</label>
                <select class="form-select bg-light border-0" id="bebida" name="bebida">
                    <option value="">Seleccione...</option>
                    <option value="Soda">🥤 Soda — $2.00</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="cantidad_bebida" class="form-label fw-semibold">Cantidad</label>
                <input type="number" class="form-control bg-light border-0" id="cantidad_bebida" name="cantidad_bebida"
                       min="1" max="10" value="1"
                       onkeydown="return !['e','E','+','-','.'].includes(event.key);"
                       oninput="if(this.value.length > 2) this.value = this.value.slice(0,2);">
            </div>
        </div>

        <!-- Postre y cantidad -->
        <div class="row mb-3 g-3">
            <div class="col-md-8">
                <label for="postre" class="form-label fw-semibold">Postre</label>
                <!-- El postre es opcional, por defecto queda en Ninguno -->
                <select class="form-select bg-light border-0" id="postre" name="postre">
                    <option value="Ninguno">Sin postre</option>
                    <option value="Postre">🍮 Postre — $3.00</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="cantidad_postre" class="form-label fw-semibold">Cantidad</label>
                <input type="number" class="form-control bg-light border-0" id="cantidad_postre" name="cantidad_postre"
                       min="1" max="10" value="1"
                       onkeydown="return !['e','E','+','-','.'].includes(event.key);"
                       oninput="if(this.value.length > 2) this.value = this.value.slice(0,2);">
            </div>
        </div>

        <!-- Tipo de pago mediante radio buttons -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Tipo de Pago</label>
            <div class="d-flex gap-4 bg-light p-3 rounded-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="tipo_pago" id="pago_efectivo" value="Efectivo" required>
                    <label class="form-check-label fw-medium" for="pago_efectivo"><i class="bi bi-cash me-1"></i>Efectivo</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="tipo_pago" id="pago_tarjeta" value="Tarjeta">
                    <label class="form-check-label fw-medium" for="pago_tarjeta"><i class="bi bi-credit-card me-1"></i>Tarjeta</label>
                </div>
            </div>
        </div>

        <!-- Campo opcional para notas adicionales del cliente -->
        <div class="mb-4">
            <label for="comentarios" class="form-label fw-semibold">Comentarios</label>
            <textarea class="form-control bg-light border-0" id="comentarios" name="comentarios"
                      rows="2" maxlength="300"
                      placeholder="Sin cebolla, extra queso, alergia a..."></textarea>
        </div>

        <!-- Error al no seleccionar ningún item -->
        <div id="errorItem" class="alert alert-warning d-none py-2 mb-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>Debe seleccionar al menos un plato, bebida o postre.
        </div>

        <!-- Botones -->
        <div class="d-flex flex-column flex-sm-row gap-2 justify-content-between mt-4">
            <button type="submit" name="pedir" class="btn btn-danger btn-lg px-4 flex-grow-1 shadow-sm fw-bold">
                <i class="bi bi-bag-check-fill me-2"></i>Realizar Pedido
            </button>
            <!-- Boton de basura: borra sesion y cookies al enviarse -->
            <button type="submit" name="borrar" class="btn btn-light btn-lg text-danger border-0 shadow-sm" formnovalidate title="Limpiar sesión y cookies">
                <i class="bi bi-trash3-fill"></i>
            </button>
        </div>

    </div>
</form>

<script>
function validarPedido(e) {
    // Si se presionó el botón borrar, dejar pasar sin validar
    if (document.activeElement && document.activeElement.name === 'borrar') return true;

    const plato  = document.getElementById('plato').value;
    const bebida = document.getElementById('bebida').value;
    const postre = document.getElementById('postre').value;
    const error  = document.getElementById('errorItem');

    // Al menos uno de los tres debe estar seleccionado
    if (!plato && !bebida && postre === 'Ninguno') {
        e.preventDefault();
        error.classList.remove('d-none');
        return false;
    }

    error.classList.add('d-none');
    return true;
}
</script>
