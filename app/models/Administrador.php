<?php
require_once __DIR__ . '/Usuario.php';

class Administrador extends Usuario {
    private $telefono;
    private $bd;

    //constructor con nombre, teléfono opcionales y conexión a BD
    public function __construct($nombre = '', $telefono = ''){
        parent::__construct($nombre);
        $this->telefono = $telefono;
        $this->bd = Conexion::conectar();
    }

    //devuelve el nombre del administrador
    public function ob_nombre(){
        return $this->nombre;
    }

    //devuelve el teléfono del administrador
    public function ob_telefono(){
        return $this->telefono;
    }

    //devuelve la información completa formateada
    public function obtenerInfo(){
        return "Nombre-Administrador: " . $this->nombre . "<br>Telefono: " . $this->telefono;
    }

    //obtiene un administrador por dni usando sentencia preparada
    public function obtener_por_dni($dni){
        $sql = "SELECT * FROM administradores WHERE dni_administrador = ?";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("s", $dni);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}