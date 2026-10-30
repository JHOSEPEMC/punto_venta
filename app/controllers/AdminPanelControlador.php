<?php
class AdminPanelControlador {
    private function verificar_sesion(){
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['AdminDni'])) {
            header('Location: ' . BASE_URL . 'index.php?controller=admin&action=login');
            exit;
        }
    }

    public function mostrar(){
        $this->verificar_sesion();
        $adminNombre = $_SESSION['AdminNombre'] ?? 'Administrador';

        $boletaDAO = new BoletaDAO();
        $pdtoDAO = new ProductoDAO();
        $empDAO = new EmpleadoDAO();

        // Ventas de hoy (consulta rápida desde el DAO de boletas)
        $pdo = Conexion::conectar();
        $qb = new QueryBuilder();
        $fechaSql = $qb->fechaFormateada('fecha_emision', 'YYYY-MM-DD');
        $hoySql = $qb->fechaFormateada('CURRENT_DATE', 'YYYY-MM-DD'); // Adaptado
        $stmtHoy = $pdo->query("SELECT SUM(total) as total_hoy FROM boletas WHERE $fechaSql = $hoySql");
        $ventasHoy = $stmtHoy->fetch()['total_hoy'] ?? 0;

        $bajoStock = $pdo->query("SELECT COUNT(*) as b FROM productos WHERE stock <= 5")->fetch()['b'];
        $totalEmpleados = count($empDAO->obtener_todos());

        $graficaData = $boletaDAO->obtener_ingresos_por_dia();
        $labels = array_map(fn($r) => date('d/m', strtotime($r['dia'])), $graficaData);
        $valores = array_column($graficaData, 'total_dia');

        require_once APP_PATH . '/views/admin/panel.php';
    }

    public function listarProductos(){
        $this->verificar_sesion();
        $productos = (new ProductoDAO())->obtener_todos_con_categoria();
        require_once APP_PATH . '/views/admin/producto_listar.php';
    }

    public function agregarProducto(){
        $this->verificar_sesion();
        $pdtoDAO = new ProductoDAO();
        $categorias = $pdtoDAO->obtener_categorias();
        $mensaje = ''; $tipo = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre']);
            $idCat  = (int)$_POST['id_categoria'];
            $precio = (float)$_POST['precio'];
            $stock  = (int)$_POST['stock'];

            if ($nombre === '' || $idCat === 0) {
                $mensaje = 'Todos los campos son obligatorios'; $tipo = 'danger';
            } elseif ($pdtoDAO->existe_nombre($nombre)) {
                $mensaje = 'Ya existe un producto con ese nombre'; $tipo = 'warning';
            } else {
                if ($pdtoDAO->insertar($nombre, $idCat, $precio, $stock)) {
                    $mensaje = 'Producto agregado correctamente'; $tipo = 'success';
                } else {
                    $mensaje = 'Error al agregar el producto'; $tipo = 'danger';
                }
            }
        }
        require_once APP_PATH . '/views/admin/producto_agregar.php';
    }

    public function editarProducto(){
        $this->verificar_sesion();
        $pdtoDAO = new ProductoDAO();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : (int)$_POST['id'];
        
        $producto = $pdtoDAO->obtener_por_id($id);

        // 🚀 VALIDACIÓN: Si no hay producto, volvemos a la lista
        if (!$producto) { 
            header('Location: ' . BASE_URL . 'index.php?controller=adminPanel&action=listarProductos'); 
            exit; 
        }
        
        $categorias = $pdtoDAO->obtener_categorias();
        $mensaje = ''; $tipo = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre']);
            $idCat  = (int)$_POST['id_categoria'];
            $precio = (float)$_POST['precio'];
            $stock  = (int)$_POST['stock'];

            if ($nombre === '' || $idCat === 0) {
                $mensaje = 'Todos los campos son obligatorios'; $tipo = 'danger';
            } else {
                if ($pdtoDAO->actualizar($id, $nombre, $idCat, $precio, $stock)) {
                    $mensaje = 'Producto actualizado correctamente'; $tipo = 'success';
                    $producto = $pdtoDAO->obtener_por_id($id);
                } else {
                    $mensaje = 'Error al actualizar'; $tipo = 'danger';
                }
            }
        }
        require_once APP_PATH . '/views/admin/producto_editar.php';
    }

    public function eliminarProducto(){
        $this->verificar_sesion();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) (new ProductoDAO())->eliminar($id);
        header('Location: ' . BASE_URL . 'index.php?controller=adminPanel&action=listarProductos');
        exit;
    }

    public function listarClientes(){
        $this->verificar_sesion();
        $clientes = (new ClienteDAO())->obtener_todos();
        require_once APP_PATH . '/views/admin/cliente_listar.php';
    }

    public function agregarCliente(){
        $this->verificar_sesion();
        $cliDAO = new ClienteDAO();
        $mensaje = ''; $tipo = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dni = trim($_POST['dni']); $nombre = trim($_POST['nombre']); $telefono = trim($_POST['telefono']);
            if ($dni === '' || $nombre === '' || $telefono === '') {
                $mensaje = 'Todos los campos son obligatorios'; $tipo = 'danger';
            } elseif ($cliDAO->obtener_por_dni($dni)) {
                $mensaje = 'Ya existe un cliente con ese DNI'; $tipo = 'warning';
            } else {
                if ($cliDAO->insertar($dni, $nombre, $telefono)) {
                    $mensaje = 'Cliente agregado correctamente'; $tipo = 'success';
                } else {
                    $mensaje = 'Error al agregar el cliente'; $tipo = 'danger';
                }
            }
        }
        require_once APP_PATH . '/views/admin/cliente_agregar.php';
    }

    public function editarCliente(){
        $this->verificar_sesion();
        $cliDAO = new ClienteDAO();
        $dni = isset($_GET['dni']) ? trim($_GET['dni']) : trim($_POST['dni']);
        
        $cliente = $cliDAO->obtener_por_dni($dni);

        // 🚀 VALIDACIÓN: Si no hay cliente, volvemos a la lista
        if (!$cliente) { 
            header('Location: ' . BASE_URL . 'index.php?controller=adminPanel&action=listarClientes'); 
            exit; 
        }

        $mensaje = ''; $tipo = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre']); $telefono = trim($_POST['telefono']);
            if ($nombre === '' || $telefono === '') {
                $mensaje = 'Todos los campos son obligatorios'; $tipo = 'danger';
            } else {
                if ($cliDAO->actualizar($dni, $nombre, $telefono)) {
                    $mensaje = 'Cliente actualizado correctamente'; $tipo = 'success';
                    $cliente = $cliDAO->obtener_por_dni($dni);
                } else {
                    $mensaje = 'Error al actualizar'; $tipo = 'danger';
                }
            }
        }
        require_once APP_PATH . '/views/admin/cliente_editar.php';
    }

    public function eliminarCliente(){
        $this->verificar_sesion();
        $dni = isset($_GET['dni']) ? trim($_GET['dni']) : '';
        if ($dni !== '') (new ClienteDAO())->eliminar($dni);
        header('Location: ' . BASE_URL . 'index.php?controller=adminPanel&action=listarClientes');
        exit;
    }

    public function listarEmpleados(){
        $this->verificar_sesion();
        $empleados = (new EmpleadoDAO())->obtener_todos();
        require_once APP_PATH . '/views/admin/empleado_listar.php';
    }

    public function agregarEmpleado(){
        $this->verificar_sesion();
        $empDAO = new EmpleadoDAO();
        $mensaje = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dni = trim($_POST['dni']); $nombre = trim($_POST['nombre']); $telefono = trim($_POST['telefono']); $pass = trim($_POST['pass']);
            if ($dni === '' || $nombre === '' || $pass === '') {
                $mensaje = 'Todos los campos son obligatorios';
            } else {
                $mensaje = $empDAO->insertar($dni, $nombre, $telefono, $pass) ? 'Empleado registrado' : 'Error al registrar';
            }
        }
        require_once APP_PATH . '/views/admin/empleado_agregar.php';
    }

    public function eliminarEmpleado(){
        $this->verificar_sesion();
        $dni = isset($_GET['dni']) ? $_GET['dni'] : '';
        if ($dni !== '') (new EmpleadoDAO())->eliminar($dni);
        header('Location: ' . BASE_URL . 'index.php?controller=adminPanel&action=listarEmpleados');
        exit;
    }

    public function listarCajas(){
        $this->verificar_sesion();
        $cajas = (new CajaDAO())->obtener_todas_abiertas();
        require_once APP_PATH . '/views/admin/caja_listar.php';
    }

    public function historialCajas(){
        $this->verificar_sesion();
        $cajas = (new CajaDAO())->obtener_historial();
        require_once APP_PATH . '/views/admin/caja_historial.php';
    }

    public function asignarCaja(){
        $this->verificar_sesion();
        $empDAO = new EmpleadoDAO();
        $empleados = $empDAO->obtener_todos();
        $mensaje = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dni_empleado = $_POST['empleado'];
            $sencillo = (float)$_POST['sencillo'];
            $cajaDAO = new CajaDAO();
            $mensaje = $cajaDAO->asignarSencillo($dni_empleado, $sencillo) ? 'Caja asignada correctamente.' : 'Error al asignar la caja.';
        }
        require_once APP_PATH . '/views/admin/caja_asignar.php';
    }
}