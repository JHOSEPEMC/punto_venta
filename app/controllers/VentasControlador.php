<?php
class VentasControlador {

    public function mostrar(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        $carritoTotal = isset($_SESSION['totalCarrito']) ? $_SESSION['totalCarrito'] : null;
        if ($carritoTotal != null) $_SESSION['totalCarrito'] = null;

        //SQL ya NO está aquí. El modelo Venta se encarga.
        $venta = new Venta();
        $ventas = $venta->obtener_todas();

        require_once APP_PATH . '/views/ventas.php';
    }
}