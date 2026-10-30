<?php
class Conexion {
    private static $instancia = null;
    private $pdo;

    private function __construct() {
        $config = Config::db();
        $driver = $config['driver'];

        try {
            if ($driver === 'sqlite') {
                $this->pdo = new PDO("sqlite:" . $config['sqlite_path']);
            } else {
                $dsn = "$driver:host={$config['host']};port={$config['port']};dbname={$config['database']}";
                if ($driver === 'mysql') $dsn .= ";charset={$config['charset']}";
                $this->pdo = new PDO($dsn, $config['username'], $config['password']);
            }
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            die('Error de conexión (' . $driver . '): ' . $e->getMessage());
        }
    }

    public static function conectar() {
        if (self::$instancia === null) {
            self::$instancia = new Conexion();
        }
        return self::$instancia->pdo;
    }
}