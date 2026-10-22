<?php
class AdminPanelControlador {

    //Verifica que haya admin logueado. Si no, redirige.
    private function verificar_sesion(){
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['AdminDni'])) {
            //redirige al nuevo controlador unificado
            header('Location: ' . BASE_URL . 'index.php?controller=admin&action=login');
            exit;
        }
    }

    //═══════════ PANEL PRINCIPAL ═══════════
    //muestra el panel principal del admin
    public function mostrar(){
        $this->verificar_sesion();

        //pasa el nombre del admin a la vista
        $adminNombre = isset($_SESSION['AdminNombre']) ? $_SESSION['AdminNombre'] : 'Administrador';

        //obtenemos datos para el dashboard
        $bd = Conexion::conectar();
        
        //1. Ventas totales de hoy
        $sqlHoy = "SELECT SUM(total) as total_hoy FROM boletas WHERE DATE(fecha_emision) = CURDATE()";
        $resHoy = $bd->query($sqlHoy)->fetch_assoc();
        $ventasHoy = $resHoy['total_hoy'] ?? 0;

        //2. Productos con bajo stock (menos de 5)
        $sqlStock = "SELECT COUNT(*) as bajo_stock FROM productos WHERE stock <= 5";
        $resStock = $bd->query($sqlStock)->fetch_assoc();
        $bajoStock = $resStock['bajo_stock'];

        //3. Empleados registrados
        $sqlEmp = "SELECT COUNT(*) as total_emp FROM empleados";
        $resEmp = $bd->query($sqlEmp)->fetch_assoc();
        $totalEmpleados = $resEmp['total_emp'];

        //4. Datos para la gráfica de ventas (últimos 7 días)
        $boletaModel = new Boleta();
        $graficaData = $boletaModel->obtener_ingresos_por_dia();
        $labels = [];
        $valores = [];
        while ($row = $graficaData->fetch_assoc()) {
            $labels[] = date('d/m', strtotime($row['dia']));
            $valores[] = $row['total_dia'];
        }

        require_once APP_PATH . '/views/admin/panel.php';
    }

    //═══════════ PRODUCTOS ═══════════

    public function listarProductos(){
        $this->verificar_sesion();

        $pdto = new Producto();
        $productos = $pdto->obtener_todos();

        require_once APP_PATH . '/views/admin/producto_listar.php';
    }

    public function agregarProducto(){
        $this->verificar_sesion();

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

        $cli = new Cliente();
        $clientes = $cli->obtener_todos();

        require_once APP_PATH . '/views/admin/cliente_listar.php';
    }

    public function agregarCliente(){
        $this->verificar_sesion();

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

        $dni = isset($_GET['dni']) ? trim($_GET['dni']) : '';
        if ($dni !== '') {
            $cli = new Cliente();
            $cli->eliminar($dni);
        }

        header('Location: ' . BASE_URL . 'index.php?controller=adminPanel&action=listarClientes');
        exit;
    }

    //═══════════ EMPLEADOS ═══════════
    public function listarEmpleados(){
        $this->verificar_sesion();
        $emp = new Empleado();
        $empleados = $emp->obtener_todos();
        require_once APP_PATH . '/views/admin/empleado_listar.php';
    }

    public function agregarEmpleado(){
        $this->verificar_sesion();
        $emp = new Empleado();
        $mensaje = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dni = trim($_POST['dni']);
            $nombre = trim($_POST['nombre']);
            $telefono = trim($_POST['telefono']);
            $pass = trim($_POST['pass']);

            if ($dni === '' || $nombre === '' || $pass === '') {
                $mensaje = 'Todos los campos son obligatorios';
            } else {
                if ($emp->insertar($dni, $nombre, $telefono, $pass)) {
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
            $emp = new Empleado();
            $emp->eliminar($dni);
        }
        header('Location: ' . BASE_URL . 'index.php?controller=adminPanel&action=listarEmpleados');
        exit;
    }

    //═══════════ CAJAS ═══════════
    // Control de cajas (Solo las que están activas)
    public function listarCajas(){
        $this->verificar_sesion();
        $cajaModel = new Caja();
        
        // Usamos el nuevo método de solo abiertas
        $cajas = $cajaModel->obtener_todas_abiertas();
        
        require_once APP_PATH . '/views/admin/caja_listar.php';
    }

    // NUEVO: Historial completo de cajas
    public function historialCajas(){
        $this->verificar_sesion();
        $cajaModel = new Caja();
        
        $cajas = $cajaModel->obtener_historial();
        
        require_once APP_PATH . '/views/admin/caja_historial.php';
    }

    public function asignarCaja(){
        $this->verificar_sesion();
        $mensaje = '';
        
        // 🚀 OBTENEMOS LA LISTA DE EMPLEADOS PARA EL SELECTOR
        $emp = new Empleado();
        $empleados = $emp->obtener_todos();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dni_empleado = $_POST['empleado'];
            $sencillo = (float)$_POST['sencillo'];
            
            $cajaModel = new Caja();
            
            if ($cajaModel->asignarSencillo($dni_empleado, $sencillo)) {
                $mensaje = 'Caja asignada correctamente. El empleado ya puede habilitarla.';
            } else {
                $mensaje = 'Error al asignar la caja.';
            }
        }
        
        // Pasamos la variable $empleados a la vista
        require_once APP_PATH . '/views/admin/caja_asignar.php';
    }
}