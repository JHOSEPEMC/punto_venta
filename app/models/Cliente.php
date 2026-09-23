<?php
class Cliente {
    private $bd;

    public function __construct(){
        $this->bd = Conexion::conectar();
    }

    //Inserta un nuevo cliente
    public function insertar($dni, $nombre, $telefono){
        $sql = "INSERT INTO clientes (dni_cliente, nombre_apellido, telefono)
                VALUES ('$dni', '$nombre', '$telefono')";
        return $this->bd->query($sql);
    }

    //Actualiza un cliente (el DNI no se cambia, es PK)
    public function actualizar($dni, $nombre, $telefono){
        $sql = "UPDATE clientes
                SET nombre_apellido = '$nombre',
                    telefono        = '$telefono'
                WHERE dni_cliente = '$dni'";
        return $this->bd->query($sql);
    }

    //Elimina un cliente
    public function eliminar($dni){
        $sql = "DELETE FROM clientes WHERE dni_cliente = '$dni'";
        return $this->bd->query($sql);
    }

    //Verifica si ya existe un cliente con ese DNI
    public function existe($dni){
        $sql = "SELECT dni_cliente FROM clientes WHERE dni_cliente = '$dni'";
        return $this->bd->query($sql)->fetch_assoc() != null;
    }

    //Devuelve un cliente por su DNI
    public function obtener_por_dni($dni){
        $sql = "SELECT * FROM clientes WHERE dni_cliente = '$dni'";
        return $this->bd->query($sql)->fetch_assoc();
    }

    //Devuelve todos los clientes
    public function obtener_todos(){
        return $this->bd->query("SELECT * FROM clientes");
    }
}