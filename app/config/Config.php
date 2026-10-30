<?php
class Config {
    public static function init() {
        // Rutas dinámicas
        $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $ip_local = '192.168.1.56';
        $host      = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $carpeta   = 'punto_venta';
        if ($host === 'localhost' || $host === '127.0.0.1') {
            $host = $ip_local; 
        }
        define('BASE_URL', $protocolo . '://' . $host . '/' . $carpeta . '/');
        define('BASE_PATH', dirname(__DIR__, 2));
        define('APP_PATH',  BASE_PATH . '/app');
        define('VENDOR_PATH', BASE_PATH . '/vendor');
        define('ASSETS_PATH', BASE_PATH . '/assets');
        define('ASSETS_URL', BASE_URL . 'assets/');

        define('NOMBRE_NEGOCIO', 'MINIMARKET EL PROVEEDOR');
        define('MONEDA', 'S/');
        
        date_default_timezone_set('America/Lima');

        define('DEBUG', true);
        if (DEBUG) {
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', 0);
            error_reporting(0);
        }
    }

    public static function db() {
        return [
            // 'driver' => 'mysql', 'pgsql' o 'sqlite'
            'driver'   => 'pgsql', 
            
            // Para MySQL y PostgreSQL:
            'host'     => 'localhost',
            'port'     => '3305', // 3306 (MySQL) | 5432 (PostgreSQL)
            'database' => 'minimarket',
            // 'username' => 'Maravi_Jhosep'; // Administrador de la base de datos | postgres | Maravi_Jhosep
            'username' => 'postgres',
            'password' => 'ec1a54f40f',
            #'password' = 'V4St8TEaz2VGlrTSpFFJ2mBOQP6oTHtKyC/EHP22Yv6Ffe2y5dHxuD00HlSoVm70'; // Contraseña del Administrador de la base de datos
            'charset'  => 'utf8',
            
            // Para SQLite (solo si usas ese driver):
            'sqlite_path' => __DIR__ . '/../../database.sqlite'
        ];
    }
}