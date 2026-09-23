<?php
require_once __DIR__ . '/Usuario.php';

class Administrador extends Usuario {
    private $telefono;

    //Corregido: antes era private
    public function __construct($nombre, $telefono) {
        parent::__construct($nombre);
        $this->telefono = $telefono;
    }

    public function ob_nombre(){
        return $this->nombre;
    }

    public function ob_telefono(){
        return $this->telefono;
    }

    public function obtenerInfo(){
        return "Nombre-Administrador: " . $this->nombre . "<br>Telefono: " . $this->telefono;
    }
}