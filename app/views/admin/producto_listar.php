<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/nav.php'; ?>

<div class="container mt-4">
    <h2>Listado de Productos (Admin)</h2>

    <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=agregarProducto" class="btn btn-success mb-3">+ Agregar Producto</a>
    <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=mostrar" class="btn btn-secondary mb-3" style="background-color: orange; border-color: darkorange;">← Volver al panel</a>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th style="background-color: orange;">ID</th>
                    <th style="background-color: orange;">Nombre</th>
                    <th style="background-color: orange;">ID Categoría</th>
                    <th style="background-color: orange;">Precio</th>
                    <th style="background-color: orange;">Stock</th>
                    <th style="background-color: orange;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $p): ?>
                    <tr>
                        <td><?= $p['id_producto'] ?></td>
                        <td><?= htmlspecialchars($p['nombre_producto']) ?></td>
                        <td><?= $p['id_categoria'] ?></td>
                        <td>S/. <?= $p['precio_unitario'] ?></td>
                        <td><?= $p['stock'] ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=editarProducto&id=<?= $p['id_producto'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=eliminarProducto&id=<?= $p['id_producto'] ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('¿Seguro que quieres eliminar este producto?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>