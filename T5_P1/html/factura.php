<!-- Factura -->
<div class="card shadow border-0 bg-white factura-card overflow-hidden flex-grow-1">
    <div class="card-header bg-dark text-white p-3 border-0 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="bi bi-receipt me-2"></i>Factura de Reserva</h5>
        <span class="badge bg-success rounded-pill px-3 py-2 shadow-sm"><i class="bi bi-check-circle-fill me-1"></i>Pagado</span>
    </div>
    <div class="card-body p-4 position-relative">
        
        <div class="row mb-4 g-3">
            <div class="col-6">
                <div class="text-muted small text-uppercase fw-bold mb-1">Cliente</div>
                <div class="fs-5 fw-semibold text-dark"><?= htmlspecialchars($factura['cliente']) ?></div>
            </div>
            <div class="col-6">
                <div class="text-muted small text-uppercase fw-bold mb-1">Edad</div>
                <div class="fs-5 text-dark"><?= $factura['edad'] ?> años</div>
            </div>
            <div class="col-6">
                <div class="text-muted small text-uppercase fw-bold mb-1">Sala</div>
                <div class="fs-5 text-dark"><?= htmlspecialchars($factura['sala']) ?></div>
            </div>
            <div class="col-6">
                <div class="text-muted small text-uppercase fw-bold mb-1">Película</div>
                <div class="fs-5 text-dark"><?= htmlspecialchars($factura['tipo_pelicula']) ?></div>
            </div>
        </div>
        
        <div class="p-3 bg-light rounded-3 mb-4 border border-light-subtle">
            <?php
            // Aplicamos un foreach 
            $desglose = [
                "Boletos ({$factura['cantidad_boletos']} x $6.00)" => $factura['cantidad_boletos'] * 6,
                "Combo de Comida" => $factura['tiene_combo'] === 'No' ? 0 : 4
            ];
            
            foreach ($desglose as $concepto => $monto): 
            ?>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-secondary fw-medium"><?= $concepto ?></span>
                <span class="fw-semibold">$<?= number_format($monto, 2) ?></span>
            </div>
            <?php endforeach; ?>

            <div class="d-flex justify-content-between fw-bold mb-2 pt-2 border-top border-secondary-subtle">
                <span class="text-dark">Subtotal</span>
                <span class="text-dark">$<?= number_format($factura['subtotal'], 2) ?></span>
            </div>
            
            <?php if ($factura['descuento'] > 0): ?>
            <div class="d-flex justify-content-between text-success mb-2">
                <span class="fw-medium"><i class="bi bi-tag-fill me-1"></i>Desc. (15% 3ra edad)</span>
                <span class="fw-bold">-$<?= number_format($factura['descuento'], 2) ?></span>
            </div>
            <?php endif; ?>
            
            <div class="d-flex justify-content-between text-muted mb-1">
                <span>ITBMS (7%)</span>
                <span>+$<?= number_format($factura['itbms'], 2) ?></span>
            </div>
        </div>
        
        <div class="d-flex justify-content-between align-items-end mb-0 bg-primary bg-opacity-10 p-3 rounded-3 border border-primary-subtle">
            <h5 class="mb-0 text-primary fw-bold text-uppercase">Total a Pagar</h5>
            <h3 class="mb-0 text-primary fw-black">$<?= number_format($factura['total'], 2) ?></h3>
        </div>
    </div>
</div>
