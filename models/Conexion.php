<?php
class Conexion{
    public static function conectar(){
            $conexion = new mysqli('localhost', 'root', '', 'minimarket');
            if($conexion->connect_error){
                die('Error de conexión: ' . $conexion->connect_error);
            }
            return $conexion;
        }
    }
?>
