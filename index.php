<?php
//Front Controller: único punto de entrada
ini_set('display_errors', 1); //Esto le dice a PHP: "muestra los errores en pantalla".
error_reporting(E_ALL); //Esto le dice a PHP: "reporta TODOS los errores, warnings y notices".

//Rutas base (estamos en /punto_venta/)
define('BASE_PATH', __DIR__);                  //.../punto_venta
define('APP_PATH',  __DIR__ . '/app');         //.../punto_venta/app
define('BASE_URL', '/punto_venta/');           //URL base para enlaces

//Autocarga de modelos y controladores
spl_autoload_register(function ($clase) {
    if(file_exists(APP_PATH . '/models/' . $clase . '.php')) {
        require_once APP_PATH . '/models/' . $clase . '.php';
    }elseif (file_exists(APP_PATH . '/controllers/' . $clase . '.php')) {
        require_once APP_PATH . '/controllers/' . $clase . '.php';
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