<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/nav.php'; ?>

<div class="container mt-4">
    <h2>Agregar Cliente</h2>

    <form action="<?= BASE_URL ?>index.php?controller=adminPanel&action=agregarCliente" method="POST">
        <span>DNI del cliente: </span><br>
        <input class='i_s' type="text" required placeholder="Ej: 12345678" name="dni"><br><br>

        <span>Nombre y apellido: </span><br>
        <input class='i_s' type="text" required placeholder="Ej: Juan Pérez" name="nombre"><br><br>

        <span>Teléfono: </span><br>
        <input class='i_s' type="text" required placeholder="Ej: 999888777" name="telefono"><br><br>

        <button type="submit" class="i_s">Guardar Cliente</button>
    </form>

    <?php if ($mensaje): ?>
        <div class="alert alert-<?= $tipo ?> mt-3"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <br>
    <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=listarClientes">← Volver al listado</a>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>