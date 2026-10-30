<?php
class BoletaDAO {
    private $pdo, $qb;

    public function __construct() {
        $this->pdo = Conexion::conectar();
        $this->qb = new QueryBuilder();
    }

    public function insertar($dni_cliente, $dni_empleado, $total, $fecha, $id_caja = null) {
        $token = bin2hex(random_bytes(16));
        $stmt = $this->pdo->prepare("INSERT INTO boletas (numero_boleta, token, id_caja, dni_cliente, dni_empleado, total, fecha_emision) VALUES ('', ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$token, $id_caja, $dni_cliente, $dni_empleado, $total, $fecha]);
        $id_boleta = $this->pdo->lastInsertId();
        $numero = 'BOL-' . str_pad($id_boleta, 6, '0', STR_PAD_LEFT);
        $this->pdo->prepare("UPDATE boletas SET numero_boleta = ? WHERE id_boleta = ?")->execute([$numero, $id_boleta]);
        return $id_boleta;
    }

    public function obtener_por_id($id_boleta) {
        $stmt = $this->pdo->prepare("SELECT b.*, c.nombre_apellido as cliente, e.nombre_apellido as empleado FROM boletas b 
                                     LEFT JOIN clientes c ON b.dni_cliente = c.dni_cliente
                                     LEFT JOIN empleados e ON b.dni_empleado = e.dni_empleado WHERE b.id_boleta = ?");
        $stmt->execute([$id_boleta]);
        $fila = $stmt->fetch();
        return $fila ? new Boleta($fila) : null;
    }

    public function obtener_todas() {
        $sql = "SELECT b.*, c.nombre_apellido as cliente, e.nombre_apellido as empleado FROM boletas b 
                LEFT JOIN clientes c ON b.dni_cliente = c.dni_cliente
                LEFT JOIN empleados e ON b.dni_empleado = e.dni_empleado ORDER BY b.id_boleta DESC";
        $res = $this->pdo->query($sql)->fetchAll();
        return array_map(fn($f) => new Boleta($f), $res);
    }

    public function obtener_por_mes($mes) {
        if ($mes === '') return $this->obtener_todas();
        
        // 🚀 ADAPTADOR DE FECHAS
        $fechaSql = $this->qb->fechaFormateada('b.fecha_emision', 'YYYY-MM');
        
        $stmt = $this->pdo->prepare("SELECT b.*, c.nombre_apellido as cliente, e.nombre_apellido as empleado FROM boletas b 
                                    LEFT JOIN clientes c ON b.dni_cliente = c.dni_cliente
                                    LEFT JOIN empleados e ON b.dni_empleado = e.dni_empleado 
                                    WHERE $fechaSql = ? ORDER BY b.id_boleta DESC");
        $stmt->execute([$mes]);
        $res = $stmt->fetchAll();
        return array_map(fn($f) => new Boleta($f), $res);
    }

    public function obtener_meses_disponibles() {
        // 🚀 ADAPTADOR DE FECHAS
        $fechaSql = $this->qb->fechaFormateada('fecha_emision', 'YYYY-MM');
        $sql = "SELECT DISTINCT $fechaSql as mes FROM boletas WHERE fecha_emision IS NOT NULL ORDER BY mes DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function obtener_ingresos_por_dia() {
        // 🚀 ADAPTADOR DE FECHAS E INTERVALOS
        $fechaSql = $this->qb->fechaFormateada('fecha_emision', 'YYYY-MM-DD');
        $filtroDias = $this->qb->ultimosDias('fecha_emision', 7);

        $sql = "SELECT $fechaSql as dia, SUM(total) as total_dia FROM boletas 
                WHERE $filtroDias GROUP BY $fechaSql ORDER BY dia ASC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function eliminar($id_boleta) {
        $this->pdo->prepare("DELETE FROM ventas WHERE id_boleta = ?")->execute([$id_boleta]);
        return $this->pdo->prepare("DELETE FROM boletas WHERE id_boleta = ?")->execute([$id_boleta]);
    }
    public function obtener_por_token($token) {
        $stmt = $this->pdo->prepare("SELECT b.*, c.nombre_apellido as cliente, e.nombre_apellido as empleado FROM boletas b 
                                    LEFT JOIN clientes c ON b.dni_cliente = c.dni_cliente
                                    LEFT JOIN empleados e ON b.dni_empleado = e.dni_empleado WHERE b.token = ?");
        $stmt->execute([$token]);
        $fila = $stmt->fetch();
        return $fila ? new Boleta($fila) : null;
    }

    public function obtener_por_numero($numero_boleta) {
        $stmt = $this->pdo->prepare("SELECT b.*, c.nombre_apellido as cliente, e.nombre_apellido as empleado FROM boletas b 
                                    LEFT JOIN clientes c ON b.dni_cliente = c.dni_cliente
                                    LEFT JOIN empleados e ON b.dni_empleado = e.dni_empleado WHERE b.numero_boleta = ?");
        $stmt->execute([$numero_boleta]);
        $fila = $stmt->fetch();
        return $fila ? new Boleta($fila) : null;
    }
}