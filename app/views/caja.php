<?php include __DIR__ . '/partials/header.php'; ?>
<?php include __DIR__ . '/partials/nav.php'; ?>

<div class="container mt-4">
    <h2>Caja</h2>

    <?php if (!empty($mensajeCaja)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($mensajeCaja) ?></div>
    <?php endif; ?>

    <!--formulario para seleccionar cliente y agregar productos-->
    <form action="<?= BASE_URL ?>index.php?controller=caja&action=agregar" method="POST">
        <div class="row">
            <div class="col-md-4">
                <label>Cliente:</label>
                <select class="form-control" name="ClienteDni" required>
                    <option value="">-- Selecciona cliente --</option>
                    <?php foreach ($clientes as $c): ?>
                        <option value="<?= $c['dni_cliente'] ?>" <?= $clienteActual == $c['dni_cliente'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['nombre_apellido']) ?> (<?= $c['dni_cliente'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label>Producto:</label>
                <select class="form-control" name="IdProducto" required>
                    <option value="">-- Selecciona producto --</option>
                    <?php foreach ($productos as $p): ?>
                        <option value="<?= $p['id_producto'] ?>">
                            <?= htmlspecialchars($p['nombre_producto']) ?> - S/. <?= $p['precio_unitario'] ?>
                            (stock: <?= $p['stock'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2">
                <label>Cantidad:</label>
                <input class="form-control" type="number" name="Cantidad" min="1" value="1" required>
            </div>

            <div class="col-md-2">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-naranja form-control">Agregar</button>
            </div>
        </div>
    </form>

    <hr>

    <!--tabla del carrito-->
    <?php if (!empty($items)): ?>
        <h4>Productos en el carrito</h4>
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                    <th>Quitar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nombre']) ?></td>
                        <td><?= $item['cantidad'] ?></td>
                        <td>S/. <?= number_format($item['precio'], 2) ?></td>
                        <td>S/. <?= number_format($item['precio'] * $item['cantidad'], 2) ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>index.php?controller=caja&action=quitar&id=<?= $item['id_producto'] ?>"
                                class="btn btn-danger btn-sm">X</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Total: S/. <?= number_format($total, 2) ?></h3>

        <!--botones de confirmar y vaciar-->
        <a href="<?= BASE_URL ?>index.php?controller=caja&action=confirmar"
            class="btn btn-success"
            onclick="return confirm('¿Confirmar la venta?');">Confirmar Venta</a>
        <a href="<?= BASE_URL ?>index.php?controller=caja&action=vaciar"
            class="btn btn-warning"
            onclick="return confirm('¿Vaciar el carrito?');">Vaciar Carrito</a>

    <?php else: ?>
        <p>No hay productos en el carrito.</p>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>