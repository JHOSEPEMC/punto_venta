<?php
class Venta {
    private $bd;

    public function __construct(){
        $this->bd = Conexion::conectar();
    }

    // ═══════════════════════════════════════════════════════
    // MÉTODOS EXISTENTES (sin cambios)
    // ═══════════════════════════════════════════════════════

    public function insertar($dni_empleado, $dni_cliente, $id_producto, $cantidad, $fecha){
        $sql = "INSERT INTO ventas (dni_empleado, dni_cliente, id_producto, cantidad, fecha_venta)
                VALUES ('$dni_empleado', '$dni_cliente', '$id_producto', '$cantidad', '$fecha')";
        return $this->bd->query($sql);
    }

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

    // ═══════════════════════════════════════════════════════
    // MÉTODOS NUEVOS (para filtros)
    // ═══════════════════════════════════════════════════════

    //devuelve las ventas filtradas por mes (formato YYYY-MM)
    public function obtener_por_mes($mes){
        //si el mes está vacío, devuelve todas
        if ($mes === '') {
            return $this->obtener_todas();
        }

        //filtra por mes: WHERE fecha_venta LIKE 'YYYY-MM%'
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
            WHERE ventas.fecha_venta LIKE '$mes%'
            ORDER BY ventas.id_venta ASC
        ";
        return $this->bd->query($sql);
    }

    //devuelve los meses que tienen ventas (para el <select>)
    public function obtener_meses_disponibles(){
        //agrupa las fechas por mes (YYYY-MM) y las cuenta
        $sql = "
            SELECT DISTINCT DATE_FORMAT(fecha_venta, '%Y-%m') as mes
            FROM ventas
            WHERE fecha_venta IS NOT NULL
            ORDER BY mes DESC
        ";
        return $this->bd->query($sql);
    }

    //devuelve una venta filtrada por id_boleta
    public function obtener_por_id_boleta($id_boleta){
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
            WHERE ventas.id_boleta = '$id_boleta'
            ORDER BY ventas.id_venta ASC
        ";
        return $this->bd->query($sql);
    }
}