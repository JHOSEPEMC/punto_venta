<?php
class Producto {
    private $bd;

    //conecta con la base de datos al instanciar
    public function __construct(){
        $this->bd = Conexion::conectar();
    }

    //devuelve todos los productos con el nombre de su categoría usando join :SillyDev:
    public function obtener_todos_con_categoria(){
        $sql = "
            SELECT productos.*, categorias.nombre_catg
            FROM productos
            LEFT JOIN categorias ON productos.id_categoria = categorias.id_categoria
            ORDER BY productos.id_producto ASC
        ";
        return $this->bd->query($sql);
    }

    //devuelve todos los productos sin categoría
    public function obtener_todos(){
        return $this->bd->query("SELECT * FROM productos");
    }

    //devuelve un producto completo por id usando sentencia preparada
    public function obtener_por_id($id){
        $sql = "SELECT * FROM productos WHERE id_producto = ?";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    //inserta un producto en la base de datos usando sentencia preparada
    public function insertar($nombre, $id_categoria, $precio, $stock){
        $sql = "INSERT INTO productos (nombre_producto, id_categoria, precio_unitario, stock)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("sidi", $nombre, $id_categoria, $precio, $stock);
        return $stmt->execute();
    }

    //actualiza los datos de un producto usando sentencia preparada
    public function actualizar($id, $nombre, $id_categoria, $precio, $stock){
        $sql = "UPDATE productos
                SET nombre_producto = ?,
                    id_categoria    = ?,
                    precio_unitario = ?,
                    stock           = ?
                WHERE id_producto = ?";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("sidii", $nombre, $id_categoria, $precio, $stock, $id);
        return $stmt->execute();
    }

    //elimina un producto por id usando sentencia preparada
    public function eliminar($id){
        $sql = "DELETE FROM productos WHERE id_producto = ?";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    //devuelve todas las categorías para el select del formulario
    public function obtener_categorias(){
        return $this->bd->query("SELECT * FROM categorias");
    }

    //verifica si ya existe un producto con ese nombre usando sentencia preparada
    public function existe_nombre($nombre){
        $sql = "SELECT id_producto FROM productos WHERE nombre_producto = ?";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() != null;
    }

    //descuenta stock de un producto usando sentencia preparada
    public function descontar_stock($id, $cantidad){
        $sql = "UPDATE productos SET stock = stock - ? WHERE id_producto = ?";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("ii", $cantidad, $id);
        return $stmt->execute();
    }
}