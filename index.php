<?php
//Front Controller: único punto de entrada
ini_set('display_errors', 1); //Esto le dice a PHP: "muestra los errores en pantalla".
error_reporting(E_ALL); //Esto le dice a PHP: "reporta TODOS los errores, warnings y notices".

//Rutas base (estamos en /punto_venta/)
define('BASE_PATH', __DIR__);                  //.../punto_venta
define('APP_PATH',  __DIR__ . '/app');         //.../punto_venta/app
define('BASE_URL', 'http://192.168.1.56/punto_venta/');           //URL base para enlaces
define('VENDOR_PATH', __DIR__ . '/vendor');    //Para la direccion vendor

//Autocarga de modelos y controladores
spl_autoload_register(function ($clase) {
    // Buscar en config
    if (file_exists(APP_PATH . '/config/' . $clase . '.php')) {
        require_once APP_PATH . '/config/' . $clase . '.php';
    }
    // Buscar en dao
    elseif (file_exists(APP_PATH . '/dao/' . $clase . '.php')) {
        require_once APP_PATH . '/dao/' . $clase . '.php';
    }
    // Buscar en models (para Carrito.php que se queda ahí)
    elseif (file_exists(APP_PATH . '/models/' . $clase . '.php')) {
        require_once APP_PATH . '/models/' . $clase . '.php';
    }
    // Buscar en controllers
    elseif (file_exists(APP_PATH . '/controllers/' . $clase . '.php')) {
        require_once APP_PATH . '/controllers/' . $clase . '.php';
    }
    // Buscar en services
    elseif (file_exists(APP_PATH . '/services/' . $clase . '.php')) {
        require_once APP_PATH . '/services/' . $clase . '.php';
    }
});

//Enrutador simple: lee controller y action de la URL
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'home';
$action     = isset($_GET['action'])     ? $_GET['action']     : 'mostrar';
$clase      = ucfirst($controller) . 'Controlador';
if (!class_exists($clase)) {
    echo "<h1>404 - Controlador no encontrado</h1>";
    exit;
}

$obj = new $clase();
if (!method_exists($obj, $action)) {
    echo "<h1>404 - Acción no encontrada</h1>";
    exit;
}
$obj->$action();