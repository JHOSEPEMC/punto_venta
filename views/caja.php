<?php include('partials/header.php'); ?>
<?php include('partials/nav.php');
    require_once('../models/Empleado.php');
    require_once '../models/Conexion.php'; // "importamos" la conexion.php

    session_start(); //Inicia la Sesion LOL
    
    //El bucle que nos permitira tener el total anterior
    $total = isset($_SESSION['totalCarrito']) ? $_SESSION['totalCarrito'] : 0;

    //Si el empleado ahun no esta logueado no puede hacer venta
    $userDNI = isset($_SESSION['UsuarioDni']) ? $_SESSION['UsuarioDni'] : '';
    if($userDNI == ''){
        echo('<h2>Debes <a href="login.php">Iniciar Sesion</a></h2>');
        return;
    }
    $bd = Conexion::conectar(); //conectamos
    $sql = "SELECT nombre_apellido, telefono FROM empleados WHERE dni_empleado = '$userDNI'"; 
    #Consulta para el sql :SillyDev:
    $nombre = $bd->query($sql)->fetch_assoc()['nombre_apellido'];
    $telefono = $bd->query($sql)->fetch_assoc()['telefono'];
    $empleado = new Empleado($nombre, $telefono);
?>
<div class = 'container mt-4'>
    <h2>caja</h2>
    <form action="" method="POST"> 
        <!--Esto es para venta de productos-->
        <span for="ClienteDni">Cliente (DNI): </span> <br>
        <input class = 'i_s' type="text" id="ClienteDni" required 
        placeholder = "12345678" name="ClienteDni"><br><br> 
    
        <span for="IdProducto">Producto (ID): </span> <br>
        <input class = 'i_s' type="text" id="IdProducto" required 
        placeholder = "1" name="IdProducto"><br><br>

        <span for="Cantidad">Cantidad: </span> <br>
        <input class = 'retiro_f' type="number" id="Cantidad" required 
        placeholder = "1" name="Cantidad"><br><br>

        <button type="submit" class = "i_s">
            Agregar
        </button>
    </form>
</div>
<?php 
    //variables obtenidas de los inputs
    $clienteDNI = isset($_POST['ClienteDni']) ? $_POST['ClienteDni'] : '';
    $idProducto = isset($_POST['IdProducto']) ? (int)$_POST['IdProducto'] : '';
    $cantidad = isset($_POST['Cantidad']) ? $_POST['Cantidad'] : 1;

    if($clienteDNI == ''){
        return;
    }

    //CONSULTA para verificar existencia de dniCLiente
    $sql = "SELECT * FROM clientes WHERE dni_cliente = '$clienteDNI'";
    if(isset($bd->query($sql)->fetch_assoc()['dni_cliente']) == null){
        echo('<h2>No hay cliente registrado con este dni</h2>');
        return;
    }

    //CONSULTA para verificar existencia del id del producto
    $sql = "SELECT * FROM productos WHERE id_producto = $idProducto";
    if(isset($bd->query($sql)->fetch_assoc()['id_producto']) == null){
        echo('<h2>No hay producto con este ID</h2>');
        return;
    }
    
    //CONSULTA para verificar si la cantidad no sobrepasa el stock
    $stockDisponible = $bd->query($sql)->fetch_assoc()['stock'];
    if($cantidad > $stockDisponible){
        echo('<h3>No hay stock suficiente de deste producto | <a href="inventario.php">Observar stock</a></h3>');
        return;
    }
    
    //Para resetear el total segun cambio de cliente
    $clientDni = isset($_SESSION['clienteDNI']) ? $_SESSION['clienteDNI'] : '';
    if($clientDni != $clienteDNI){
        $_SESSION['totalCarrito'] = 0;
        $total = 0;
        $_SESSION['clienteDNI'] = $clienteDNI;
    }
    
    //Total de la compra del cliente
    $_SESSION['totalCarrito'] = $total + ($cantidad * (float)$bd->query($sql)->fetch_assoc()['precio_unitario']);
    echo('<br><hr><h2 style = "background-color: rgb(255, 100, 0);">TOTAL COMPRA: S/ '. $_SESSION['totalCarrito']);

    //Actualizar datos del stock del producto
    $sql = "UPDATE productos
            SET stock = stock - '$cantidad'
            WHERE id_producto = '$idProducto'";
    $bd->query($sql);
    $fecha = date("Y/m/d");

    
    //Comando para insertar datos para la tabla ventas
    $sql = "
    INSERT INTO ventas(dni_empleado, dni_cliente, id_producto, cantidad, fecha_venta) VALUES
    ('$userDNI', '$clienteDNI', '$idProducto', '$cantidad', '$fecha' )";
    $bd->query($sql);
    $sql = "SELECT * FROM clientes WHERE dni_cliente = '$clienteDNI'";
    $nombreCliente = $bd->query($sql)->fetch_assoc()['nombre_apellido'];

    //Imprimir resultado de quien hiso la venta y a que cliente.
    echo('<h4> '.$empleado->ob_nombre(). ': <br>A registrado una venta con el cliente: <hr>'.$nombreCliente.
    ' | <a href="ventas.php">Observar venta</a></h4>');
?>
<?php include 'partials/footer.php'; ?>
</div>