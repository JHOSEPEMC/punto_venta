<?php
class Producto {
    /** @var int|null */
    public $id;
    
    /** @var string */
    public $nombre;
    
    /** @var int|null */
    public $id_categoria;
    
    /** @var string */
    public $nombre_categoria;
    
    /** @var float */
    public $precio;
    
    /** @var int */
    public $stock;
    
    /** @var string */
    public $icono;

    public function __construct($datos = []) {
        $this->id = $datos['id_producto'] ?? null;
        $this->nombre = $datos['nombre_producto'] ?? '';
        $this->id_categoria = $datos['id_categoria'] ?? null;
        $this->nombre_categoria = $datos['nombre_catg'] ?? '';
        $this->precio = $datos['precio_unitario'] ?? 0;
        $this->stock = $datos['stock'] ?? 0;
        $this->icono = '📦';
    }
}