<?php
require_once __DIR__ . '/Usuario.php';

class Administrador extends Usuario {
    private $telefono;
    private $bd;

    public function __construct($nombre = '', $telefono = ''){
        parent::__construct($nombre);
        $this->telefono = $telefono;
        $this->bd = Conexion::conectar();
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

    //MÉTODOS DE CONSULTA¿

    //Obtiene un administrador por DNI
    public function obtener_por_dni($dni){
        $sql = "SELECT * FROM administradores WHERE dni_administrador = '$dni'";
        return $this->bd->query($sql)->fetch_assoc();
    }
}