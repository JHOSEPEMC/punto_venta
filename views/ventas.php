<?php include 'partials/header.php'; ?>
<?php include 'partials/nav.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="desing.css">
</head>
<body>
    <div class="container mt-4">
    <h2>VENTAS REALIZADAS</h2>
    <div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
        <tr>
            <th style="background-color: orange;">ID</th>
            <th style="background-color: orange;">EMPLEADO</th>
            <th style="background-color: orange;">CLIENTE</th>
            <th style="background-color: orange;">PRODUCTO</th>
            <th style="background-color: orange;">CANTIDAD</th>
            <th style="background-color: orange;">FECHA</th>
        </tr>
        </thead>
        <tbody>
        <?php 
        session_start(); //Inicia la Sesion LOL
        $carritoTotal = isset($_SESSION['totalCarrito']) ? $_SESSION['totalCarrito'] : null; //si carrito existe ya en session, lo vaceamos
        if($carritoTotal != null){$_SESSION['totalCarrito'] = null;}
        
        // para conectarse con la base de datos :SillyDev:
        require_once '../models/Conexion.php';
        $bd = Conexion::conectar();
        $sql = "
        SELECT ventas.id_venta, empleados.nombre_apellido as empleado, clientes.nombre_apellido as cliente,
        productos.nombre_producto as producto, ventas.cantidad, ventas.fecha_venta
        FROM ventas
        INNER JOIN productos
        ON ventas.id_producto = productos.id_producto
        INNER JOIN empleados
        ON ventas.dni_empleado = empleados.dni_empleado
        INNER JOIN clientes
        ON ventas.dni_cliente = clientes.dni_cliente
        "; #Consulta para el sql :SillyDev:
        $ventas = $bd->query($sql); //hacer la consulta a nuestra base de datos con el $sql
        foreach ($ventas as $venta): ?>
        <tr>
            <td><?= $venta['id_venta'] ?></td>
            <td><?= htmlspecialchars($venta['empleado']) ?></td>
            <td><?= htmlspecialchars($venta['cliente']) ?></td>
            <td><?= htmlspecialchars($venta['producto']) ?></td>
            <td><?= htmlspecialchars($venta['cantidad']) ?></td>
            <td><?= htmlspecialchars($venta['fecha_venta']) ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>
</body>
</html>

<?php include 'partials/footer.php'; ?>