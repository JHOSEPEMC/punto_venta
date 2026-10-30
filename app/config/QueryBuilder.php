<?php
class QueryBuilder {
    private $driver;

    public function __construct() {
        $this->driver = Config::db()['driver'];
    }

    // Traduce la función de fecha según el motor
    public function fechaFormateada($columna, $formato = 'YYYY-MM') {
        if ($this->driver === 'pgsql') {
            //POSTGREESQL
            return "TO_CHAR($columna, '$formato')";
        } elseif ($this->driver === 'sqlite') {
            //SQLLITE
            return "strftime('%Y-%m', $columna)";
        } else {
            //MYSQL
            $formatoMySQL = str_replace('YYYY-MM', '%Y-%m', $formato);
            return "DATE_FORMAT($columna, '$formatoMySQL')";
        }
    }

    // Traduce el intervalo de días (para gráficas)
    public function ultimosDias($columna, $dias = 7) {
        if ($this->driver === 'pgsql') {
            return "$columna >= CURRENT_DATE - INTERVAL '$dias days'";
        } elseif ($this->driver === 'sqlite') {
            return "$columna >= date('now', '-$dias days')";
        } else {
            return "$columna >= DATE_SUB(CURDATE(), INTERVAL $dias DAY)";
        }
    }
}