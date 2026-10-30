<?php
class Venta {
    public $id_venta, $id_boleta, $id_producto, $cantidad, $precio_unitario, $producto_nombre;

    public function __construct($datos = []) {
        $this->id_venta = $datos['id_venta'] ?? null;
        $this->id_boleta = $datos['id_boleta'] ?? null;
        $this->id_producto = $datos['id_producto'] ?? null;
        $this->cantidad = $datos['cantidad'] ?? 0;
        $this->precio_unitario = $datos['precio_unitario'] ?? 0;
        $this->producto_nombre = $datos['producto'] ?? null;
    }
}