<?php
class Categoria {
    private $bd;

    //conecta con la base de datos al instanciar
    public function __construct(){
        $this->bd = Conexion::conectar();
    }

    //devuelve todas las categorías
    public function obtener_todas(){
        return $this->bd->query("SELECT * FROM categorias");
    }

    //obtiene una categoría por id usando sentencia preparada
    public function obtener_por_id($id){
        $sql = "SELECT * FROM categorias WHERE id_categoria = ?";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}