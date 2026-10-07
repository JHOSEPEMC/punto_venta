<?php
require_once __DIR__ . '/Usuario.php';

class Empleado extends Usuario {
    private $telefono;
    private $bd;

    //constructor con nombre, teléfono opcionales y conexión a BD
    public function __construct($nombre = '', $telefono = ''){
        parent::__construct($nombre);
        $this->telefono = $telefono;
        $this->bd = Conexion::conectar();
    }

    //devuelve el nombre y teléfono formateado
    public function ob_nombre(){
        return "Nombre-Empleado: " . $this->nombre . "<br>Telefono: " . $this->telefono;
    }

    //devuelve el teléfono del empleado
    public function ob_telefono(){
        return $this->telefono;
    }

    //busca un empleado por dni y contraseña usando sentencia preparada
    public function buscar_por_credenciales($dni, $pass){
        $sql = "SELECT * FROM empleados WHERE dni_empleado = ? AND pass = ?";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("ss", $dni, $pass);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    //obtiene un empleado por dni usando sentencia preparada
    public function obtener_por_dni($dni){
        $sql = "SELECT * FROM empleados WHERE dni_empleado = ?";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("s", $dni);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}