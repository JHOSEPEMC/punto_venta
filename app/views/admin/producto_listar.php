<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/nav.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold m-0">Listado de Productos</h2>
        <div>
            <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=agregarProducto" class="btn btn-success shadow-sm">➕ Agregar Producto</a>
            <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=mostrar" class="btn btn-secondary shadow-sm">← Volver al panel</a>
        </div>
    </div>

    <div class="card p-3 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $p): ?>
                        <tr>
                            <td class="text-muted">#<?= $p->id ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($p->nombre) ?></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($p->nombre_categoria) ?></span></td>
                            <td><?= MONEDA ?> <?= number_format($p->precio, 2) ?></td>
                            <td>
                                <?php if($p->stock <= 5): ?>
                                    <span class="badge bg-danger"><?= $p->stock ?> (Bajo)</span>
                                <?php else: ?>
                                    <span class="badge bg-success"><?= $p->stock ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=editarProducto&id=<?= $p->id ?>" class="btn btn-sm btn-outline-warning" title="Editar">✏️</a>
                                <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=eliminarProducto&id=<?= $p->id ?>"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('¿Seguro que quieres eliminar este producto?');" title="Eliminar">🗑️</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>