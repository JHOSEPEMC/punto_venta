<?php
class Categoria {
    private $bd;

    public function __construct(){
        $this->bd = Conexion::conectar();
    }

    //Devuelve todas las categorías
    public function obtener_todas(){
        return $this->bd->query("SELECT * FROM categorias");
    }

    //Obtiene una categoría por ID
    public function obtener_por_id($id){
        $sql = "SELECT * FROM categorias WHERE id_categoria = " . (int)$id;
        return $this->bd->query($sql)->fetch_assoc();
    }
}