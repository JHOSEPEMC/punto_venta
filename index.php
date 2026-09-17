<?php 
include('vista/partials/header.php');
include('vista/partials/navIndex.php');
require_once('source/Main.php');
require_once('source/models/Conexion.php');
require_once('source/models/Producto.php');

$inicio = new Main();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Punto de Venta Basico</title>
    <link rel="icon" href="assets/icono.webp">
    <link href="vista/desing.css" rel="stylesheet">
</head>
<body>
    <h1>💸 Sistema de Punto de Venta 💸</h1>

    <div>
        <img src="assets/icono-transparent.webp" alt="Logo de Punto Venta">
    </div>
    
</body>
</html>
<?php 
include('vista/partials/footer.php');
?>