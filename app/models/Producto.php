<?php
class Producto {
    private $bd;

    public function __construct(){
        $this->bd = Conexion::conectar();
    }

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

    public function obtener_todos(){
        return $this->bd->query("SELECT * FROM productos");
    }

    //═══════════════════════════════════════════════════════
    //MÉTODOS NUEVOS (panel de admin)
    //═══════════════════════════════════════════════════════

    //Inserta un producto (id_producto es AUTO_INCREMENT)
    public function insertar($nombre, $id_categoria, $precio, $stock){
        $sql = "INSERT INTO productos (nombre_producto, id_categoria, precio_unitario, stock)
                VALUES ('$nombre', '$id_categoria', '$precio', '$stock')";
        return $this->bd->query($sql);
    }

    //Actualiza un producto
    public function actualizar($id, $nombre, $id_categoria, $precio, $stock){
        $sql = "UPDATE productos
                SET nombre_producto = '$nombre',
                    id_categoria    = '$id_categoria',
                    precio_unitario = '$precio',
                    stock           = '$stock'
                WHERE id_producto = " . (int)$id;
        return $this->bd->query($sql);
    }

    //Elimina un producto
    public function eliminar($id){
        $sql = "DELETE FROM productos WHERE id_producto = " . (int)$id;
        return $this->bd->query($sql);
    }

    //Devuelve todas las categorías (para el <select>)
    public function obtener_categorias(){
        return $this->bd->query("SELECT * FROM categorias");
    }

    //Verifica si ya existe un producto con ese nombre
    public function existe_nombre($nombre){
        $sql = "SELECT id_producto FROM productos WHERE nombre_producto = '$nombre'";
        return $this->bd->query($sql)->fetch_assoc() != null;
    }

    //Devuelve un producto completo por ID
    public function obtener_por_id($id){
        $sql = "SELECT * FROM productos WHERE id_producto = " . (int)$id;
        return $this->bd->query($sql)->fetch_assoc();
    }
}