<?php
require_once __DIR__ . '/Usuario.php';

class Empleado extends Usuario {
    private $telefono;
    private $bd;

    public function __construct($nombre = '', $telefono = ''){
        parent::__construct($nombre);
        $this->telefono = $telefono;
        //Conexión a la BD para los métodos de consulta
        $this->bd = Conexion::conectar();
    }

    public function ob_nombre(){
        return "Nombre-Empleado: " . $this->nombre . "<br>Telefono: " . $this->telefono;
    }

    public function ob_telefono(){
        return $this->telefono;
    }

    //MÉTODOS DE CONSULTA

    //Busca un empleado por DNI y contraseña
    public function buscar_por_credenciales($dni, $pass){
        $sql = "SELECT * FROM empleados WHERE dni_empleado = '$dni' AND pass = '$pass'";
        return $this->bd->query($sql)->fetch_assoc();
    }

    //Obtiene un empleado por DNI
    public function obtener_por_dni($dni){
        $sql = "SELECT * FROM empleados WHERE dni_empleado = '$dni'";
        return $this->bd->query($sql)->fetch_assoc();
    }
}