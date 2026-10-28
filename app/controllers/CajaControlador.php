<?php
class CajaControlador {

    //muestra la caja con el formulario y el carrito actual
    public function mostrar(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        $userDNI = isset($_SESSION['UsuarioDni']) ? $_SESSION['UsuarioDni'] : '';
        if ($userDNI === '') {
            echo '<div class="container mt-4"><h2>Debes <a href="' . BASE_URL . 'index.php?controller=empleado&action=login">Iniciar Sesion</a></h2></div>';
            return;
        }

        //verificamos si el empleado tiene una caja abierta
        $cajaDAO = new CajaDAO();
        $cajaActiva = $cajaDAO->obtener_activa($userDNI);

        //si no hay caja abierta, mostramos la pantalla de apertura
        if (!$cajaActiva) {
            $cajaAsignada = $cajaDAO->obtener_ultima_cerrada($userDNI);
            $tieneCajaAsignada = ($cajaAsignada) ? true : false;
            require_once APP_PATH . '/views/caja_apertura.php';
            return;
        }

        //instancia DAOs
        $empDAO  = new EmpleadoDAO();
        $cliDAO  = new ClienteDAO();
        $pdtoDAO = new ProductoDAO();

        //obtiene los datos del empleado logueado
        $empData = $empDAO->obtener_por_dni($userDNI);
        $empleado = new Empleado($empData['nombre_apellido'], $empData['telefono']);
        
        //obtiene los productos con categoría para los iconos
        $productosRaw = $pdtoDAO->obtener_todos_con_categoria(); 
        
        $iconosCategorias = [
            'Lácteos' => '🥛', 'Chocolates' => '🍫', 'Abarrotes' => '🌾',
            'Bebidas' => '🥤', 'Limpieza' => '🧹', 'Panadería' => '🍞',
            'Carnes' => '🥩', 'Snacks' => '🍿', 'Frutas' => '🍎', 'Cuidado Personal' => '🧴'
        ];

        $productos = [];
        // 🚀 CAMBIO: foreach en lugar de while->fetch_assoc()
        foreach ($productosRaw as $p) { 
            $cat = $p['nombre_catg'];
            $p['icono'] = isset($iconosCategorias[$cat]) ? $iconosCategorias[$cat] : '📦';
            $productos[] = $p;
        }

        $clientes = $cliDAO->obtener_todos();
        $clienteActual = isset($_SESSION['clienteDNI']) ? $_SESSION['clienteDNI'] : '';
        
        $items = [];
        $total = 0;
        $mensajeCaja = isset($_SESSION['mensajeCaja']) ? $_SESSION['mensajeCaja'] : '';
        unset($_SESSION['mensajeCaja']);

        require_once APP_PATH . '/views/caja.php';
    }

    //Método para que el empleado abra su caja
    public function abrir_caja(){
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $userDNI = isset($_SESSION['UsuarioDni']) ? $_SESSION['UsuarioDni'] : '';
        if ($userDNI === '') {
            header('Location: ' . BASE_URL . 'index.php?controller=empleado&action=login');
            exit;
        }

        $cajaDAO = new CajaDAO();
        
        // 1. Verificamos si ya tiene una caja abierta
        $cajaAbierta = $cajaDAO->obtener_activa($userDNI);
        if ($cajaAbierta) {
            header('Location: ' . BASE_URL . 'index.php?controller=caja&action=mostrar');
            exit;
        }

        // 2. Buscamos la caja que el admin le asignó
        $cajaAsignada = $cajaDAO->obtener_ultima_cerrada($userDNI);

        if ($cajaAsignada) {
            // 3. La reabrimos
            $cajaDAO->reabrir($cajaAsignada['id_caja']);
            header('Location: ' . BASE_URL . 'index.php?controller=caja&action=mostrar');
        } else {
            $_SESSION['mensajeCaja'] = 'No tienes una caja asignada. Por favor, pide al administrador que te asigne un sencillo.';
            header('Location: ' . BASE_URL . 'index.php?controller=caja&action=mostrar');
        }
        exit;
    }

    //quita un producto del carrito
    public function quitar(){
        if (session_status() === PHP_SESSION_NONE) session_start();
        $idProducto = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($idProducto > 0) {
            Carrito::quitar($idProducto);
        }
        header('Location: ' . BASE_URL . 'index.php?controller=caja&action=mostrar');
        exit;
    }

    //vacía todo el carrito
    public function vaciar(){
        if (session_status() === PHP_SESSION_NONE) session_start();
        Carrito::vaciar();
        header('Location: ' . BASE_URL . 'index.php?controller=caja&action=mostrar');
        exit;
    }

    //confirma la venta: inserta en BD y redirige a la boleta
    public function confirmar(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        $userDNI = isset($_SESSION['UsuarioDni']) ? $_SESSION['UsuarioDni'] : '';
        if ($userDNI === '') {
            header('Location: ' . BASE_URL . 'index.php?controller=empleado&action=login');
            exit;
        }

        $clienteDNI = isset($_POST['cliente']) ? trim($_POST['cliente']) : '';
        $carritoJson = isset($_POST['carrito']) ? $_POST['carrito'] : '';
        $items = json_decode($carritoJson, true);

        if ($clienteDNI === '' || empty($items)) {
            header('Location: ' . BASE_URL . 'index.php?controller=caja&action=mostrar');
            exit;
        }

        // Instanciamos todos los DAO necesarios
        $pdtoDAO   = new ProductoDAO();
        $boletaDAO = new BoletaDAO();
        $ventaDAO  = new VentaDAO();
        $cajaDAO   = new CajaDAO();

        // Obtener la caja activa del empleado
        $cajaActiva = $cajaDAO->obtener_activa($userDNI);
        if (!$cajaActiva) {
            header('Location: ' . BASE_URL . 'index.php?controller=caja&action=mostrar');
            exit;
        }
        $id_caja = $cajaActiva['id_caja'];

        $total = 0;
        foreach ($items as $item) {
            $total += ($item['precio'] * $item['cantidad']);
        }

        $fecha = date("Y-m-d H:i:s");

        // 1. Insertar la boleta
        $id_boleta = $boletaDAO->insertar($clienteDNI, $userDNI, $total, $fecha, $id_caja);

        // 2. Insertar el detalle y descontar stock
        foreach ($items as $item) {
            $pdtoDAO->descontar_stock($item['id'], $item['cantidad']);
            $ventaDAO->insertar($id_boleta, $item['id'], $item['cantidad'], $item['precio']);
        }

        // 3. Vaciamos el carrito de la sesión
        Carrito::vaciar(); 
        unset($_SESSION['clienteDNI']);

        // 4. Obtenemos el token para redirigir al PDF
        $boletaData = $boletaDAO->obtener_por_id($id_boleta);
        
        // 5. Redirigimos a la URL de la boleta
        header('Location: ' . BASE_URL . 'index.php?controller=boleta&action=mostrar&token=' . $boletaData['token']);
        exit;
    }

    public function cerrar_caja(){
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $userDNI = isset($_SESSION['UsuarioDni']) ? $_SESSION['UsuarioDni'] : '';
        if ($userDNI === '') {
            header('Location: ' . BASE_URL . 'index.php?controller=empleado&action=login');
            exit;
        }

        $cajaDAO = new CajaDAO();
        $cajaActiva = $cajaDAO->obtener_activa($userDNI);

        if ($cajaActiva) {
            $id_caja = $cajaActiva['id_caja'];
            $totalVentas = $cajaDAO->calcular_ventas($id_caja);
            $totalEnCaja = $cajaActiva['sencillo_inicial'] + $totalVentas;

            // Cerramos la caja en la BD
            $cajaDAO->cerrar($id_caja);
            
            // Datos para la vista
            $sencillo_inicial = $cajaActiva['sencillo_inicial'];
            $total_ventas = $totalVentas;
            $total_en_caja = $totalEnCaja;

            require_once APP_PATH . '/views/caja_cierre.php';
            exit;
        }
        
        header('Location: ' . BASE_URL . 'index.php?controller=caja&action=mostrar');
        exit;
    }
}