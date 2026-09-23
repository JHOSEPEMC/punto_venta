<?php
class VentasControlador {

    public function mostrar(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        $carritoTotal = isset($_SESSION['totalCarrito']) ? $_SESSION['totalCarrito'] : null;
        if ($carritoTotal != null) $_SESSION['totalCarrito'] = null;

        require_once APP_PATH . '/models/Conexion.php';
        $bd = Conexion::conectar();

        // LEFT JOIN: no perdemos ventas aunque el producto, empleado
        // o cliente hayan sido eliminados
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
        $ventas = $bd->query($sql);

        require_once APP_PATH . '/views/ventas.php';
    }
}