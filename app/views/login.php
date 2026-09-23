<?php include __DIR__ . '/partials/header.php'; ?>
<?php include __DIR__ . '/partials/nav.php'; ?>

<div class="container mt-4">
    <h2>Login</h2>
    <form action="<?= BASE_URL ?>index.php?controller=login&action=iniciar_sesion" method="POST">
        <span>Usuario (DNI): </span><br>
        <input class='i_s' type="text" required placeholder="Usuario" name="UsuarioDni"><br><br>

        <span>Contraseña: </span><br>
        <input class='i_s' type="password" required placeholder="contraseña" name="UsuarioContra"><br><br>

        <button type="submit" class="i_s">Iniciar Sesion</button>
    </form>

    <?php if ($mensaje): ?>
        <div class="alert alert-info">Ingrese correctamente sus credenciales</div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>