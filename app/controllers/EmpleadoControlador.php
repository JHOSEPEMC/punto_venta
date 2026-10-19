<?php
class EmpleadoControlador {

    //muestra el login y valida las credenciales del empleado
    public function login(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        //recibe los datos del formulario
        $dni    = isset($_POST['UsuarioDni'])    ? trim($_POST['UsuarioDni'])    : '';
        $contra = isset($_POST['UsuarioContra']) ? trim($_POST['UsuarioContra']) : '';
        $mensaje = false;

        //si el usuario envió datos, valida con el modelo Empleado
        if ($dni !== '' && $contra !== '') {
            $emp = new Empleado();
            $empleado = $emp->obtener_por_dni($dni);

            //compara la contraseña con el hash de la BD
            if ($empleado && password_verify($contra, $empleado['pass'])) {
                $_SESSION['UsuarioDni']    = $empleado['dni_empleado'];
                $_SESSION['UsuarioContra'] = $empleado['pass'];

                header('Location: ' . BASE_URL . 'index.php?controller=caja&action=mostrar');
                exit;
            } else {
                $mensaje = true;
            }
        }

        //carga la vista del login de empleado
        require_once APP_PATH . '/views/login.php';
    }

    //cierra la sesión del empleado
    public function logout(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        //elimina solo las variables de sesión del empleado
        unset($_SESSION['UsuarioDni']);
        unset($_SESSION['UsuarioContra']);

        header('Location: ' . BASE_URL . 'index.php?controller=home&action=mostrar');
        exit;
    }
}