<?php
class VentasControlador {

    public function mostrar(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        // Verificamos si es empleado o admin
        $esEmpleado = isset($_SESSION['UsuarioDni']);
        $esAdmin    = isset($_SESSION['AdminDni']);

        // Si no es ninguno de los dos, lo mandamos al home
        if (!$esEmpleado && !$esAdmin) {
            header('Location: ' . BASE_URL . 'index.php?controller=home&action=mostrar');
            exit;
        }

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

        // Datos para la gráfica
        $graficaData = $boletaModel->obtener_ingresos_por_dia();
        $labels = [];
        $valores = [];
        while ($row = $graficaData->fetch_assoc()) {
            $labels[] = date('d/m', strtotime($row['dia']));
            $valores[] = $row['total_dia'];
        }

        require_once APP_PATH . '/views/ventas.php';
    }
}