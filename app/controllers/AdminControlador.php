<?php
class AdminControlador {

    //muestra el login y valida las credenciales del administrador
    public function login(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        //recibe los datos del formulario
        $dni    = isset($_POST['AdminDni'])    ? trim($_POST['AdminDni'])    : '';
        $contra = isset($_POST['AdminContra']) ? trim($_POST['AdminContra']) : '';
        $mensaje = false;

        //si el usuario envió datos, valida con el modelo Administrador
        if ($dni !== '' && $contra !== '') {
            $adm = new Administrador();
            $admin = $adm->obtener_por_dni($dni);

            //compara la contraseña con el hash de la BD
            if ($admin && password_verify($contra, $admin['pass'])) {
                $_SESSION['AdminDni']    = $admin['dni_administrador'];
                $_SESSION['AdminNombre'] = $admin['nombre_apellido'];

                header('Location: ' . BASE_URL . 'index.php?controller=adminPanel&action=mostrar');
                exit;
            } else {
                $mensaje = true;
            }
        }

        //carga la vista del login de admin
        require_once APP_PATH . '/views/admin/login.php';
    }

    //cierra la sesión del administrador
    public function logout(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        //elimina solo las variables de sesión del admin
        unset($_SESSION['AdminDni']);
        unset($_SESSION['AdminNombre']);

        header('Location: ' . BASE_URL . 'index.php?controller=admin&action=login');
        exit;
    }
}