<?php
class VentasControlador {
    public function mostrar(){
        if (session_status() === PHP_SESSION_NONE) session_start();
        $mesFiltro = trim($_GET['mes'] ?? '');
        $numeroFiltro = trim($_GET['numero_boleta'] ?? '');

        $boletaDAO = new BoletaDAO();

        if ($numeroFiltro !== '') {
            $boleta = $boletaDAO->obtener_por_numero($numeroFiltro);
            $boletas = $boleta ? [$boleta] : [];
        } elseif ($mesFiltro !== '') {
            $boletas = $boletaDAO->obtener_por_mes($mesFiltro);
        } else {
            $boletas = $boletaDAO->obtener_todas();
        }

        $meses = $boletaDAO->obtener_meses_disponibles();
        $graficaData = $boletaDAO->obtener_ingresos_por_dia();
        $labels = array_map(fn($r) => date('d/m', strtotime($r['dia'])), $graficaData);
        $valores = array_column($graficaData, 'total_dia');

        require_once APP_PATH . '/views/ventas.php';
    }
}