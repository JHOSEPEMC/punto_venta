<?php
class CajaDAO {
    private $pdo;
    public function __construct() { $this->pdo = Conexion::conectar(); }

    public function obtener_activa($dni) {
        $stmt = $this->pdo->prepare("SELECT * FROM cajas WHERE dni_empleado = ? AND estado = 'abierta' ORDER BY id_caja DESC LIMIT 1");
        $stmt->execute([$dni]);
        $f = $stmt->fetch();
        return $f ? new Caja($f) : null;
    }

    public function obtener_ultima_cerrada($dni) {
        $stmt = $this->pdo->prepare("SELECT * FROM cajas WHERE dni_empleado = ? AND estado = 'cerrada' ORDER BY id_caja DESC LIMIT 1");
        $stmt->execute([$dni]);
        $f = $stmt->fetch();
        return $f ? new Caja($f) : null;
    }

    public function reabrir($id_caja) {
        $stmt = $this->pdo->prepare("UPDATE cajas SET hora_apertura = ?, estado = 'abierta' WHERE id_caja = ?");
        return $stmt->execute([date('Y-m-d H:i:s'), $id_caja]);
    }

    public function cerrar($id_caja) {
        $stmt = $this->pdo->prepare("UPDATE cajas SET hora_cierre = ?, estado = 'cerrada' WHERE id_caja = ?");
        return $stmt->execute([date('Y-m-d H:i:s'), $id_caja]);
    }

    public function calcular_ventas($id_caja) {
        $stmt = $this->pdo->prepare("SELECT SUM(total) as total FROM boletas WHERE id_caja = ?");
        $stmt->execute([$id_caja]);
        return $stmt->fetch()['total'] ?? 0;
    }

    public function obtener_todas_abiertas() {
        $sql = "SELECT c.*, e.nombre_apellido as empleado FROM cajas c LEFT JOIN empleados e ON c.dni_empleado = e.dni_empleado WHERE c.estado = 'abierta' ORDER BY c.id_caja DESC";
        $res = $this->pdo->query($sql)->fetchAll();
        return array_map(fn($f) => new Caja($f), $res);
    }

    public function obtener_historial() {
        $sql = "SELECT c.*, e.nombre_apellido as empleado FROM cajas c LEFT JOIN empleados e ON c.dni_empleado = e.dni_empleado ORDER BY c.id_caja DESC";
        $res = $this->pdo->query($sql)->fetchAll();
        return array_map(fn($f) => new Caja($f), $res);
    }

    public function asignarSencillo($dni_empleado, $sencillo) {
        $this->pdo->prepare("DELETE FROM cajas WHERE dni_empleado = ? AND estado = 'cerrada'")->execute([$dni_empleado]);
        $stmt = $this->pdo->prepare("INSERT INTO cajas (dni_empleado, sencillo_inicial, hora_apertura, estado) VALUES (?, ?, ?, 'cerrada')");
        return $stmt->execute([$dni_empleado, $sencillo, date('Y-m-d H:i:s')]);
    }
}