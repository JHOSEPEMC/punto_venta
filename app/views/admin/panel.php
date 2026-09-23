<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/nav.php'; ?>

<div class="container mt-4">
    <h2>Panel de Administrador</h2>

    <div class="card mt-3">
        <div class="card-body">
            <h5>Bienvenido, <?= htmlspecialchars($_SESSION['AdminNombre']) ?></h5>
            <p>¿Qué deseas hacer?</p>

            <h6 class="mt-3">Productos</h6>
            <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=listarProductos" class="btn btn-primary" style="background-color: darkorange; border-color: black;">Ver productos</a>
            <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=agregarProducto" class="btn btn-success">Agregar producto</a>

            <h6 class="mt-3">Clientes</h6>
            <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=listarClientes" class="btn btn-primary" style="background-color: darkorange; border-color: black;">Ver clientes</a>
            <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=agregarCliente" class="btn btn-success">Agregar cliente</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>