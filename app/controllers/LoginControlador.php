<?php
class LoginControlador {

    //Muestra el login y valida credenciales
    public function iniciar_sesion(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        $usuario = isset($_POST['UsuarioDni'])    ? trim($_POST['UsuarioDni'])    : '';
        $contra  = isset($_POST['UsuarioContra']) ? trim($_POST['UsuarioContra']) : '';
        $mensaje = false;

        if ($usuario !== '' && $contra !== '') {
            //SQL ya NO está aquí. El modelo Empleado se encarga.
            $emp = new Empleado();
            $usuarioLogueado = $emp->buscar_por_credenciales($usuario, $contra);

            if (!$usuarioLogueado) {
                $mensaje = true;
            } else {
                $_SESSION['UsuarioDni']    = $usuarioLogueado['dni_empleado'];
                $_SESSION['UsuarioContra'] = $usuarioLogueado['pass'];
                header('Location: ' . BASE_URL . 'index.php?controller=caja&action=mostrar');
                exit;
            }
        }

        require_once APP_PATH . '/views/login.php';
    }

    //Cierra solo la sesión del empleado
    public function cerrar_sesion(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        unset($_SESSION['UsuarioDni']);
        unset($_SESSION['UsuarioContra']);

        header('Location: ' . BASE_URL . 'index.php?controller=home&action=mostrar');
        exit;
    }
}