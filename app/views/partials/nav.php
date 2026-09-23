<?php
//Aseguramos que la sesión esté iniciada
if (session_status() === PHP_SESSION_NONE) session_start();

$esEmpleado = isset($_SESSION['UsuarioDni']);
$esAdmin    = isset($_SESSION['AdminDni']);
?>
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: rgb(255, 100, 0);">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>index.php?controller=home&action=mostrar">PUNTO DE VENTA</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">

                <!-- Solo si hay EMPLEADO logueado -->
                <?php if ($esEmpleado): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?controller=caja&action=mostrar">Caja</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?controller=ventas&action=mostrar">Ventas</a></li>
                <?php endif; ?>

                <!-- Inventario: visible para todos -->
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?controller=inventario&action=mostrar">Inventario</a></li>

                <!-- Solo si hay ADMIN logueado -->
                <?php if ($esAdmin): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?controller=adminPanel&action=mostrar">Panel Admin</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?controller=adminLogout&action=cerrar">Cerrar Sesión Admin</a></li>
                <?php endif; ?>

                <!-- Logins: solo si NADIE está logueado -->
                <?php if (!$esEmpleado && !$esAdmin): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?controller=login&action=iniciar_sesion">Login Empleado</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?controller=adminLogin&action=iniciar_sesion">Login Admin</a></li>
                <?php endif; ?>

                <!-- Cerrar sesión de empleado: solo si es empleado -->
                <?php if ($esEmpleado): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?controller=login&action=cerrar_sesion">Cerrar Sesión</a></li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>