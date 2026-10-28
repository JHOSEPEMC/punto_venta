<?php
class BoletaDAO {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexion::conectar();
    }

    public function insertar($dni_cliente, $dni_empleado, $total, $fecha, $id_caja = null) {
        $token = bin2hex(random_bytes(16));
        $stmt = $this->pdo->prepare("INSERT INTO boletas (numero_boleta, token, id_caja, dni_cliente, dni_empleado, total, fecha_emision) VALUES ('', ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$token, $id_caja, $dni_cliente, $dni_empleado, $total, $fecha]);

        $id_boleta = $this->pdo->lastInsertId();
        $numero = 'BOL-' . str_pad($id_boleta, 6, '0', STR_PAD_LEFT);

        $stmt2 = $this->pdo->prepare("UPDATE boletas SET numero_boleta = ? WHERE id_boleta = ?");
        $stmt2->execute([$numero, $id_boleta]);

        return $id_boleta;
    }

    public function obtener_por_id($id_boleta) {
        $stmt = $this->pdo->prepare("SELECT b.*, c.nombre_apellido as cliente, e.nombre_apellido as empleado FROM boletas b 
                                    LEFT JOIN clientes c ON b.dni_cliente = c.dni_cliente
                                    LEFT JOIN empleados e ON b.dni_empleado = e.dni_empleado
                                    WHERE b.id_boleta = ?");
        $stmt->execute([$id_boleta]);
        return $stmt->fetch();
    }

    public function obtener_por_token($token) {
        $stmt = $this->pdo->prepare("SELECT b.*, c.nombre_apellido as cliente, e.nombre_apellido as empleado FROM boletas b 
                                    LEFT JOIN clientes c ON b.dni_cliente = c.dni_cliente
                                    LEFT JOIN empleados e ON b.dni_empleado = e.dni_empleado
                                    WHERE b.token = ?");
        $stmt->execute([$token]);
        return $stmt->fetch();
    }

    public function obtener_todas() {
        $sql = "SELECT b.*, c.nombre_apellido as cliente, e.nombre_apellido as empleado FROM boletas b 
                LEFT JOIN clientes c ON b.dni_cliente = c.dni_cliente
                LEFT JOIN empleados e ON b.dni_empleado = e.dni_empleado
                ORDER BY b.id_boleta DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function obtener_por_mes($mes) {
        if ($mes === '') return $this->obtener_todas();
        
        $stmt = $this->pdo->prepare("SELECT b.*, c.nombre_apellido as cliente, e.nombre_apellido as empleado FROM boletas b 
                                    LEFT JOIN clientes c ON b.dni_cliente = c.dni_cliente
                                    LEFT JOIN empleados e ON b.dni_empleado = e.dni_empleado
                                    WHERE DATE_FORMAT(b.fecha_emision, '%Y-%m') = ?
                                    ORDER BY b.id_boleta DESC");
        $stmt->execute([$mes]);
        return $stmt->fetchAll();
    }

    public function obtener_meses_disponibles() {
        $sql = "SELECT DISTINCT DATE_FORMAT(fecha_emision, '%Y-%m') as mes FROM boletas WHERE fecha_emision IS NOT NULL ORDER BY mes DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function obtener_ingresos_por_dia() {
        $sql = "SELECT DATE(fecha_emision) as dia, SUM(total) as total_dia FROM boletas 
                WHERE fecha_emision >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) 
                GROUP BY DATE(fecha_emision) ORDER BY dia ASC";
        return $this->pdo->query($sql)->fetchAll();
    }

    //Busca una boleta por su número visible (Ej: BOL-000001)
    public function obtener_por_numero($numero_boleta) {
        $stmt = $this->pdo->prepare("SELECT b.*, c.nombre_apellido as cliente, e.nombre_apellido as empleado FROM boletas b 
                                    LEFT JOIN clientes c ON b.dni_cliente = c.dni_cliente
                                    LEFT JOIN empleados e ON b.dni_empleado = e.dni_empleado
                                    WHERE b.numero_boleta = ?");
        $stmt->execute([$numero_boleta]);
        return $stmt->fetch();
    }
    // Eliminar
    public function eliminar($id_boleta) {
        // Primero borramos el detalle (ventas)
        $stmt = $this->pdo->prepare("DELETE FROM ventas WHERE id_boleta = ?");
        $stmt->execute([$id_boleta]);

        // Luego la cabecera (boleta)
        $stmt = $this->pdo->prepare("DELETE FROM boletas WHERE id_boleta = ?");
        return $stmt->execute([$id_boleta]);
    }
}