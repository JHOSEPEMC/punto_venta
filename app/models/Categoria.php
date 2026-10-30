<?php
class Categoria {
    public $id;
    public $nombre;

    public function __construct($datos = []) {
        $this->id = $datos['id_categoria'] ?? null;
        $this->nombre = $datos['nombre_catg'] ?? '';
    }
}