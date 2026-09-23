<?php
class InventarioControlador {
    // Muestra el listado de productos
    public function mostrar(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        require_once APP_PATH . '/models/Conexion.php';
        require_once APP_PATH . '/models/Producto.php';

        $pdto = new Producto();
        $bd   = Conexion::conectar();
        // Consulta para el sql :SillyDev:
        $productos = $bd->query("SELECT * FROM productos");

        require_once APP_PATH . '/views/inventario.php';
    }
}