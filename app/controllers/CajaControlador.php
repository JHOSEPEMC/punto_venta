<?php
class CajaControlador {

    //muestra la caja con el formulario y el carrito actual
    public function mostrar(){
        //inicia la sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) session_start();

        //verifica que el empleado esté logueado
        $userDNI = isset($_SESSION['UsuarioDni']) ? $_SESSION['UsuarioDni'] : '';
        if ($userDNI === '') {
            echo '<div class="container mt-4"><h2>Debes <a href="' . BASE_URL . 'index.php?controller=login&action=iniciar_sesion">Iniciar Sesion</a></h2></div>';
            return;
        }

        //inicia el carrito si no existe
        Carrito::iniciar();

        //instancia los modelos que vamos a usar
        $emp  = new Empleado();
        $cli  = new Cliente();
        $pdto = new Producto();

        //obtiene los datos del empleado logueado
        $empData = $emp->obtener_por_dni($userDNI);
        $empleado = new Empleado($empData['nombre_apellido'], $empData['telefono']);

        //obtiene la lista completa de clientes y productos
        $clientes = $cli->obtener_todos();
        $productos = $pdto->obtener_todos();

        //obtiene el cliente seleccionado actualmente (si hay)
        $clienteActual = isset($_SESSION['clienteDNI']) ? $_SESSION['clienteDNI'] : '';

        //obtiene los items del carrito y el total
        $items = Carrito::obtener();
        $total = Carrito::total();

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
            header('Location: ' . BASE_URL . 'index.php?controller=login&action=iniciar_sesion');
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
            header('Location: ' . BASE_URL . 'index.php?controller=login&action=iniciar_sesion');
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
    public function confirmar(){
        //inicia la sesión
        if (session_status() === PHP_SESSION_NONE) session_start();

        //verifica login
        $userDNI = isset($_SESSION['UsuarioDni']) ? $_SESSION['UsuarioDni'] : '';
        if ($userDNI === '') {
            header('Location: ' . BASE_URL . 'index.php?controller=login&action=iniciar_sesion');
            exit;
        }

        //inicia el carrito
        Carrito::iniciar();

        //recoge el cliente actual y los items
        $clienteDNI = isset($_SESSION['clienteDNI']) ? $_SESSION['clienteDNI'] : '';
        $items = Carrito::obtener();

        //si no hay cliente o no hay items, no se puede confirmar
        if ($clienteDNI === '' || empty($items)) {
            header('Location: ' . BASE_URL . 'index.php?controller=caja&action=mostrar');
            exit;
        }

        //instancia modelos
        $pdto  = new Producto();
        $venta = new Venta();

        //fecha actual
        $fecha = date("Y/m/d");

        //recorre los items: descuenta stock e inserta la venta
        foreach ($items as $item) {
            //descuenta el stock del producto
            $pdto->descontar_stock($item['id_producto'], $item['cantidad']);

            //inserta el registro de venta
            $venta->insertar($userDNI, $clienteDNI, $item['id_producto'], $item['cantidad'], $fecha);
        }

        //guarda los datos de la venta en sesión para la boleta
        $_SESSION['boleta'] = [
            'clienteDNI' => $clienteDNI,
            'items'      => $items,
            'total'      => Carrito::total(),
            'fecha'      => $fecha,
            'empleado'   => $userDNI,
        ];

        //vacía el carrito (ya se registró la venta)
        Carrito::vaciar();

        //redirige a la boleta en PDF
        header('Location: ' . BASE_URL . 'index.php?controller=boleta&action=resultado');
        exit;
    }
}