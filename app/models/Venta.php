<?php
class Venta {
    private $bd;

    public function __construct(){
        $this->bd = Conexion::conectar();
    }

    //Inserta una nueva venta
    public function insertar($dni_empleado, $dni_cliente, $id_producto, $cantidad, $fecha){
        $sql = "INSERT INTO ventas (dni_empleado, dni_cliente, id_producto, cantidad, fecha_venta)
                VALUES ('$dni_empleado', '$dni_cliente', '$id_producto', '$cantidad', '$fecha')";
        return $this->bd->query($sql);
    }

    //Devuelve todas las ventas con datos cruzados (empleado, cliente, producto)
    //Usa LEFT JOIN para no perder ventas de clientes/productos eliminados
    public function obtener_todas(){
        $sql = "
            SELECT ventas.id_venta,
                   empleados.nombre_apellido as empleado,
                   clientes.nombre_apellido  as cliente,
                   productos.nombre_producto as producto,
                   ventas.dni_cliente,
                   ventas.id_producto,
                   ventas.cantidad,
                   ventas.fecha_venta
            FROM ventas
            LEFT JOIN productos ON ventas.id_producto = productos.id_producto
            LEFT JOIN empleados ON ventas.dni_empleado = empleados.dni_empleado
            LEFT JOIN clientes  ON ventas.dni_cliente  = clientes.dni_cliente
            ORDER BY ventas.id_venta ASC
        ";
        return $this->bd->query($sql);
    }
}