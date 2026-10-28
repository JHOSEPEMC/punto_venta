<?php
class CategoriaDAO {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexion::conectar();
    }

    public function obtener_todas() {
        return $this->pdo->query("SELECT * FROM categorias")->fetchAll();
    }
}