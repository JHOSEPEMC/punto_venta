<?php
class ClienteDAO {
    private $pdo;

    public function __construct() { $this->pdo = Conexion::conectar(); }

    public function obtener_todos() {
        $res = $this->pdo->query("SELECT * FROM clientes")->fetchAll();
        return array_map(fn($f) => new Cliente($f), $res);
    }

    // 🚀 El ": ?Cliente" le dice al editor que puede devolver un Cliente o null
    public function obtener_por_dni($dni): ?Cliente {
        $stmt = $this->pdo->prepare("SELECT * FROM clientes WHERE dni_cliente = ?");
        $stmt->execute([$dni]);
        $fila = $stmt->fetch();
        
        return $fila ? new Cliente($fila) : null;
    }

    public function insertar($dni, $nombre, $telefono, $ruc = null, $razon = null, $direccion = null) {
        $stmt = $this->pdo->prepare("INSERT INTO clientes (dni_cliente, nombre_apellido, telefono, ruc, razon_social, direccion) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$dni, $nombre, $telefono, $ruc, $razon, $direccion]);
    }

    public function actualizar($dni, $nombre, $telefono, $ruc = null, $razon = null, $direccion = null) {
        $stmt = $this->pdo->prepare("UPDATE clientes SET nombre_apellido=?, telefono=?, ruc=?, razon_social=?, direccion=? WHERE dni_cliente=?");
        return $stmt->execute([$nombre, $telefono, $ruc, $razon, $direccion, $dni]);
    }

    public function eliminar($dni) {
        $stmt = $this->pdo->prepare("DELETE FROM clientes WHERE dni_cliente = ?");
        return $stmt->execute([$dni]);
    }
}