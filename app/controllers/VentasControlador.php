<?php
class VentasControlador {

    public function mostrar(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        $carritoTotal = isset($_SESSION['totalCarrito']) ? $_SESSION['totalCarrito'] : null;
        if ($carritoTotal != null) $_SESSION['totalCarrito'] = null;

        //recibe los filtros desde la URL
        $mesFiltro      = isset($_GET['mes'])        ? trim($_GET['mes'])        : '';
        $idBoletaFiltro = isset($_GET['id_boleta'])  ? trim($_GET['id_boleta'])  : '';

        //instancia el modelo
        $ventaModel = new Venta();

        //si hay búsqueda por boleta, filtra por boleta
        if ($idBoletaFiltro !== '') {
            $ventas = $ventaModel->obtener_por_id_boleta($idBoletaFiltro);
        }
        //si hay filtro por mes, filtra por mes
        elseif ($mesFiltro !== '') {
            $ventas = $ventaModel->obtener_por_mes($mesFiltro);
        }
        //si no hay filtro, muestra todas
        else {
            $ventas = $ventaModel->obtener_todas();
        }

        //lista de meses disponibles para el <select>
        $meses = $ventaModel->obtener_meses_disponibles();

        require_once APP_PATH . '/views/ventas.php';
    }
}