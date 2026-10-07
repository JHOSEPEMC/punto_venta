<?php
class LoginControlador {

    //muestra el login y valida credenciales con hash
    public function iniciar_sesion(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        $usuario = isset($_POST['UsuarioDni'])    ? trim($_POST['UsuarioDni'])    : '';
        $contra  = isset($_POST['UsuarioContra']) ? trim($_POST['UsuarioContra']) : '';
        $mensaje = false;

        if ($usuario !== '' && $contra !== '') {
            //busca al empleado por dni
            $emp = new Empleado();
            $empleado = $emp->obtener_por_dni($usuario);

            //valida la contraseña contra el hash guardado
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

    //cierra solo la sesión del empleado
    public function cerrar_sesion(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        unset($_SESSION['UsuarioDni']);
        unset($_SESSION['UsuarioContra']);

        header('Location: ' . BASE_URL . 'index.php?controller=home&action=mostrar');
        exit;
    }
}