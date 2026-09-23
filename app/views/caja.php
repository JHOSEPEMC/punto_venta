<?php include __DIR__ . '/partials/header.php'; ?>
<?php include __DIR__ . '/partials/nav.php'; ?>

<div class="container mt-4">
    <h2>Caja</h2>
    <form action="<?= BASE_URL ?>index.php?controller=caja&action=mostrar" method="POST">
        <span>Cliente (DNI): </span><br>
        <input class='i_s' type="text" required placeholder="12345678" name="ClienteDni"><br><br>

        <span>Producto (ID): </span><br>
        <input class='i_s' type="text" required placeholder="1" name="IdProducto"><br><br>

        <span>Cantidad: </span><br>
        <input class='retiro_f' type="number" required placeholder="1" name="Cantidad"><br><br>

        <button type="submit" class="i_s">Agregar</button>
    </form>

    <?php if (!empty($mensajeError)): ?>
        <h3><?= htmlspecialchars($mensajeError) ?></h3>
    <?php endif; ?>

    <?php if (isset($_SESSION['totalCarrito']) && $_SESSION['totalCarrito'] > 0): ?>
        <br><hr>
        <h2 style="background-color: rgb(255, 100, 0);">
            TOTAL COMPRA: S/ <?= $_SESSION['totalCarrito'] ?>
        </h2>
    <?php endif; ?>

    <?php if ($ventaRegistrada): ?>
        <h4>
            <?= $empleado->ob_nombre() ?>:<br>
            A registrado una venta con el cliente: <hr>
            <?= htmlspecialchars($nombreCliente) ?>
            | <a href="<?= BASE_URL ?>index.php?controller=ventas&action=mostrar">Observar venta</a>
        </h4>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>