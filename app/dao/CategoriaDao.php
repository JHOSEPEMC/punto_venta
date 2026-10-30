<?php
class CategoriaDAO {
    private $pdo;
    public function __construct() { $this->pdo = Conexion::conectar(); }
    public function obtener_todas() {
        $res = $this->pdo->query("SELECT * FROM categorias")->fetchAll();
        return array_map(fn($f) => new Categoria($f), $res);
    }
}