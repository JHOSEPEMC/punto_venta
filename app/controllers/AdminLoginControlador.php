<?php
class AdminLoginControlador {

    public function iniciar_sesion(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        $dni    = isset($_POST['AdminDni'])    ? trim($_POST['AdminDni'])    : '';
        $contra = isset($_POST['AdminContra']) ? trim($_POST['AdminContra']) : '';
        $mensaje = false;

        if ($dni !== '' && $contra !== '') {
            //SQL ya NO está aquí. El modelo Administrador se encarga.
            $adm = new Administrador();
            $admin = $adm->obtener_por_dni($dni);

            //password_verify valida contra el hash guardado
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
}