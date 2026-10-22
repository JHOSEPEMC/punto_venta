<?php include __DIR__ . '/partials/header.php'; ?>
<?php include __DIR__ . '/partials/nav.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card p-5 shadow-sm border-0 rounded-4">
                
                <?php if ($tieneCajaAsignada): ?>
                    <!-- ESTADO 2: El admin YA le asignó el sencillo -->
                    <div class="display-1 mb-3">🔓</div>
                    <h3 class="fw-bold text-success">¡Caja Lista!</h3>
                    <p class="text-muted mt-3">El administrador ya te asignó el sencillo. Haz clic en el botón para comenzar tu turno.</p>
                    
                    <a href="<?= BASE_URL ?>index.php?controller=caja&action=abrir_caja" class="btn btn-naranja btn-lg w-100 mt-4 fw-bold shadow-sm">
                        ✅ Habilitar mi Caja
                    </a>

                <?php else: ?>
                    <!-- ESTADO 1: Aún no tiene caja asignada -->
                    <div class="display-1 mb-3">⏳</div>
                    <h3 class="fw-bold text-secondary">Caja Pendiente</h3>
                    
                    <?php if(isset($_SESSION['mensajeCaja'])): ?>
                        <div class="alert alert-warning fw-bold mt-3">
                            <?= htmlspecialchars($_SESSION['mensajeCaja']) ?>
                        </div>
                        <?php unset($_SESSION['mensajeCaja']); ?>
                    <?php else: ?>
                        <p class="text-muted mt-3">Hola, para empezar a vender debes esperar a que el administrador te asigne el sencillo de tu caja.</p>
                    <?php endif; ?>

                    <a href="<?= BASE_URL ?>index.php?controller=caja&action=mostrar" class="btn btn-outline-secondary w-100 mt-4">
                        🔄 Verificar de nuevo
                    </a>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>