<?php
class AdminPanelControlador {

    //Verifica que haya admin logueado. Si no, redirige.
    private function verificar_sesion(){
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['AdminDni'])) {
            header('Location: ' . BASE_URL . 'index.php?controller=adminLogin&action=iniciar_sesion');
            exit;
        }
    }

    //═══════════ PANEL PRINCIPAL ═══════════
    public function mostrar(){
        $this->verificar_sesion();
        require_once APP_PATH . '/views/admin/panel.php';
    }

    //═══════════ PRODUCTOS ═══════════

    public function listarProductos(){
        $this->verificar_sesion();
        require_once APP_PATH . '/models/Conexion.php';
        require_once APP_PATH . '/models/Producto.php';

        $pdto = new Producto();
        $productos = $pdto->obtener_todos();

        require_once APP_PATH . '/views/admin/producto_listar.php';
    }

    public function agregarProducto(){
        $this->verificar_sesion();
        require_once APP_PATH . '/models/Conexion.php';
        require_once APP_PATH . '/models/Producto.php';

        $pdto = new Producto();
        $categorias = $pdto->obtener_categorias();
        $mensaje = '';
        $tipo = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre']);
            $idCat  = (int)$_POST['id_categoria'];
            $precio = (float)$_POST['precio'];
            $stock  = (int)$_POST['stock'];

            if ($nombre === '' || $idCat === 0) {
                $mensaje = 'Todos los campos son obligatorios';
                $tipo = 'danger';
            } elseif ($pdto->existe_nombre($nombre)) {
                $mensaje = 'Ya existe un producto con ese nombre';
                $tipo = 'warning';
            } else {
                if ($pdto->insertar($nombre, $idCat, $precio, $stock)) {
                    $mensaje = 'Producto agregado correctamente';
                    $tipo = 'success';
                } else {
                    $mensaje = 'Error al agregar el producto';
                    $tipo = 'danger';
                }
            }
        }

        require_once APP_PATH . '/views/admin/producto_agregar.php';
    }

    public function editarProducto(){
        $this->verificar_sesion();
        require_once APP_PATH . '/models/Conexion.php';
        require_once APP_PATH . '/models/Producto.php';

        $pdto = new Producto();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : (int)$_POST['id'];

        $producto = $pdto->obtener_por_id($id);
        if (!$producto) {
            header('Location: ' . BASE_URL . 'index.php?controller=adminPanel&action=listarProductos');
            exit;
        }

        $categorias = $pdto->obtener_categorias();
        $mensaje = '';
        $tipo = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre']);
            $idCat  = (int)$_POST['id_categoria'];
            $precio = (float)$_POST['precio'];
            $stock  = (int)$_POST['stock'];

            if ($nombre === '' || $idCat === 0) {
                $mensaje = 'Todos los campos son obligatorios';
                $tipo = 'danger';
            } else {
                if ($pdto->actualizar($id, $nombre, $idCat, $precio, $stock)) {
                    $mensaje = 'Producto actualizado correctamente';
                    $tipo = 'success';
                    $producto = $pdto->obtener_por_id($id);
                } else {
                    $mensaje = 'Error al actualizar';
                    $tipo = 'danger';
                }
            }
        }

        require_once APP_PATH . '/views/admin/producto_editar.php';
    }

    public function eliminarProducto(){
        $this->verificar_sesion();
        require_once APP_PATH . '/models/Conexion.php';
        require_once APP_PATH . '/models/Producto.php';

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            $pdto = new Producto();
            $pdto->eliminar($id);
        }

        header('Location: ' . BASE_URL . 'index.php?controller=adminPanel&action=listarProductos');
        exit;
    }

    //═══════════ CLIENTES ═══════════

    public function listarClientes(){
        $this->verificar_sesion();
        require_once APP_PATH . '/models/Conexion.php';
        require_once APP_PATH . '/models/Cliente.php';

        $cli = new Cliente();
        $clientes = $cli->obtener_todos();

        require_once APP_PATH . '/views/admin/cliente_listar.php';
    }

    public function agregarCliente(){
        $this->verificar_sesion();
        require_once APP_PATH . '/models/Conexion.php';
        require_once APP_PATH . '/models/Cliente.php';

        $cli = new Cliente();
        $mensaje = '';
        $tipo = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dni      = trim($_POST['dni']);
            $nombre   = trim($_POST['nombre']);
            $telefono = trim($_POST['telefono']);

            if ($dni === '' || $nombre === '' || $telefono === '') {
                $mensaje = 'Todos los campos son obligatorios';
                $tipo = 'danger';
            } elseif ($cli->existe($dni)) {
                $mensaje = 'Ya existe un cliente con ese DNI';
                $tipo = 'warning';
            } else {
                if ($cli->insertar($dni, $nombre, $telefono)) {
                    $mensaje = 'Cliente agregado correctamente';
                    $tipo = 'success';
                } else {
                    $mensaje = 'Error al agregar el cliente';
                    $tipo = 'danger';
                }
            }
        }

        require_once APP_PATH . '/views/admin/cliente_agregar.php';
    }

    public function editarCliente(){
        $this->verificar_sesion();
        require_once APP_PATH . '/models/Conexion.php';
        require_once APP_PATH . '/models/Cliente.php';

        $cli = new Cliente();
        $dni = isset($_GET['dni']) ? trim($_GET['dni']) : trim($_POST['dni']);

        $cliente = $cli->obtener_por_dni($dni);
        if (!$cliente) {
            header('Location: ' . BASE_URL . 'index.php?controller=adminPanel&action=listarClientes');
            exit;
        }

        $mensaje = '';
        $tipo = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre   = trim($_POST['nombre']);
            $telefono = trim($_POST['telefono']);

            if ($nombre === '' || $telefono === '') {
                $mensaje = 'Todos los campos son obligatorios';
                $tipo = 'danger';
            } else {
                if ($cli->actualizar($dni, $nombre, $telefono)) {
                    $mensaje = 'Cliente actualizado correctamente';
                    $tipo = 'success';
                    $cliente = $cli->obtener_por_dni($dni);
                } else {
                    $mensaje = 'Error al actualizar';
                    $tipo = 'danger';
                }
            }
        }

        require_once APP_PATH . '/views/admin/cliente_editar.php';
    }

    public function eliminarCliente(){
        $this->verificar_sesion();
        require_once APP_PATH . '/models/Conexion.php';
        require_once APP_PATH . '/models/Cliente.php';

        $dni = isset($_GET['dni']) ? trim($_GET['dni']) : '';
        if ($dni !== '') {
            $cli = new Cliente();
            $cli->eliminar($dni);
        }

        header('Location: ' . BASE_URL . 'index.php?controller=adminPanel&action=listarClientes');
        exit;
    }
}