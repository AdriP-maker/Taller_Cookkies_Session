<h4 class="mb-3 text-primary"><i class="bi bi-pencil-square me-2"></i>Nueva Reserva</h4>

<form method="POST" action="" class="p-4 border-0 rounded-4 shadow-sm bg-white form-reserva position-relative overflow-hidden flex-grow-1 d-flex flex-column justify-content-center">
    <div class="form-decorative-bg"></div>
    <div class="position-relative z-1">
        <div class="mb-3">
            <label for="cliente" class="form-label fw-semibold">Nombre del Cliente</label>
            <input type="text" class="form-control form-control-lg bg-light border-0" id="cliente" name="cliente" required minlength="3" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo se permiten letras y espacios" placeholder="Ej. Juan Pérez">
        </div>
        
        <div class="row mb-3 g-3">
            <div class="col-md-6">
                <label for="edad" class="form-label fw-semibold">Edad</label>
                <input type="number" class="form-control bg-light border-0" id="edad" name="edad" required min="4" max="120" onkeydown="return !['e', 'E', '+', '-', '.'].includes(event.key);" placeholder="Ej. 25">
                <div class="form-text small text-muted"><i class="bi bi-info-circle me-1"></i>60+ años reciben 15% desc.</div>
            </div>
            <div class="col-md-6">
                <label for="cantidad_boletos" class="form-label fw-semibold">Cantidad de Boletos</label>
                <input type="number" class="form-control bg-light border-0" id="cantidad_boletos" name="cantidad_boletos" required min="1" max="8" value="1" onkeydown="return !['e', 'E', '+', '-', '.'].includes(event.key);">
                <div class="form-text small text-muted"><i class="bi bi-info-circle me-1"></i>Máximo 8 por persona.</div>
            </div>
        </div>
        
        <div class="row mb-3 g-3">
            <div class="col-md-6">
                <label for="sala" class="form-label fw-semibold">Sala Seleccionada</label>
                <select class="form-select bg-light border-0" id="sala" name="sala" required>
                    <option value="">Seleccione...</option>
                    <option value="Sala 1 (2D)">Sala 1 (2D)</option>
                    <option value="Sala 2 (3D)">Sala 2 (3D)</option>
                    <option value="Sala 3 (VIP)">Sala 3 (VIP)</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="tipo_pelicula" class="form-label fw-semibold">Tipo de Película</label>
                <select class="form-select bg-light border-0" id="tipo_pelicula" name="tipo_pelicula" required>
                    <option value="">Seleccione...</option>
                    <option value="Acción">Acción</option>
                    <option value="Comedia">Comedia</option>
                    <option value="Drama">Drama</option>
                    <option value="Ciencia Ficción">Ciencia Ficción</option>
                    <option value="Terror">Terror</option>
                    <option value="Animación">Animación</option>
                </select>
            </div>
        </div>
        
        <div class="mb-4 form-check bg-light p-3 rounded-3 border-0 d-flex align-items-center gap-3">
            <input type="checkbox" class="form-check-input m-0 ms-1 fs-4" id="combo_comida" name="combo_comida" value="1">
            <label class="form-check-label fw-bold mb-0 text-dark" style="cursor:pointer;" for="combo_comida">
                🍿 Agregar Combo de Comida <span class="text-primary">($4.00)</span>
            </label>
        </div>
        
        <div class="d-flex flex-column flex-sm-row gap-2 justify-content-between mt-4">
            <button type="submit" name="reservar" class="btn btn-primary btn-lg px-4 flex-grow-1 shadow-sm fw-bold">
                <i class="bi bi-ticket-perforated-fill me-2"></i>Reservar Boletos
            </button>
            <button type="submit" name="borrar" class="btn btn-light btn-lg text-danger border-0 shadow-sm" formnovalidate title="Limpiar sesión y cookies">
                <i class="bi bi-trash3-fill"></i>
            </button>
        </div>
    </div>
</form>
