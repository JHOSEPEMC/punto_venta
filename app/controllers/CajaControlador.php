<?php
class CajaControlador {

    public function mostrar(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        //Verificar login del empleado
        $userDNI = isset($_SESSION['UsuarioDni']) ? $_SESSION['UsuarioDni'] : '';
        if ($userDNI === '') {
            echo '<div class="container mt-4"><h2>Debes <a href="' . BASE_URL . 'index.php?controller=login&action=iniciar_sesion">Iniciar Sesion</a></h2></div>';
            return;
        }

        //SQL ya NO está aquí. Todos usan modelos.
        $emp = new Empleado();
        $empData = $emp->obtener_por_dni($userDNI);
        $empleado = new Empleado($empData['nombre_apellido'], $empData['telefono']);

        $cli = new Cliente();
        $pdto = new Producto();
        $venta = new Venta();

        //Datos del formulario
        $clienteDNI = isset($_POST['ClienteDni']) ? trim($_POST['ClienteDni']) : '';
        $idProducto = isset($_POST['IdProducto']) ? (int)$_POST['IdProducto'] : 0;
        $cantidad   = isset($_POST['Cantidad'])   ? (int)$_POST['Cantidad']   : 1;

        $total = isset($_SESSION['totalCarrito']) ? $_SESSION['totalCarrito'] : 0;
        $mensajeError = '';
        $nombreCliente = '';
        $ventaRegistrada = false;

        //Procesar si se envió el formulario
        if ($clienteDNI !== '') {
            //Verificar cliente
            if (!$cli->existe($clienteDNI)) {
                $mensajeError = 'No hay cliente registrado con este dni';
            }

            //Verificar producto
            $productoFila = $pdto->obtener_por_id($idProducto);
            if (!$mensajeError && $productoFila == null) {
                $mensajeError = 'No hay producto con este ID';
            }

            //Verificar stock
            if (!$mensajeError && $cantidad > $productoFila['stock']) {
                $mensajeError = 'No hay stock suficiente de este producto';
            }

            //Procesar venta
            if (!$mensajeError) {
                $clientDniSesion = isset($_SESSION['clienteDNI']) ? $_SESSION['clienteDNI'] : '';
                if ($clientDniSesion !== $clienteDNI) {
                    $_SESSION['totalCarrito'] = 0;
                    $total = 0;
                    $_SESSION['clienteDNI'] = $clienteDNI;
                }

                $_SESSION['totalCarrito'] = $total + ($cantidad * (float)$productoFila['precio_unitario']);

                //Descontar stock
                $pdto->descontar_stock($idProducto, $cantidad);

                //Insertar venta
                $fecha = date("Y/m/d");
                $venta->insertar($userDNI, $clienteDNI, $idProducto, $cantidad, $fecha);

                //Obtener nombre del cliente
                $clienteData = $cli->obtener_por_dni($clienteDNI);
                $nombreCliente = $clienteData['nombre_apellido'];

                $ventaRegistrada = true;
            }
        }

        require_once APP_PATH . '/views/caja.php';
    }
}