<?php
class Venta {
    private $bd;

    public function __construct(){
        $this->bd = Conexion::conectar();
    }

    //inserta una fila en el detalle de la venta
    public function insertar($id_boleta, $id_producto, $cantidad, $precio_unitario){
        $sql = "INSERT INTO ventas (id_boleta, id_producto, cantidad, precio_unitario)
                VALUES (" . (int)$id_boleta . ", " . (int)$id_producto . ", " . (int)$cantidad . ", '$precio_unitario')";
        return $this->bd->query($sql);
    }

    //devuelve el detalle de una boleta
    public function obtener_por_boleta($id_boleta){
        $sql = "
            SELECT ventas.id_venta,
                    ventas.cantidad,
                    ventas.precio_unitario,
                    productos.nombre_producto as producto
            FROM ventas
            LEFT JOIN productos ON ventas.id_producto = productos.id_producto
            WHERE ventas.id_boleta = " . (int)$id_boleta . "
            ORDER BY ventas.id_venta ASC
        ";
        return $this->bd->query($sql);
    }
}