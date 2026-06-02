<?php

class Reserva {
    private $cliente;
    private $edad;
    private $sala;
    private $tipo_pelicula;
    private $cantidad_boletos;
    private $tiene_combo;

    private $precio_boleto = 6;
    private $precio_combo = 4;
    private $tasa_itbms = 0.07;
    private $porcentaje_descuento = 0.15;

    public function __construct($cliente, $edad, $sala, $tipo_pelicula, $cantidad_boletos, $tiene_combo) {
        $this->cliente = $cliente;
        $this->edad = $edad;
        $this->sala = $sala;
        $this->tipo_pelicula = $tipo_pelicula;
        $this->cantidad_boletos = $cantidad_boletos;
        $this->tiene_combo = $tiene_combo;
    }

    public function calcularSubtotal() {
        $subtotal = $this->cantidad_boletos * $this->precio_boleto;
        if ($this->tiene_combo) {
            $subtotal += $this->precio_combo;
        }
        return $subtotal;
    }

    public function calcularDescuento() {
        if ($this->edad >= 60) {
            return $this->calcularSubtotal() * $this->porcentaje_descuento;
        }
        return 0;
    }

    public function calcularITBMS() {
        $subtotalConDescuento = $this->calcularSubtotal() - $this->calcularDescuento();
        return $subtotalConDescuento * $this->tasa_itbms;
    }

    public function calcularTotal() {
        $subtotalConDescuento = $this->calcularSubtotal() - $this->calcularDescuento();
        return $subtotalConDescuento + $this->calcularITBMS();
    }

    public function getDetalleFactura() {
        return [
            'cliente' => $this->cliente,
            'edad' => $this->edad,
            'sala' => $this->sala,
            'tipo_pelicula' => $this->tipo_pelicula,
            'cantidad_boletos' => $this->cantidad_boletos,
            'tiene_combo' => $this->tiene_combo ? 'Sí ($4.00)' : 'No',
            'subtotal' => $this->calcularSubtotal(),
            'descuento' => $this->calcularDescuento(),
            'itbms' => $this->calcularITBMS(),
            'total' => $this->calcularTotal()
        ];
    }
}
?>
