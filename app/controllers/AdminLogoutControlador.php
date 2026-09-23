<?php
class AdminLogoutControlador {

    //Cierra solo la sesión del admin (no toca la del empleado)
    public function cerrar(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        unset($_SESSION['AdminDni']);
        unset($_SESSION['AdminNombre']);

        header('Location: ' . BASE_URL . 'index.php?controller=adminLogin&action=iniciar_sesion');
        exit;
    }
}