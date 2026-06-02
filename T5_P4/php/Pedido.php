<?php

class Pedido {

    // Datos del pedido
    private $cliente;
    private $edad;
    private $plato;
    private $bebida;
    private $postre;
    private $cantidad;
    private $tipo_pago;
    private $comentarios;

    // Precios del menú
    private $precios = [
        'Hamburguesa' => 8,
        'Pizza'       => 10,
        'Pasta'       => 12,
        'Soda'        => 2,
        'Postre'      => 3,
    ];

    private $tasa_itbms           = 0.07;  // 7% ITBMS
    private $porcentaje_descuento = 0.15;  // 15% para mayores de 55
    private $edad_descuento       = 55;

    public function __construct($cliente, $edad, $plato, $bebida, $postre, $cantidad, $tipo_pago, $comentarios) {
        $this->cliente     = $cliente;
        $this->edad        = $edad;
        $this->plato       = $plato;
        $this->bebida      = $bebida;
        $this->postre      = $postre;
        $this->cantidad    = $cantidad;
        $this->tipo_pago   = $tipo_pago;
        $this->comentarios = $comentarios;
    }

    // Retorna el precio unitario del plato seleccionado
    public function getPrecioPlato() {
        return $this->precios[$this->plato] ?? 0;
    }

    // Suma plato × cantidad + bebida + postre (si aplica)
    public function calcularSubtotal() {
        $precioPlato  = $this->getPrecioPlato() * $this->cantidad;
        $precioBebida = $this->precios[$this->bebida] ?? 0;
        $precioPostre = ($this->postre !== 'Ninguno') ? ($this->precios['Postre'] ?? 0) : 0;
        return $precioPlato + $precioBebida + $precioPostre;
    }

    // Aplica 15% de descuento si el cliente tiene 55 años o más
    public function calcularDescuento() {
        if ($this->edad >= $this->edad_descuento) {
            return $this->calcularSubtotal() * $this->porcentaje_descuento;
        }
        return 0;
    }

    // Calcula el ITBMS sobre el monto ya descontado
    public function calcularITBMS() {
        return ($this->calcularSubtotal() - $this->calcularDescuento()) * $this->tasa_itbms;
    }

    public function calcularTotal() {
        return ($this->calcularSubtotal() - $this->calcularDescuento()) + $this->calcularITBMS();
    }

    // Devuelve un array asociativo con todos los datos para la factura
    public function getDetalleFactura() {
        return [
            'cliente'      => $this->cliente,
            'edad'         => $this->edad,
            'plato'        => $this->plato,
            'bebida'       => $this->bebida,
            'postre'       => $this->postre,
            'cantidad'     => $this->cantidad,
            'tipo_pago'    => $this->tipo_pago,
            'comentarios'  => $this->comentarios,
            'precio_plato' => $this->getPrecioPlato(),
            'subtotal'     => $this->calcularSubtotal(),
            'descuento'    => $this->calcularDescuento(),
            'itbms'        => $this->calcularITBMS(),
            'total'        => $this->calcularTotal(),
        ];
    }
}
?>
