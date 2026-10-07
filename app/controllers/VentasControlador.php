<?php
class VentasControlador {

    public function mostrar(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        $carritoTotal = isset($_SESSION['totalCarrito']) ? $_SESSION['totalCarrito'] : null;
        if ($carritoTotal != null) $_SESSION['totalCarrito'] = null;

        //recibe filtros desde la URL
        $mesFiltro   = isset($_GET['mes'])         ? trim($_GET['mes'])         : '';
        $numeroFiltro = isset($_GET['numero_boleta']) ? trim($_GET['numero_boleta']) : '';

        //instancia modelo
        $boletaModel = new Boleta();

        //si hay búsqueda por número de boleta
        if ($numeroFiltro !== '') {
            $boleta = $boletaModel->obtener_por_numero($numeroFiltro);
            $boletas = $boleta ? [$boleta] : [];
        }
        //si hay filtro por mes
        elseif ($mesFiltro !== '') {
            $boletas = $boletaModel->obtener_por_mes($mesFiltro);
        }
        //sin filtro
        else {
            $boletas = $boletaModel->obtener_todas();
        }

        //meses disponibles para el <select>
        $meses = $boletaModel->obtener_meses_disponibles();

        require_once APP_PATH . '/views/ventas.php';
    }
}