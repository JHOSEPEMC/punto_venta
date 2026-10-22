<?php
class Cliente {
    private $bd;

    //conecta con la base de datos al instanciar
    public function __construct(){
        $this->bd = Conexion::conectar();
    }

    //inserta un nuevo cliente (con soporte para RUC/Negocio)
    public function insertar($dni, $nombre, $telefono, $ruc = null, $razon_social = null, $direccion = null){
        $sql = "INSERT INTO clientes (dni_cliente, nombre_apellido, telefono, ruc, razon_social, direccion) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("ssssss", $dni, $nombre, $telefono, $ruc, $razon_social, $direccion);
        return $stmt->execute();
    }

    //actualiza un cliente
    public function actualizar($dni, $nombre, $telefono, $ruc = null, $razon_social = null, $direccion = null){
        $sql = "UPDATE clientes 
                SET nombre_apellido = ?, telefono = ?, ruc = ?, razon_social = ?, direccion = ? 
                WHERE dni_cliente = ?";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("ssssss", $nombre, $telefono, $ruc, $razon_social, $direccion, $dni);
        return $stmt->execute();
    }

    //elimina un cliente usando sentencia preparada
    public function eliminar($dni){
        $sql = "DELETE FROM clientes WHERE dni_cliente = ?";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("s", $dni);
        return $stmt->execute();
    }

    //verifica si existe un cliente usando sentencia preparada
    public function existe($dni){
        $sql = "SELECT dni_cliente FROM clientes WHERE dni_cliente = ?";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("s", $dni);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() != null;
    }

    //devuelve un cliente por dni usando sentencia preparada
    public function obtener_por_dni($dni){
        $sql = "SELECT * FROM clientes WHERE dni_cliente = ?";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("s", $dni);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    //devuelve todos los clientes
    public function obtener_todos(){
        return $this->bd->query("SELECT * FROM clientes");
    }
}