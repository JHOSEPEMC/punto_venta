<?php
require_once('Usuario.php');
class Empleado extends Usuario{ //Herencia de usuario
    private $telefono;

    public function __construct($nombre, $telefono) {
        parent::__construct($nombre);
        $this->telefono = $telefono;
    }
    public function ob_nombre(){ //Polimorfismo Lol
        return "Nombre-Empleado: " . $this->nombre . "<br>Telefono: " . $this->telefono;
    }

    public function ob_telefono(){ //Metodo para tener el numero
        return $this->telefono;
    }
}
?>