<?php
class CajaControlador {
    //Muestra el formulario y procesa la venta
    public function mostrar(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        require_once APP_PATH . '/models/Conexion.php';
        require_once APP_PATH . '/models/Empleado.php';

        //Verificar login
        $userDNI = isset($_SESSION['UsuarioDni']) ? $_SESSION['UsuarioDni'] : '';
        if ($userDNI === '') {
            echo '<div class="container mt-4"><h2>Debes <a href="' . BASE_URL . 'index.php?controller=login&action=iniciar_sesion">Iniciar Sesion</a></h2></div>';
            return;
        }

        $bd = Conexion::conectar();
        $sql = "SELECT nombre_apellido, telefono FROM empleados WHERE dni_empleado = '$userDNI'";
        $fila = $bd->query($sql)->fetch_assoc();
        $empleado = new Empleado($fila['nombre_apellido'], $fila['telefono']);

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
            $sql = "SELECT * FROM clientes WHERE dni_cliente = '$clienteDNI'";
            if ($bd->query($sql)->fetch_assoc()['dni_cliente'] == null) {
                $mensajeError = 'No hay cliente registrado con este dni';
            }

            //Verificar producto
            $sql = "SELECT * FROM productos WHERE id_producto = $idProducto";
            $productoFila = $bd->query($sql)->fetch_assoc();
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

                //Actualizar stock
                $bd->query("UPDATE productos SET stock = stock - '$cantidad' WHERE id_producto = '$idProducto'");

                //Insertar venta
                $fecha = date("Y/m/d");
                $sql = "INSERT INTO ventas(dni_empleado, dni_cliente, id_producto, cantidad, fecha_venta)
                        VALUES ('$userDNI', '$clienteDNI', '$idProducto', '$cantidad', '$fecha')";
                $bd->query($sql);

                $sql = "SELECT * FROM clientes WHERE dni_cliente = '$clienteDNI'";
                $nombreCliente = $bd->query($sql)->fetch_assoc()['nombre_apellido'];

                $ventaRegistrada = true;
            }
        }

        require_once APP_PATH . '/views/caja.php';
    }
}