<?php include __DIR__ . '/partials/header.php'; ?>
<?php include __DIR__ . '/partials/nav.php'; ?>

<div class="container mt-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold text-naranja">💸 Punto de Venta</h1>
        <p class="text-muted fs-5">Bienvenido al sistema de gestión Minimarket</p>
        <img src="<?= BASE_URL ?>assets/icono-transparent.webp" alt="Logo" style="max-width: 175px;">
    </div>

    <div class="row justify-content-center g-4">
        <!-- Caja -->
        <div class="col-6 col-md-3">
            <a href="<?= BASE_URL ?>index.php?controller=caja&action=mostrar" class="text-decoration-none">
                <div class="card text-center p-4 h-100 shadow-sm">
                    <div class="display-4 mb-2">🛒</div>
                    <h5 class="fw-bold text-dark">Caja</h5>
                </div>
            </a>
        </div>
        
        <!-- Ventas -->
        <div class="col-6 col-md-3">
            <a href="<?= BASE_URL ?>index.php?controller=ventas&action=mostrar" class="text-decoration-none">
                <div class="card text-center p-4 h-100 shadow-sm">
                    <div class="display-4 mb-2">📊</div>
                    <h5 class="fw-bold text-dark">Ventas</h5>
                </div>
            </a>
        </div>

        <!-- Inventario -->
        <div class="col-6 col-md-3">
            <a href="<?= BASE_URL ?>index.php?controller=inventario&action=mostrar" class="text-decoration-none">
                <div class="card text-center p-4 h-100 shadow-sm">
                    <div class="display-4 mb-2">📦</div>
                    <h5 class="fw-bold text-dark">Inventario</h5>
                </div>
            </a>
        </div>

        <!-- Panel Admin (Si está logueado) -->
        <?php if (isset($_SESSION['AdminDni'])): ?>
        <div class="col-6 col-md-3">
            <a href="<?= BASE_URL ?>index.php?controller=adminPanel&action=mostrar" class="text-decoration-none">
                <div class="card text-center p-4 h-100 shadow-sm border-warning">
                    <div class="display-4 mb-2">⚙️</div>
                    <h5 class="fw-bold text-dark">Admin</h5>
                </div>
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>