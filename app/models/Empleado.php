<?php
require_once __DIR__ . '/Usuario.php';

class Empleado extends Usuario { // Herencia
    private $telefono;

    public function __construct($nombre, $telefono) {
        parent::__construct($nombre);
        $this->telefono = $telefono;
    }

    // Polimorfismo
    public function ob_nombre(){
        return "Nombre-Empleado: " . $this->nombre . "<br>Telefono: " . $this->telefono;
    }

    public function ob_telefono(){
        return $this->telefono;
    }
}