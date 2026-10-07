<?php include __DIR__ . '/partials/header.php'; ?>
<?php include __DIR__ . '/partials/nav.php'; ?>

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
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?= $producto['id_producto'] ?></td>
                        <td><?= htmlspecialchars($producto['nombre_producto']) ?></td>
                        <td><?= htmlspecialchars($producto['nombre_catg']) ?></td>
                        <td>S/. <?= htmlspecialchars($producto['precio_unitario']) ?></td>
                        <td><?= number_format($producto['stock'], 0) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>