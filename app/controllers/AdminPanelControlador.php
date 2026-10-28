<?php
class AdminPanelControlador {

    //Verifica que haya admin logueado. Si no, redirige.
    private function verificar_sesion(){
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['AdminDni'])) {
            header('Location: ' . BASE_URL . 'index.php?controller=admin&action=login');
            exit;
        }
    }

    //═══════════ PANEL PRINCIPAL ═══════════
    public function mostrar(){
        $this->verificar_sesion();

        $adminNombre = isset($_SESSION['AdminNombre']) ? $_SESSION['AdminNombre'] : 'Administrador';

        // 🚀 USAMOS LOS DAO
        $boletaDAO = new BoletaDAO();
        $pdtoDAO = new ProductoDAO();
        $empDAO = new EmpleadoDAO();
        $pdo = Conexion::conectar(); // Para las consultas rápidas del dashboard

        //1. Ventas totales de hoy
        $stmtHoy = $pdo->query("SELECT SUM(total) as total_hoy FROM boletas WHERE DATE(fecha_emision) = CURDATE()");
        $resHoy = $stmtHoy->fetch();
        $ventasHoy = $resHoy['total_hoy'] ?? 0;

        //2. Productos con bajo stock (menos de 5)
        $stmtStock = $pdo->query("SELECT COUNT(*) as bajo_stock FROM productos WHERE stock <= 5");
        $resStock = $stmtStock->fetch();
        $bajoStock = $resStock['bajo_stock'];

        //3. Empleados registrados
        $stmtEmp = $pdo->query("SELECT COUNT(*) as total_emp FROM empleados");
        $resEmp = $stmtEmp->fetch();
        $totalEmpleados = $resEmp['total_emp'];

        //4. Datos para la gráfica de ventas (últimos 7 días)
        $graficaData = $boletaDAO->obtener_ingresos_por_dia();
        $labels = [];
        $valores = [];
        // 🚀 foreach en lugar de while
        foreach ($graficaData as $row) {
            $labels[] = date('d/m', strtotime($row['dia']));
            $valores[] = $row['total_dia'];
        }

        require_once APP_PATH . '/views/admin/panel.php';
    }

    //═══════════ PRODUCTOS ═══════════
    public function listarProductos(){
        $this->verificar_sesion();
        $pdtoDAO = new ProductoDAO();
        $productos = $pdtoDAO->obtener_todos();
        require_once APP_PATH . '/views/admin/producto_listar.php';
    }

    public function agregarProducto(){
        $this->verificar_sesion();
        $pdtoDAO = new ProductoDAO();
        $categorias = $pdtoDAO->obtener_categorias();
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
            } elseif ($pdtoDAO->existe_nombre($nombre)) {
                $mensaje = 'Ya existe un producto con ese nombre';
                $tipo = 'warning';
            } else {
                if ($pdtoDAO->insertar($nombre, $idCat, $precio, $stock)) {
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
        $pdtoDAO = new ProductoDAO();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : (int)$_POST['id'];

        $producto = $pdtoDAO->obtener_por_id($id);
        if (!$producto) {
            header('Location: ' . BASE_URL . 'index.php?controller=adminPanel&action=listarProductos');
            exit;
        }

        $categorias = $pdtoDAO->obtener_categorias();
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
                if ($pdtoDAO->actualizar($id, $nombre, $idCat, $precio, $stock)) {
                    $mensaje = 'Producto actualizado correctamente';
                    $tipo = 'success';
                    $producto = $pdtoDAO->obtener_por_id($id);
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
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            $pdtoDAO = new ProductoDAO();
            $pdtoDAO->eliminar($id);
        }
        header('Location: ' . BASE_URL . 'index.php?controller=adminPanel&action=listarProductos');
        exit;
    }

    //═══════════ CLIENTES ═══════════
    public function listarClientes(){
        $this->verificar_sesion();
        $cliDAO = new ClienteDAO();
        $clientes = $cliDAO->obtener_todos();
        require_once APP_PATH . '/views/admin/cliente_listar.php';
    }

    public function agregarCliente(){
        $this->verificar_sesion();
        $cliDAO = new ClienteDAO();
        $mensaje = '';
        $tipo = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dni      = trim($_POST['dni']);
            $nombre   = trim($_POST['nombre']);
            $telefono = trim($_POST['telefono']);

            if ($dni === '' || $nombre === '' || $telefono === '') {
                $mensaje = 'Todos los campos son obligatorios';
                $tipo = 'danger';
            } elseif ($cliDAO->obtener_por_dni($dni)) { // 🚀 Cambiado a obtener_por_dni
                $mensaje = 'Ya existe un cliente con ese DNI';
                $tipo = 'warning';
            } else {
                if ($cliDAO->insertar($dni, $nombre, $telefono)) {
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
        $cliDAO = new ClienteDAO();
        $dni = isset($_GET['dni']) ? trim($_GET['dni']) : trim($_POST['dni']);

        $cliente = $cliDAO->obtener_por_dni($dni);
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
                // 🚀 Asegúrate de tener el método actualizar en ClienteDAO
                if ($cliDAO->actualizar($dni, $nombre, $telefono)) {
                    $mensaje = 'Cliente actualizado correctamente';
                    $tipo = 'success';
                    $cliente = $cliDAO->obtener_por_dni($dni);
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
        $dni = isset($_GET['dni']) ? trim($_GET['dni']) : '';
        if ($dni !== '') {
            $cliDAO = new ClienteDAO();
            $cliDAO->eliminar($dni);
        }
        header('Location: ' . BASE_URL . 'index.php?controller=adminPanel&action=listarClientes');
        exit;
    }

    //═══════════ EMPLEADOS ═══════════
    public function listarEmpleados(){
        $this->verificar_sesion();
        $empDAO = new EmpleadoDAO();
        $empleados = $empDAO->obtener_todos();
        require_once APP_PATH . '/views/admin/empleado_listar.php';
    }

    public function agregarEmpleado(){
        $this->verificar_sesion();
        $empDAO = new EmpleadoDAO();
        $mensaje = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dni = trim($_POST['dni']);
            $nombre = trim($_POST['nombre']);
            $telefono = trim($_POST['telefono']);
            $pass = trim($_POST['pass']);

            if ($dni === '' || $nombre === '' || $pass === '') {
                $mensaje = 'Todos los campos son obligatorios';
            } else {
                if ($empDAO->insertar($dni, $nombre, $telefono, $pass)) {
                    $mensaje = 'Empleado registrado';
                } else {
                    $mensaje = 'Error al registrar';
                }
            }
        }
        require_once APP_PATH . '/views/admin/empleado_agregar.php';
    }

    public function eliminarEmpleado(){
        $this->verificar_sesion();
        $dni = isset($_GET['dni']) ? $_GET['dni'] : '';
        if ($dni !== '') {
            $empDAO = new EmpleadoDAO();
            $empDAO->eliminar($dni);
        }
        header('Location: ' . BASE_URL . 'index.php?controller=adminPanel&action=listarEmpleados');
        exit;
    }

    //═══════════ CAJAS ═══════════
    public function listarCajas(){
        $this->verificar_sesion();
        $cajaDAO = new CajaDAO();
        $cajas = $cajaDAO->obtener_todas_abiertas();
        require_once APP_PATH . '/views/admin/caja_listar.php';
    }

    public function historialCajas(){
        $this->verificar_sesion();
        $cajaDAO = new CajaDAO();
        $cajas = $cajaDAO->obtener_historial();
        require_once APP_PATH . '/views/admin/caja_historial.php';
    }

    public function asignarCaja(){
        $this->verificar_sesion();
        $mensaje = '';
        
        // 🚀 USAMOS EL EMPLEADO DAO
        $empDAO = new EmpleadoDAO();
        $empleados = $empDAO->obtener_todos();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dni_empleado = $_POST['empleado'];
            $sencillo = (float)$_POST['sencillo'];
            
            $cajaDAO = new CajaDAO();
            
            // 🚀 USAMOS EL MÉTODO DEL DAO
            if ($cajaDAO->asignarSencillo($dni_empleado, $sencillo)) {
                $mensaje = 'Caja asignada correctamente. El empleado ya puede habilitarla.';
            } else {
                $mensaje = 'Error al asignar la caja.';
            }
        }
        require_once APP_PATH . '/views/admin/caja_asignar.php';
    }
}