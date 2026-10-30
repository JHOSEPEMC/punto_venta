<?php
class Boleta {
    public $id, $id_caja, $numero_boleta, $token, $dni_cliente, $dni_empleado, $total, $fecha_emision;
    public $cliente_nombre, $empleado_nombre;

    public function __construct($datos = []) {
        $this->id = $datos['id_boleta'] ?? null;
        $this->id_caja = $datos['id_caja'] ?? null;
        $this->numero_boleta = $datos['numero_boleta'] ?? '';
        $this->token = $datos['token'] ?? '';
        $this->dni_cliente = $datos['dni_cliente'] ?? '';
        $this->dni_empleado = $datos['dni_empleado'] ?? '';
        $this->total = $datos['total'] ?? 0;
        $this->fecha_emision = $datos['fecha_emision'] ?? null;
        $this->cliente_nombre = $datos['cliente'] ?? null;
        $this->empleado_nombre = $datos['empleado'] ?? null;
    }
}