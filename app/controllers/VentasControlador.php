<?php
class VentasControlador {
    public function mostrar(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        $mesFiltro   = isset($_GET['mes'])         ? trim($_GET['mes'])         : '';
        $numeroFiltro = isset($_GET['numero_boleta']) ? trim($_GET['numero_boleta']) : '';

        $boletaDAO = new BoletaDAO();

        if ($numeroFiltro !== '') {
            $boleta = $boletaDAO->obtener_por_numero($numeroFiltro); // (Añadir este método en el DAO)
            $boletas = $boleta ? [$boleta] : [];
        } elseif ($mesFiltro !== '') {
            $boletas = $boletaDAO->obtener_por_mes($mesFiltro);
        } else {
            $boletas = $boletaDAO->obtener_todas();
        }

        $meses = $boletaDAO->obtener_meses_disponibles();

        // 🚀 Gráfica con foreach (ya que es un array)
        $graficaData = $boletaDAO->obtener_ingresos_por_dia();
        $labels = [];
        $valores = [];
        foreach ($graficaData as $row) {
            $labels[] = date('d/m', strtotime($row['dia']));
            $valores[] = $row['total_dia'];
        }

        require_once APP_PATH . '/views/ventas.php';
    }
}