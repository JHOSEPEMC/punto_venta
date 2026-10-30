<?php
class Empleado {
    public $dni, $nombre, $telefono, $pass, $num_caja;

    public function __construct($datos = []) {
        $this->dni = $datos['dni_empleado'] ?? '';
        $this->nombre = $datos['nombre_apellido'] ?? '';
        $this->telefono = $datos['telefono'] ?? '';
        $this->pass = $datos['pass'] ?? '';
        $this->num_caja = $datos['num_caja'] ?? '';
    }
}