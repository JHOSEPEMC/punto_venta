<?php
// 1. Cargamos la clase Config
require_once __DIR__ . '/app/config/Config.php';

// 2. Inicializamos (Esto define BASE_URL, APP_PATH, MONEDA, VENDOR_PATH, etc.)
Config::init(); 

// 3. Autocarga de clases (Ya podemos usar APP_PATH porque Config lo definió)
spl_autoload_register(function ($clase) {
    $rutas = [
        APP_PATH . '/config/',
        APP_PATH . '/dao/',
        APP_PATH . '/models/',
        APP_PATH . '/controllers/',
        APP_PATH . '/services/'
    ];
    
    foreach ($rutas as $ruta) {
        if (file_exists($ruta . $clase . '.php')) {
            require_once $ruta . $clase . '.php';
            return;
        }
    }
});

// 4. Enrutador simple
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'home';
$action     = isset($_GET['action'])     ? $_GET['action']     : 'mostrar';
$clase      = ucfirst($controller) . 'Controlador';

if (!class_exists($clase)) {
    echo "<h1>404 - Controlador no encontrado: $clase</h1>";
    exit;
}

$obj = new $clase();
if (!method_exists($obj, $action)) {
    echo "<h1>404 - Acción no encontrada: $action</h1>";
    exit;
}
$obj->$action();