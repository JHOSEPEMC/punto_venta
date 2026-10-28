<?php
class Conexion {
    private static $instancia = null;
    private $pdo;

    private function __construct() {
        $host = 'localhost';
        $db   = 'minimarket';
        $user = 'Maravi_Jhosep'; // Administrador de la base de datos
        $pass = 'V4St8TEaz2VGlrTSpFFJ2mBOQP6oTHtKyC/EHP22Yv6Ffe2y5dHxuD00HlSoVm70'; // Contraseña del Administrador de la base de datos
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            die('Error de conexión: ' . $e->getMessage());
        }
    }

    public static function conectar() {
        if (self::$instancia === null) {
            self::$instancia = new Conexion();
        }
        return self::$instancia->pdo;
    }
}