<?php
class CajaControlador {
    public function mostrar(){
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userDNI = $_SESSION['UsuarioDni'] ?? '';
        if ($userDNI === '') {
            echo '<div class="container mt-4"><h2>Debes <a href="' . BASE_URL . 'index.php?controller=empleado&action=login">Iniciar Sesion</a></h2></div>';
            return;
        }

        $cajaDAO = new CajaDAO();
        $cajaActiva = $cajaDAO->obtener_activa($userDNI);

        if (!$cajaActiva) {
            $cajaAsignada = $cajaDAO->obtener_ultima_cerrada($userDNI);
            $tieneCajaAsignada = ($cajaAsignada) ? true : false;
            require_once APP_PATH . '/views/caja_apertura.php';
            return;
        }

        $empDAO = new EmpleadoDAO();
        $cliDAO = new ClienteDAO();
        $pdtoDAO = new ProductoDAO();

        $empleado = $empDAO->obtener_por_dni($userDNI);
        $productosRaw = $pdtoDAO->obtener_todos_con_categoria(); 
        
        $iconosCategorias = ['Lácteos' => '🥛', 'Chocolates' => '🍫', 'Abarrotes' => '🌾', 'Bebidas' => '🥤', 'Limpieza' => '🧹', 'Panadería' => '🍞', 'Carnes' => '🥩', 'Snacks' => '🍿', 'Frutas' => '🍎', 'Cuidado Personal' => '🧴'];
        
        $productos = [];
        foreach ($productosRaw as $p) { 
            // $p ya es un OBJETO Producto
            $cat = $p->nombre_categoria;
            $p->icono = isset($iconosCategorias[$cat]) ? $iconosCategorias[$cat] : '📦';
            $productos[] = $p;
        }

        $clientes = $cliDAO->obtener_todos();
        $clienteActual = $_SESSION['clienteDNI'] ?? '';
        
        $items = []; $total = 0;
        $mensajeCaja = $_SESSION['mensajeCaja'] ?? '';
        unset($_SESSION['mensajeCaja']);

        require_once APP_PATH . '/views/caja.php';
    }

    public function abrir_caja(){
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userDNI = $_SESSION['UsuarioDni'] ?? '';
        if ($userDNI === '') { header('Location: ' . BASE_URL . 'index.php?controller=empleado&action=login'); exit; }

        $cajaDAO = new CajaDAO();
        if ($cajaDAO->obtener_activa($userDNI)) {
            header('Location: ' . BASE_URL . 'index.php?controller=caja&action=mostrar');
            exit;
        }

        $cajaAsignada = $cajaDAO->obtener_ultima_cerrada($userDNI);
        if ($cajaAsignada) {
            $cajaDAO->reabrir($cajaAsignada->id);
        } else {
            $_SESSION['mensajeCaja'] = 'No tienes una caja asignada. Por favor, pide al administrador que te asigne un sencillo.';
        }
        header('Location: ' . BASE_URL . 'index.php?controller=caja&action=mostrar');
        exit;
    }

    public function confirmar(){
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userDNI = $_SESSION['UsuarioDni'] ?? '';
        if ($userDNI === '') { header('Location: ' . BASE_URL . 'index.php?controller=empleado&action=login'); exit; }

        $clienteDNI = trim($_POST['cliente'] ?? '');
        $items = json_decode($_POST['carrito'] ?? '[]', true);

        if ($clienteDNI === '' || empty($items)) {
            header('Location: ' . BASE_URL . 'index.php?controller=caja&action=mostrar');
            exit;
        }

        $pdtoDAO = new ProductoDAO();
        $boletaDAO = new BoletaDAO();
        $ventaDAO = new VentaDAO();
        $cajaDAO = new CajaDAO();

        $cajaActiva = $cajaDAO->obtener_activa($userDNI);
        if (!$cajaActiva) { header('Location: ' . BASE_URL . 'index.php?controller=caja&action=mostrar'); exit; }
        $id_caja = $cajaActiva->id;

        $total = 0;
        foreach ($items as $item) { $total += ($item['precio'] * $item['cantidad']); }

        $fecha = date("Y-m-d H:i:s");
        $id_boleta = $boletaDAO->insertar($clienteDNI, $userDNI, $total, $fecha, $id_caja);

        foreach ($items as $item) {
            $pdtoDAO->descontar_stock($item['id'], $item['cantidad']);
            $ventaDAO->insertar($id_boleta, $item['id'], $item['cantidad'], $item['precio']);
        }

        Carrito::vaciar(); 
        unset($_SESSION['clienteDNI']);
        
        $boletaData = $boletaDAO->obtener_por_id($id_boleta);
        header('Location: ' . BASE_URL . 'index.php?controller=boleta&action=mostrar&token=' . $boletaData->token);
        exit;
    }

    public function cerrar_caja(){
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userDNI = $_SESSION['UsuarioDni'] ?? '';
        if ($userDNI === '') { header('Location: ' . BASE_URL . 'index.php?controller=empleado&action=login'); exit; }

        $cajaDAO = new CajaDAO();
        $cajaActiva = $cajaDAO->obtener_activa($userDNI);

        if ($cajaActiva) {
            $totalVentas = $cajaDAO->calcular_ventas($cajaActiva->id);
            $totalEnCaja = $cajaActiva->sencillo_inicial + $totalVentas;
            $cajaDAO->cerrar($cajaActiva->id);
            
            $sencillo_inicial = $cajaActiva->sencillo_inicial;
            $total_ventas = $totalVentas;
            $total_en_caja = $totalEnCaja;

            require_once APP_PATH . '/views/caja_cierre.php';
            exit;
        }
        header('Location: ' . BASE_URL . 'index.php?controller=caja&action=mostrar');
        exit;
    }
}