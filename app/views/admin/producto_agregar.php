<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/nav.php'; ?>

<div class="container mt-4">
    <h2>Agregar Producto</h2>

    <form action="<?= BASE_URL ?>index.php?controller=adminPanel&action=agregarProducto" method="POST">
        <span>Nombre del producto: </span><br>
        <input class='i_s' type="text" required placeholder="Ej: Leche Gloria 1L" name="nombre"><br><br>

        <span>Categoría: </span><br>
        <select class='i_s' required name="id_categoria">
            <option value="">-- Selecciona --</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['id_categoria'] ?>"><?= htmlspecialchars($cat['nombre_catg']) ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <span>Precio unitario: </span><br>
        <input class='i_s' type="number" step="0.01" min="0" required name="precio"><br><br>

        <span>Stock inicial: </span><br>
        <input class='i_s' type="number" min="0" required name="stock"><br><br>

        <button type="submit" class="i_s">Guardar Producto</button>
    </form>

    <?php if ($mensaje): ?>
        <div class="alert alert-<?= $tipo ?> mt-3"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <br>
    <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=listarProductos">← Volver al listado</a>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>