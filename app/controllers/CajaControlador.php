<?php
class CajaControlador {

    //muestra la caja con el formulario y el carrito actual
    public function mostrar(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        //verifica que el empleado esté logueado
        $userDNI = isset($_SESSION['UsuarioDni']) ? $_SESSION['UsuarioDni'] : '';
        if ($userDNI === '') {
            echo '<div class="container mt-4"><h2>Debes <a href="' . BASE_URL . 'index.php?controller=empleado&action=login">Iniciar Sesion</a> como <b>Empleado</h2></div>';
            return;
        }

        //inicia el carrito si no existe
        Carrito::iniciar();

        //instancia modelos
        $emp  = new Empleado();
        $cli  = new Cliente();
        $pdto = new Producto();

        //obtiene datos del empleado logueado
        $empData = $emp->obtener_por_dni($userDNI);
        $empleado = new Empleado($empData['nombre_apellido'], $empData['telefono']);

        //obtiene listas de clientes y productos
        $clientes = $cli->obtener_todos();
        // En CajaControlador::mostrar()
        #$productos = $pdto->obtener_todos_con_categoria(); // Usamos el método con JOIN

         // En CajaControlador::mostrar()
        $productosRaw = $pdto->obtener_todos_con_categoria();
        
        // Array de iconos por nombre de categoría (ajústalo a tus categorías reales)
        $iconosCategorias = [
            'Lácteos'      => '🥛',
            'Chocolates'   => '🍫',
            'Abarrotes'    => '🌾',
            'Bebidas'      => '🥤',
            'Limpieza'     => '🧹',
            'Panadería'    => '🍞',
            'Carnes'       => '🥩',
            'Snacks'       => '🍿',
            'Frutas'       => '🍎',
            'Cuidado Personal' => '🧴'
        ];

        $productos = [];
        while ($p = $productosRaw->fetch_assoc()) {
            // Busca el icono (si no está, usa uno por defecto)
            $cat = $p['nombre_catg'];
            $p['icono'] = isset($iconosCategorias[$cat]) ? $iconosCategorias[$cat] : '📦';
            $productos[] = $p;
        }

        //obtiene cliente seleccionado actualmente
        $clienteActual = isset($_SESSION['clienteDNI']) ? $_SESSION['clienteDNI'] : '';

        //obtiene items del carrito y total
        $items = Carrito::obtener();
        $total = Carrito::total();

        //recoge el mensaje flash y limpia la sesión
        $mensajeCaja = isset($_SESSION['mensajeCaja']) ? $_SESSION['mensajeCaja'] : '';
        unset($_SESSION['mensajeCaja']);

        //carga la vista de la caja
        require_once APP_PATH . '/views/caja.php';
    }

    //agrega un producto al carrito
    public function agregar(){
        //inicia la sesión
        if (session_status() === PHP_SESSION_NONE) session_start();

        //verifica que el empleado esté logueado
        $userDNI = isset($_SESSION['UsuarioDni']) ? $_SESSION['UsuarioDni'] : '';
        if ($userDNI === '') {
            header('Location: ' . BASE_URL . 'index.php?controller=empleado&action=login');
            exit;
        }

        //inicia el carrito
        Carrito::iniciar();

        //recoge los datos del formulario
        $clienteDNI = isset($_POST['ClienteDni']) ? trim($_POST['ClienteDni']) : '';
        $idProducto = isset($_POST['IdProducto']) ? (int)$_POST['IdProducto'] : 0;
        $cantidad   = isset($_POST['Cantidad'])   ? (int)$_POST['Cantidad']   : 1;

        //validaciones básicas
        if ($clienteDNI === '' || $idProducto === 0 || $cantidad < 1) {
            header('Location: ' . BASE_URL . 'index.php?controller=caja&action=mostrar');
            exit;
        }

        //si cambió de cliente, vaciamos el carrito
        $clienteEnSesion = isset($_SESSION['clienteDNI']) ? $_SESSION['clienteDNI'] : '';
        if ($clienteEnSesion !== $clienteDNI) {
            Carrito::vaciar();
            $_SESSION['clienteDNI'] = $clienteDNI;
        }

        //instanciamos el modelo Producto para verificar stock
        $pdto = new Producto();
        $producto = $pdto->obtener_por_id($idProducto);

        //si el producto no existe, volvemos a la caja
        if (!$producto) {
            header('Location: ' . BASE_URL . 'index.php?controller=caja&action=mostrar');
            exit;
        }

        //verificamos que haya stock suficiente
        if ($cantidad > $producto['stock']) {
            $_SESSION['mensajeCaja'] = 'No hay stock suficiente de este producto';
            header('Location: ' . BASE_URL . 'index.php?controller=caja&action=mostrar');
            exit;
        }

        //agregamos el producto al carrito
        Carrito::agregar($idProducto, $producto['nombre_producto'], $producto['precio_unitario'], $cantidad);

        //volvemos a la caja
        header('Location: ' . BASE_URL . 'index.php?controller=caja&action=mostrar');
        exit;
    }

    //quita un producto del carrito
    public function quitar(){
        //inicia la sesión
        if (session_status() === PHP_SESSION_NONE) session_start();

        //verifica login
        $userDNI = isset($_SESSION['UsuarioDni']) ? $_SESSION['UsuarioDni'] : '';
        if ($userDNI === '') {
            header('Location: ' . BASE_URL . 'index.php?controller=empleado&action=login');
            exit;
        }

        //inicia el carrito
        Carrito::iniciar();

        //recibe el id del producto a quitar
        $idProducto = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        //si el id es válido, lo quitamos
        if ($idProducto > 0) {
            Carrito::quitar($idProducto);
        }

        //volvemos a la caja
        header('Location: ' . BASE_URL . 'index.php?controller=caja&action=mostrar');
        exit;
    }

    //vacía todo el carrito
    public function vaciar(){
        //inicia la sesión
        if (session_status() === PHP_SESSION_NONE) session_start();

        //inicia el carrito y lo vacía
        Carrito::iniciar();
        Carrito::vaciar();

        //volvemos a la caja
        header('Location: ' . BASE_URL . 'index.php?controller=caja&action=mostrar');
        exit;
    }

    //confirma la venta: inserta en BD y redirige a la boleta
    //confirma la venta: crea la boleta y su detalle
        public function confirmar(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        $userDNI = isset($_SESSION['UsuarioDni']) ? $_SESSION['UsuarioDni'] : '';
        if ($userDNI === '') {
            header('Location: ' . BASE_URL . 'index.php?controller=empleado&action=login');
            exit;
        }

        $clienteDNI = isset($_POST['cliente']) ? trim($_POST['cliente']) : '';
        $carritoJson = isset($_POST['carrito']) ? $_POST['carrito'] : '';
        
        // Decodificar el carrito enviado desde JS
        $items = json_decode($carritoJson, true);

        if ($clienteDNI === '' || empty($items)) {
            header('Location: ' . BASE_URL . 'index.php?controller=caja&action=mostrar');
            exit;
        }

        $pdto     = new Producto();
        $boleta   = new Boleta();
        $venta    = new Venta();

        // Calcular total
        $total = 0;
        foreach ($items as $item) {
            $total += ($item['precio'] * $item['cantidad']);
        }

        $fecha = date("Y-m-d H:i:s");

        // 1. Insertar cabecera
        $id_boleta = $boleta->insertar($clienteDNI, $userDNI, $total, $fecha);

        // 2. Insertar detalle y descontar stock
        foreach ($items as $item) {
            $pdto->descontar_stock($item['id'], $item['cantidad']);
            $venta->insertar($id_boleta, $item['id'], $item['cantidad'], $item['precio']);
        }

        // 3. Redirigir a la boleta generada (Ticket Digital)
        header('Location: ' . BASE_URL . 'index.php?controller=boleta&action=mostrar&id=' . $id_boleta);
        exit;
    }
}