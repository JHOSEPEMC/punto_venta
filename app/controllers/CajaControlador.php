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
        $cajaModel = new Caja();
        $cajaActiva = $cajaModel->obtener_activa($userDNI);

        //si no hay caja abierta, mostramos la pantalla de apertura
        if (!$cajaActiva) {
            // Verificamos si el admin ya le asignó un sencillo (caja en estado 'cerrada')
            $cajaAsignada = $cajaModel->obtener_ultima_cerrada($userDNI);
            
            // Pasamos esta variable a la vista (true si el admin ya le asignó, false si no)
            $tieneCajaAsignada = ($cajaAsignada) ? true : false;

            require_once APP_PATH . '/views/caja_apertura.php';
            return;
        }

        //instancia modelos
        $emp  = new Empleado();
        $cli  = new Cliente();
        $pdto = new Producto();

        //obtiene los datos del empleado logueado
        $empData = $emp->obtener_por_dni($userDNI);
        $empleado = new Empleado($empData['nombre_apellido'], $empData['telefono']);
        
        //obtiene los productos con categoría para los iconos
        $productosRaw = $pdto->obtener_todos_con_categoria(); 
        
        $iconosCategorias = [
            'Lácteos' => '🥛', 'Chocolates' => '🍫', 'Abarrotes' => '🌾',
            'Bebidas' => '🥤', 'Limpieza' => '🧹', 'Panadería' => '🍞',
            'Carnes' => '🥩', 'Snacks' => '🍿', 'Frutas' => '🍎', 'Cuidado Personal' => '🧴'
        ];

        $productos = [];
        while ($p = $productosRaw->fetch_assoc()) {
            $cat = $p['nombre_catg'];
            $p['icono'] = isset($iconosCategorias[$cat]) ? $iconosCategorias[$cat] : '📦';
            $productos[] = $p;
        }

        $clientes = $cli->obtener_todos();
        $clienteActual = isset($_SESSION['clienteDNI']) ? $_SESSION['clienteDNI'] : '';
        //$items = Carrito::obtener();
        //$total = Carrito::total();
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

        $cajaModel = new Caja();
        
        // 1. Verificamos si ya tiene una caja abierta (por si acaso)
        $cajaAbierta = $cajaModel->obtener_activa($userDNI);
        if ($cajaAbierta) {
            header('Location: ' . BASE_URL . 'index.php?controller=caja&action=mostrar');
            exit;
        }

        // 2. Buscamos la caja que el admin le asignó (estado cerrada)
        $cajaAsignada = $cajaModel->obtener_ultima_cerrada($userDNI);

        if ($cajaAsignada) {
            // 3. La reabrimos
            $cajaModel->reabrir($cajaAsignada['id_caja']);
            header('Location: ' . BASE_URL . 'index.php?controller=caja&action=mostrar');
        } else {
            // Si no hay caja asignada, mostramos el mensaje de error
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

    //confirma la venta: inserta en BD y redirige al historial
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

        $pdto     = new Producto();
        $boleta   = new Boleta();
        $venta    = new Venta();
        $cajaModel = new Caja();

        //obtener la caja activa del empleado
        $cajaActiva = $cajaModel->obtener_activa($userDNI);
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

        //insertar la boleta
        $id_boleta = $boleta->insertar($clienteDNI, $userDNI, $total, $fecha, $id_caja);

        //insertar el detalle
        foreach ($items as $item) {
            $pdto->descontar_stock($item['id'], $item['cantidad']);
            $venta->insertar($id_boleta, $item['id'], $item['cantidad'], $item['precio']);
        }

        // 🚀 Guardamos el ID en sesión para abrirlo en ventas.php
        $_SESSION['boleta_nueva'] = $id_boleta;
        
        // Redirigimos al historial de ventas
        header('Location: ' . BASE_URL . 'index.php?controller=ventas&action=mostrar');
        
        // ... (código donde insertas la boleta y el detalle) ...
        // 6. Redirigimos a la URL de la boleta (con su token seguro)
        // Como el formulario tiene target="_blank", esto se abrirá en la pestaña nueva.
        $boletaData = $boleta->obtener_por_id($id_boleta);
        
        // ... (insertar la boleta y el detalle) ...

        // 🚀 VACIAMOS EL CARRITO DE LA SESIÓN
        Carrito::vaciar(); 
        unset($_SESSION['clienteDNI']);

        // 6. Redirigimos a la URL de la boleta (con su token seguro)
        $boletaData = $boleta->obtener_por_id($id_boleta);
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

        $cajaModel = new Caja();
        $cajaActiva = $cajaModel->obtener_activa($userDNI);

        if ($cajaActiva) {
            $id_caja = $cajaActiva['id_caja'];
            $totalVentas = $cajaModel->calcular_ventas($id_caja);
            $totalEnCaja = $cajaActiva['sencillo_inicial'] + $totalVentas;

            // Cerramos la caja en la BD
            $cajaModel->cerrar($id_caja);
            
            // Datos para la vista
            $sencillo_inicial = $cajaActiva['sencillo_inicial'];
            $total_ventas = $totalVentas;
            $total_en_caja = $totalEnCaja;

            // Cargamos la nueva vista bonita
            require_once APP_PATH . '/views/caja_cierre.php';
            exit;
        }
        
        // Si no había caja activa, volvemos a la caja
        header('Location: ' . BASE_URL . 'index.php?controller=caja&action=mostrar');
        exit;
    }
}