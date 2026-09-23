<?php
class Conexion {
    //Conexión a la base de datos
    public static function conectar(){
        $conexion = new mysqli('localhost', 'root', '', 'minimarket');
        if($conexion->connect_error){
            die('Error de conexión: ' . $conexion->connect_error);
        }
        return $conexion;
    }
}