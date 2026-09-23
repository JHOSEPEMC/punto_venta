<?php
class LoginControlador {
    // Muestra el login y valida credenciales
    public function iniciar_sesion(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        $usuario = isset($_POST['UsuarioDni'])    ? trim($_POST['UsuarioDni'])    : '';
        $contra  = isset($_POST['UsuarioContra']) ? trim($_POST['UsuarioContra']) : '';
        $mensaje = false;

        if ($usuario !== '' && $contra !== '') {
            require_once APP_PATH . '/models/Conexion.php';
            $db = Conexion::conectar();
            $sql = "SELECT * FROM empleados WHERE dni_empleado = '$usuario' AND pass = '$contra'";
            $usuarioLogueado = $db->query($sql)->fetch_assoc();

            if (!$usuarioLogueado) {
                $mensaje = true;
            } else {
                $_SESSION['UsuarioDni']    = $usuarioLogueado['dni_empleado'];
                $_SESSION['UsuarioContra'] = $usuarioLogueado['pass'];
                // Redirige a caja tras login
                header('Location: ' . BASE_URL . 'index.php?controller=caja&action=mostrar');
                exit;
            }
        }

        require_once APP_PATH . '/views/login.php';
    }
}