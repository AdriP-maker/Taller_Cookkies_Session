<!-- Vista de Arrays — P4 Restaurante -->
<div class="mt-4">
    <h5 class="text-secondary fw-semibold mb-3"><i class="bi bi-code-slash me-2"></i>Datos Almacenados</h5>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold text-danger border-bottom-0 pt-3 pb-0">
                    <i class="bi bi-box-seam-fill me-2"></i>Array ($_SESSION)
                </div>
                <div class="card-body p-0">
                    <pre class="m-0 p-3 bg-light rounded-bottom small text-dark border-top border-light h-100" style="overflow-x:auto;"><code><?= htmlspecialchars(print_r($_SESSION, true)) ?></code></pre>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold text-warning border-bottom-0 pt-3 pb-0">
                    <i class="bi bi-cookie me-2"></i>Array ($_COOKIE)
                </div>
                <div class="card-body p-0">
                    <pre class="m-0 p-3 bg-light rounded-bottom small text-dark border-top border-light h-100" style="overflow-x:auto;"><code><?= htmlspecialchars(print_r($_COOKIE, true)) ?></code></pre>
                </div>
            </div>
        </div>
    </div>
</div>
