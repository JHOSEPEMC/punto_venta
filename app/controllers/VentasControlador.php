<?php
class VentasControlador {
    // Muestra el listado de ventas
    public function mostrar(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        // Vaciar carrito al entrar aquí
        $carritoTotal = isset($_SESSION['totalCarrito']) ? $_SESSION['totalCarrito'] : null;
        if ($carritoTotal != null) $_SESSION['totalCarrito'] = null;

        require_once APP_PATH . '/models/Conexion.php';
        $bd = Conexion::conectar();

        // Consulta para el sql :SillyDev:
        $sql = "
            SELECT ventas.id_venta,
                    empleados.nombre_apellido as empleado,
                    clientes.nombre_apellido as cliente,
                    productos.nombre_producto as producto,
                    ventas.cantidad,
                    ventas.fecha_venta
            FROM ventas
            INNER JOIN productos ON ventas.id_producto = productos.id_producto
            INNER JOIN empleados ON ventas.dni_empleado = empleados.dni_empleado
            INNER JOIN clientes  ON ventas.dni_cliente  = clientes.dni_cliente
        ";
        $ventas = $bd->query($sql);

        require_once APP_PATH . '/views/ventas.php';
    }
}