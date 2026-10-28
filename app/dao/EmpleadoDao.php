<?php
class EmpleadoDAO {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexion::conectar();
    }

    public function obtener_por_dni($dni) {
        $stmt = $this->pdo->prepare("SELECT * FROM empleados WHERE dni_empleado = ?");
        $stmt->execute([$dni]);
        return $stmt->fetch();
    }

    public function obtener_todos() {
        return $this->pdo->query("SELECT * FROM empleados ORDER BY nombre_apellido ASC")->fetchAll();
    }

    public function insertar($dni, $nombre, $telefono, $pass) {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO empleados (dni_empleado, nombre_apellido, telefono, pass) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$dni, $nombre, $telefono, $hash]);
    }

    public function actualizar($dni, $nombre, $telefono, $pass = null) {
        if ($pass) {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare("UPDATE empleados SET nombre_apellido=?, telefono=?, pass=? WHERE dni_empleado=?");
            return $stmt->execute([$nombre, $telefono, $hash, $dni]);
        } else {
            $stmt = $this->pdo->prepare("UPDATE empleados SET nombre_apellido=?, telefono=? WHERE dni_empleado=?");
            return $stmt->execute([$nombre, $telefono, $dni]);
        }
    }

    public function eliminar($dni) {
        $stmt = $this->pdo->prepare("DELETE FROM empleados WHERE dni_empleado = ?");
        return $stmt->execute([$dni]);
    }
}