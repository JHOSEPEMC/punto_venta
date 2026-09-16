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
    <h2>LISTADO DE PRODUCTOS</h2>
    <div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>PRODUCTO</th>
            <th>CATEGORIA</th>
            <th>PRECIO</th>
            <th>STOCK</th>
        </tr>
        </thead>
        <tbody>
        <?php 
        // para conectarse con la base de datos :SillyDev:
        require_once '../source/models/Conexion.php'; // "importamos" el database.php
        $bd = Conexion::conectar(); //conectamos
        $sql = "
        SELECT *
        FROM productos;
        "; #Consulta para el sql :SillyDev:
        $productos = $bd->query($sql); //hacer la consulta a nuestra base de datos con el $sql
        //$usuarios = $resultado->fetch_assoc()['COUNT(usuario)'];
        foreach ($productos as $producto): ?>
        <tr>
            <td><?= $producto['id_producto'] ?></td>
            <td><?= htmlspecialchars($producto['nombre_producto']) ?></td>
            <td><?= htmlspecialchars($producto['id_categoria']) ?></td>
            <td><?= htmlspecialchars($producto['precio_unitario']) ?></td>
            <td>$<?= number_format($producto['stock'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>
</body>
</html>

<?php include 'partials/footer.php'; ?>