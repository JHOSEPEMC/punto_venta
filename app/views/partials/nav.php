<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: rgb(255, 100, 0);">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>index.php?controller=home&action=mostrar">PUNTO DE VENTA</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?controller=caja&action=mostrar">Caja</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?controller=ventas&action=mostrar">Ventas</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?controller=inventario&action=mostrar">Listado de Productos</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?controller=login&action=iniciar_sesion">Login</a></li>
            </ul>
        </div>
    </div>
</nav>