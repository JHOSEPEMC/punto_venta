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

        //método para insertar un nuevo empleado desde el admin
    public function insertar($dni, $nombre, $telefono, $pass){
        //hasheamos la contraseña por seguridad
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO empleados (dni_empleado, nombre_apellido, telefono, pass) VALUES (?, ?, ?, ?)";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("ssss", $dni, $nombre, $telefono, $hash);
        return $stmt->execute();
    }

    //método para actualizar un empleado
    public function actualizar($dni, $nombre, $telefono, $pass = null){
        if ($pass) {
            //si se envía contraseña, se hashea
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $sql = "UPDATE empleados SET nombre_apellido=?, telefono=?, pass=? WHERE dni_empleado=?";
            $stmt = $this->bd->prepare($sql);
            $stmt->bind_param("ssss", $nombre, $telefono, $hash, $dni);
        } else {
            //si no, solo se actualizan los datos personales
            $sql = "UPDATE empleados SET nombre_apellido=?, telefono=? WHERE dni_empleado=?";
            $stmt = $this->bd->prepare($sql);
            $stmt->bind_param("sss", $nombre, $telefono, $dni);
        }
        return $stmt->execute();
    }

    //método para eliminar un empleado
    public function eliminar($dni){
        $sql = "DELETE FROM empleados WHERE dni_empleado = ?";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("s", $dni);
        return $stmt->execute();
    }

    //método para obtener todos los empleados
    public function obtener_todos(){
        return $this->bd->query("SELECT * FROM empleados ORDER BY nombre_apellido ASC");
    }
}