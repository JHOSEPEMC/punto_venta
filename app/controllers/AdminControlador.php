<?php
class AdminControlador {
    public function login(){
        if (session_status() === PHP_SESSION_NONE) session_start();
        $dni    = trim($_POST['AdminDni'] ?? '');
        $contra = trim($_POST['AdminContra'] ?? '');
        $mensaje = false;

        if ($dni !== '' && $contra !== '') {
            $admin = (new AdministradorDAO())->obtener_por_dni($dni);
            if ($admin && password_verify($contra, $admin->pass)) {
                $_SESSION['AdminDni']    = $admin->dni;
                $_SESSION['AdminNombre'] = $admin->nombre;
                header('Location: ' . BASE_URL . 'index.php?controller=adminPanel&action=mostrar');
                exit;
            } else { $mensaje = true; }
        }
        require_once APP_PATH . '/views/admin/login.php';
    }

    public function logout(){
        if (session_status() === PHP_SESSION_NONE) session_start();
        unset($_SESSION['AdminDni'], $_SESSION['AdminNombre']);
        header('Location: ' . BASE_URL . 'index.php?controller=admin&action=login');
        exit;
    }
}