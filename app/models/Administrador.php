<?php
class Administrador {
    public $dni, $nombre, $telefono, $pass;

    public function __construct($datos = []) {
        $this->dni = $datos['dni_administrador'] ?? '';
        $this->nombre = $datos['nombre_apellido'] ?? '';
        $this->telefono = $datos['telefono'] ?? '';
        $this->pass = $datos['pass'] ?? '';
    }
}