<?php

// Clase que representa un pedido de restaurante con sus calculos de precio
class Pedido {

    // Datos que llegan del formulario
    private $cliente;
    private $correo;
    private $edad;
    private $plato;
    private $bebida;
    private $postre;
    private $cantidad;
    private $cantidad_bebida;
    private $cantidad_postre;
    private $tipo_pago;
    private $comentarios;

    // Lista de precios de cada item del menu
    private $precios = [
        'Hamburguesa' => 8,
        'Pizza'       => 10,
        'Pasta'       => 12,
        'Soda'        => 2,
        'Postre'      => 3,
    ];

    private $tasa_itbms           = 0.07;  // 7% ITBMS
    private $porcentaje_descuento = 0.15;  // 15% para mayores de 55
    private $edad_descuento       = 55;    // Edad minima para el descuento

    // Constructor: recibe todos los datos del pedido y los guarda en la clase
    public function __construct($cliente, $correo, $edad, $plato, $bebida, $postre, $cantidad, $cantidad_bebida, $cantidad_postre, $tipo_pago, $comentarios) {
        $this->cliente         = $cliente;
        $this->correo          = $correo;
        $this->edad            = $edad;
        $this->plato           = $plato;
        $this->bebida          = $bebida;
        $this->postre          = $postre;
        $this->cantidad        = $cantidad;
        $this->cantidad_bebida = $cantidad_bebida;
        $this->cantidad_postre = $cantidad_postre;
        $this->tipo_pago       = $tipo_pago;
        $this->comentarios     = $comentarios;
    }

    // Retorna el precio unitario del plato seleccionado
    public function getPrecioPlato() {
        return $this->precios[$this->plato] ?? 0;
    }

    // Suma plato x cantidad + bebida x cantidad + postre x cantidad (si aplica)
    public function calcularSubtotal() {
        $precioPlato  = $this->getPrecioPlato() * $this->cantidad;
        $precioBebida = ($this->precios[$this->bebida] ?? 0) * $this->cantidad_bebida;
        $precioPostre = (($this->postre !== 'Ninguno') ? ($this->precios['Postre'] ?? 0) : 0) * $this->cantidad_postre;
        return $precioPlato + $precioBebida + $precioPostre;
    }

    // Aplica 15% de descuento si el cliente tiene 55 anos o mas
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

    // Suma subtotal - descuento + ITBMS para obtener el total final
    public function calcularTotal() {
        return ($this->calcularSubtotal() - $this->calcularDescuento()) + $this->calcularITBMS();
    }

    // Devuelve un array asociativo con todos los datos listos para mostrar en la factura
    public function getDetalleFactura() {
        return [
            'cliente'      => $this->cliente,
            'correo'       => $this->correo,
            'edad'         => $this->edad,
            'plato'        => $this->plato,
            'bebida'       => $this->bebida,
            'postre'       => $this->postre,
            'cantidad'         => $this->cantidad,
            'cantidad_bebida'  => $this->cantidad_bebida,
            'cantidad_postre'  => $this->cantidad_postre,
            'tipo_pago'        => $this->tipo_pago,
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
