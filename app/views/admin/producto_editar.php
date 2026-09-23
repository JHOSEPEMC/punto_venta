<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/nav.php'; ?>

<div class="container mt-4">
    <h2>Editar Producto</h2>

    <form action="<?= BASE_URL ?>index.php?controller=adminPanel&action=editarProducto" method="POST">
        <input type="hidden" name="id" value="<?= $producto['id_producto'] ?>">

        <span>Nombre del producto: </span><br>
        <input class='i_s' type="text" required name="nombre" value="<?= htmlspecialchars($producto['nombre_producto']) ?>"><br><br>

        <span>Categoría: </span><br>
        <select class='i_s' required name="id_categoria">
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['id_categoria'] ?>"
                    <?= $cat['id_categoria'] == $producto['id_categoria'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['nombre_catg']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <span>Precio unitario: </span><br>
        <input class='i_s' type="number" step="0.01" min="0" required name="precio" value="<?= $producto['precio_unitario'] ?>"><br><br>

        <span>Stock: </span><br>
        <input class='i_s' type="number" min="0" required name="stock" value="<?= $producto['stock'] ?>"><br><br>

        <button type="submit" class="i_s">Actualizar Producto</button>
    </form>

    <?php if ($mensaje): ?>
        <div class="alert alert-<?= $tipo ?> mt-3"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <br>
    <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=listarProductos">← Volver al listado</a>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>