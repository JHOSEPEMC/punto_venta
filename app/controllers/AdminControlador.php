<?php
class AdminControlador {

    public function login(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        $dni    = isset($_POST['AdminDni'])    ? trim($_POST['AdminDni'])    : '';
        $contra = isset($_POST['AdminContra']) ? trim($_POST['AdminContra']) : '';
        $mensaje = false;

        if ($dni !== '' && $contra !== '') {
            // 🚀 Usamos el DAO de PDO
            $admDAO = new AdministradorDAO();
            $admin = $admDAO->obtener_por_dni($dni);

            if ($admin && password_verify($contra, $admin['pass'])) {
                $_SESSION['AdminDni']    = $admin['dni_administrador'];
                $_SESSION['AdminNombre'] = $admin['nombre_apellido'];

                header('Location: ' . BASE_URL . 'index.php?controller=adminPanel&action=mostrar');
                exit;
            } else {
                $mensaje = true;
            }
        }
        require_once APP_PATH . '/views/admin/login.php';
    }

    public function logout(){
        if (session_status() === PHP_SESSION_NONE) session_start();
        unset($_SESSION['AdminDni']);
        unset($_SESSION['AdminNombre']);
        header('Location: ' . BASE_URL . 'index.php?controller=admin&action=login');
        exit;
    }
}