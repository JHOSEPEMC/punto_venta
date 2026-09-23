<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/nav.php'; ?>

<div class="container mt-4">
    <h2>Editar Cliente</h2>

    <form action="<?= BASE_URL ?>index.php?controller=adminPanel&action=editarCliente" method="POST">
        <input type="hidden" name="dni" value="<?= htmlspecialchars($cliente['dni_cliente']) ?>">

        <span>DNI (no editable): </span><br>
        <input class='i_s' type="text" disabled value="<?= htmlspecialchars($cliente['dni_cliente']) ?>"><br><br>

        <span>Nombre y apellido: </span><br>
        <input class='i_s' type="text" required name="nombre" value="<?= htmlspecialchars($cliente['nombre_apellido']) ?>"><br><br>

        <span>Teléfono: </span><br>
        <input class='i_s' type="text" required name="telefono" value="<?= htmlspecialchars($cliente['telefono']) ?>"><br><br>

        <button type="submit" class="i_s">Actualizar Cliente</button>
    </form>

    <?php if ($mensaje): ?>
        <div class="alert alert-<?= $tipo ?> mt-3"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <br>
    <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=listarClientes">← Volver al listado</a>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>