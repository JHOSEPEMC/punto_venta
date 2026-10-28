<?php
class EmpleadoControlador {

    public function login(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        $dni    = isset($_POST['UsuarioDni'])    ? trim($_POST['UsuarioDni'])    : '';
        $contra = isset($_POST['UsuarioContra']) ? trim($_POST['UsuarioContra']) : '';
        $mensaje = false;

        if ($dni !== '' && $contra !== '') {
            // 🚀 Usamos el DAO de PDO
            $empDAO = new EmpleadoDAO();
            $empleado = $empDAO->obtener_por_dni($dni);

            if ($empleado && password_verify($contra, $empleado['pass'])) {
                $_SESSION['UsuarioDni']    = $empleado['dni_empleado'];
                $_SESSION['UsuarioContra'] = $empleado['pass'];

                header('Location: ' . BASE_URL . 'index.php?controller=caja&action=mostrar');
                exit;
            } else {
                $mensaje = true;
            }
        }
        require_once APP_PATH . '/views/login.php';
    }

    public function logout(){
        if (session_status() === PHP_SESSION_NONE) session_start();
        unset($_SESSION['UsuarioDni']);
        unset($_SESSION['UsuarioContra']);
        header('Location: ' . BASE_URL . 'index.php?controller=home&action=mostrar');
        exit;
    }
}