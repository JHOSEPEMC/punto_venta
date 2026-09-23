<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/nav.php'; ?>

<div class="container mt-4">
    <h2>Login de Administrador</h2>
    <form action="<?= BASE_URL ?>index.php?controller=adminLogin&action=iniciar_sesion" method="POST">
        <span>DNI del administrador: </span><br>
        <input class='i_s' type="text" required placeholder="DNI" name="AdminDni"><br><br>

        <span>Contraseña: </span><br>
        <input class='i_s' type="password" required placeholder="contraseña" name="AdminContra"><br><br>

        <button type="submit" class="i_s">Iniciar Sesión</button>
    </form>

    <?php if ($mensaje): ?>
        <div class="alert alert-info mt-3">Credenciales incorrectas</div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>   