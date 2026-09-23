<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/nav.php'; ?>

<div class="container mt-4">
    <h2>Listado de Clientes (Admin)</h2>

    <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=agregarCliente" class="btn btn-success mb-3">+ Agregar Cliente</a>
    <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=mostrar" class="btn btn-secondary mb-3" style="background-color: orange; border-color: darkorange;">← Volver al panel</a>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th style="background-color: orange;">DNI</th>
                    <th style="background-color: orange;">Nombre</th>
                    <th style="background-color: orange;">Teléfono</th>
                    <th style="background-color: orange;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $c): ?>
                    <tr>
                        <td><?= htmlspecialchars($c['dni_cliente']) ?></td>
                        <td><?= htmlspecialchars($c['nombre_apellido']) ?></td>
                        <td><?= htmlspecialchars($c['telefono']) ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=editarCliente&dni=<?= $c['dni_cliente'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=eliminarCliente&dni=<?= $c['dni_cliente'] ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('¿Seguro que quieres eliminar este cliente? Sus ventas NO se eliminarán.');">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>