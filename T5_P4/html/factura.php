<!-- Factura P4 — Restaurante -->
<div class="card shadow border-0 bg-white factura-card overflow-hidden flex-grow-1">
    <div class="card-header text-white p-3 border-0 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="bi bi-receipt me-2"></i>Factura del Pedido</h5>
        <span class="badge bg-success rounded-pill px-3 py-2 shadow-sm"><i class="bi bi-check-circle-fill me-1"></i>Confirmado</span>
    </div>

    <div class="card-body p-4">

        <!-- Datos del cliente -->
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
                <div class="text-muted small text-uppercase fw-bold mb-1">Plato</div>
                <div class="fs-5 text-dark"><?= htmlspecialchars($factura['plato']) ?></div>
            </div>
            <div class="col-6">
                <div class="text-muted small text-uppercase fw-bold mb-1">Tipo de Pago</div>
                <div class="fs-5 text-dark"><?= htmlspecialchars($factura['tipo_pago']) ?></div>
            </div>
        </div>

        <!-- Desglose con foreach -->
        <div class="p-3 bg-light rounded-3 mb-4 border border-light-subtle">
            <?php
            $precioBebida = ($factura['bebida'] !== 'Ninguno') ? 2 : 0;
            $precioPostre = ($factura['postre'] !== 'Ninguno') ? 3 : 0;

            $desglose = [
                "{$factura['plato']} ({$factura['cantidad']} x $" . number_format($factura['precio_plato'], 2) . ")"
                    => $factura['precio_plato'] * $factura['cantidad'],
                "Bebida — {$factura['bebida']}"
                    => $precioBebida,
                "Postre — {$factura['postre']}"
                    => $precioPostre,
            ];

            foreach ($desglose as $concepto => $monto):
                if ($monto == 0 && str_contains($concepto, 'Ninguno')) continue;
            ?>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-secondary fw-medium"><?= htmlspecialchars($concepto) ?></span>
                <span class="fw-semibold">$<?= number_format($monto, 2) ?></span>
            </div>
            <?php endforeach; ?>

            <!-- Subtotal -->
            <div class="d-flex justify-content-between fw-bold mb-2 pt-2 border-top border-secondary-subtle">
                <span class="text-dark">Subtotal</span>
                <span class="text-dark">$<?= number_format($factura['subtotal'], 2) ?></span>
            </div>

            <!-- Descuento (solo si aplica) -->
            <?php if ($factura['descuento'] > 0): ?>
            <div class="d-flex justify-content-between text-success mb-2">
                <span class="fw-medium"><i class="bi bi-tag-fill me-1"></i>Desc. (15% — 55+ años)</span>
                <span class="fw-bold">-$<?= number_format($factura['descuento'], 2) ?></span>
            </div>
            <?php endif; ?>

            <!-- ITBMS -->
            <div class="d-flex justify-content-between text-muted mb-1">
                <span>ITBMS (7%)</span>
                <span>+$<?= number_format($factura['itbms'], 2) ?></span>
            </div>
        </div>

        <!-- Total -->
        <div class="d-flex justify-content-between align-items-end bg-danger bg-opacity-10 p-3 rounded-3 border border-danger-subtle">
            <h5 class="mb-0 text-danger fw-bold text-uppercase">Total a Pagar</h5>
            <h3 class="mb-0 text-danger fw-black">$<?= number_format($factura['total'], 2) ?></h3>
        </div>

        <!-- Comentarios -->
        <?php if (!empty($factura['comentarios'])): ?>
        <div class="mt-3 p-3 bg-light rounded-3 border border-light-subtle">
            <div class="text-muted small text-uppercase fw-bold mb-1"><i class="bi bi-chat-left-text me-1"></i>Comentarios</div>
            <p class="mb-0 small text-dark"><?= htmlspecialchars($factura['comentarios']) ?></p>
        </div>
        <?php endif; ?>

    </div>
</div>
