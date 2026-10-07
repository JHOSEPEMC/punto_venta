<?php
class Venta {
    private $bd;

    //conecta con la base de datos al instanciar
    public function __construct(){
        $this->bd = Conexion::conectar();
    }

    //inserta una fila en el detalle usando sentencia preparada
    public function insertar($id_boleta, $id_producto, $cantidad, $precio_unitario){
        $sql = "INSERT INTO ventas (id_boleta, id_producto, cantidad, precio_unitario)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("iiid", $id_boleta, $id_producto, $cantidad, $precio_unitario);
        return $stmt->execute();
    }

    //devuelve el detalle de una boleta usando sentencia preparada
    public function obtener_por_boleta($id_boleta){
        $sql = "
            SELECT ventas.id_venta,
                    ventas.cantidad,
                    ventas.precio_unitario,
                    productos.nombre_producto as producto
            FROM ventas
            LEFT JOIN productos ON ventas.id_producto = productos.id_producto
            WHERE ventas.id_boleta = ?
            ORDER BY ventas.id_venta ASC
        ";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("i", $id_boleta);
        $stmt->execute();
        return $stmt->get_result();
    }
}