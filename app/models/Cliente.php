<?php
class Cliente {
    /** @var string */
    public $dni;
    
    /** @var string */
    public $nombre;
    
    /** @var string */
    public $telefono;
    
    /** @var string|null */
    public $ruc;
    
    /** @var string|null */
    public $razon_social;
    
    /** @var string|null */
    public $direccion;

    public function __construct($datos = []) {
        $this->dni = $datos['dni_cliente'] ?? '';
        $this->nombre = $datos['nombre_apellido'] ?? '';
        $this->telefono = $datos['telefono'] ?? '';
        $this->ruc = $datos['ruc'] ?? null;
        $this->razon_social = $datos['razon_social'] ?? null;
        $this->direccion = $datos['direccion'] ?? null;
    }
}