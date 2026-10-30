<?php
class VentaDAO {
    private $pdo;
    public function __construct() { $this->pdo = Conexion::conectar(); }

    public function insertar($id_boleta, $id_producto, $cantidad, $precio) {
        $stmt = $this->pdo->prepare("INSERT INTO ventas (id_boleta, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$id_boleta, $id_producto, $cantidad, $precio]);
    }

    public function obtener_por_boleta($id_boleta) {
        $stmt = $this->pdo->prepare("SELECT v.*, p.nombre_producto as producto FROM ventas v
                                    LEFT JOIN productos p ON v.id_producto = p.id_producto
                                    WHERE v.id_boleta = ? ORDER BY v.id_venta ASC");
        $stmt->execute([$id_boleta]);
        $res = $stmt->fetchAll();
        return array_map(fn($f) => new Venta($f), $res);
    }
}