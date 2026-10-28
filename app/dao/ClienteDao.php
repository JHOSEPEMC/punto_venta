<?php
class ClienteDAO {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexion::conectar();
    }

    public function obtener_todos() {
        return $this->pdo->query("SELECT * FROM clientes")->fetchAll();
    }

    public function obtener_por_dni($dni) {
        $stmt = $this->pdo->prepare("SELECT * FROM clientes WHERE dni_cliente = ?");
        $stmt->execute([$dni]);
        return $stmt->fetch();
    }

    public function insertar($dni, $nombre, $telefono, $ruc = null, $razon_social = null, $direccion = null) {
        $stmt = $this->pdo->prepare("INSERT INTO clientes (dni_cliente, nombre_apellido, telefono, ruc, razon_social, direccion) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$dni, $nombre, $telefono, $ruc, $razon_social, $direccion]);
    }

    // 🚀 FALTABAN ESTOS:

    public function actualizar($dni, $nombre, $telefono, $ruc = null, $razon_social = null, $direccion = null) {
        $stmt = $this->pdo->prepare("UPDATE clientes SET nombre_apellido = ?, telefono = ?, ruc = ?, razon_social = ?, direccion = ? WHERE dni_cliente = ?");
        return $stmt->execute([$nombre, $telefono, $ruc, $razon_social, $direccion, $dni]);
    }

    public function eliminar($dni) {
        $stmt = $this->pdo->prepare("DELETE FROM clientes WHERE dni_cliente = ?");
        return $stmt->execute([$dni]);
    }
}