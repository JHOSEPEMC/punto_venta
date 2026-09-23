<?php
class Producto {
    private $bd;

    // Corregido: antes era private
    public function __construct(){
        $this->bd = Conexion::conectar();
    }

    // Info completa de un producto
    public function obtener_info($id){
        $sql = "
            SELECT productos.nombre_producto as nameProducto,
                    categorias.nombre_catg as nameCategoria,
                    productos.precio_unitario as precio,
                    productos.stock as stock
            FROM productos
            INNER JOIN categorias ON productos.id_categoria = categorias.id_categoria
            WHERE productos.id_producto = " . (int)$id;
        $fila = $this->bd->query($sql)->fetch_assoc();
        return [$fila['nameProducto'], $fila['nameCategoria'], $fila['precio'], $fila['stock']];
    }

    public function obtener_nombre($id){
        $sql = 'SELECT nombre_producto FROM productos WHERE id_producto = ' . (int)$id;
        return $this->bd->query($sql)->fetch_assoc()['nombre_producto'];
    }

    public function obtener_categoria($id){
        $sql = '
            SELECT categorias.nombre_catg
            FROM productos
            INNER JOIN categorias ON categorias.id_categoria = productos.id_categoria
            WHERE id_producto = ' . (int)$id;
        return $this->bd->query($sql)->fetch_assoc()['nombre_catg'];
    }

    public function obtener_precio($id){
        $sql = 'SELECT precio_unitario FROM productos WHERE id_producto = ' . (int)$id;
        return $this->bd->query($sql)->fetch_assoc()['precio_unitario'];
    }

    public function obtener_stock($id){
        $sql = 'SELECT stock FROM productos WHERE id_producto = ' . (int)$id;
        return $this->bd->query($sql)->fetch_assoc()['stock'];
    }

    // Devuelve todos los productos
    public function obtener_todos(){
        return $this->bd->query("SELECT * FROM productos");
    }
}