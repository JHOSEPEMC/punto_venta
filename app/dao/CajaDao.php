<?php
class CajaDAO {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexion::conectar();
    }

    public function obtener_activa($dni_empleado) {
        $stmt = $this->pdo->prepare("SELECT * FROM cajas WHERE dni_empleado = ? AND estado = 'abierta' ORDER BY id_caja DESC LIMIT 1");
        $stmt->execute([$dni_empleado]);
        return $stmt->fetch();
    }

    public function obtener_ultima_cerrada($dni_empleado) {
        $stmt = $this->pdo->prepare("SELECT * FROM cajas WHERE dni_empleado = ? AND estado = 'cerrada' ORDER BY id_caja DESC LIMIT 1");
        $stmt->execute([$dni_empleado]);
        return $stmt->fetch();
    }

    public function reabrir($id_caja) {
        $fecha = date('Y-m-d H:i:s');
        $stmt = $this->pdo->prepare("UPDATE cajas SET hora_apertura = ?, estado = 'abierta' WHERE id_caja = ?");
        return $stmt->execute([$fecha, $id_caja]);
    }

    public function cerrar($id_caja) {
        $fecha = date('Y-m-d H:i:s');
        $stmt = $this->pdo->prepare("UPDATE cajas SET hora_cierre = ?, estado = 'cerrada' WHERE id_caja = ?");
        return $stmt->execute([$fecha, $id_caja]);
    }

    public function calcular_ventas($id_caja) {
        $stmt = $this->pdo->prepare("SELECT SUM(total) as total FROM boletas WHERE id_caja = ?");
        $stmt->execute([$id_caja]);
        $resultado = $stmt->fetch();
        return $resultado['total'] ?? 0;
    }

    // 🚀 FALTABAN ESTOS 3 MÉTODOS:

    //trae solo las cajas ABIERTAS (para el panel de admin)
    public function obtener_todas_abiertas() {
        $sql = "SELECT c.*, e.nombre_apellido as empleado 
                FROM cajas c
                LEFT JOIN empleados e ON c.dni_empleado = e.dni_empleado
                WHERE c.estado = 'abierta'
                ORDER BY c.id_caja DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    //trae el HISTORIAL de cajas
    public function obtener_historial() {
        $sql = "SELECT c.*, e.nombre_apellido as empleado 
                FROM cajas c
                LEFT JOIN empleados e ON c.dni_empleado = e.dni_empleado
                ORDER BY c.id_caja DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    //asigna un sencillo a un empleado creando una caja cerrada
    public function asignarSencillo($dni_empleado, $sencillo) {
        // 1. Borramos cualquier caja cerrada vieja que no se haya usado
        $stmt = $this->pdo->prepare("DELETE FROM cajas WHERE dni_empleado = ? AND estado = 'cerrada'");
        $stmt->execute([$dni_empleado]);
        
        // 2. Creamos la nueva caja
        $fecha = date('Y-m-d H:i:s');
        $stmt = $this->pdo->prepare("INSERT INTO cajas (dni_empleado, sencillo_inicial, hora_apertura, estado) VALUES (?, ?, ?, 'cerrada')");
        return $stmt->execute([$dni_empleado, $sencillo, $fecha]);
    }
    
}