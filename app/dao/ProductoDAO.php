<?php
class ProductoDAO {
    private $pdo;

    //Conecta a la base de datos PDO
    public function __construct() {
        $this->pdo = Conexion::conectar();
    }

    //Devuelve todos los productos con su categoría (JOIN)
    public function obtener_todos_con_categoria() {
        $sql = "SELECT productos.*, categorias.nombre_catg 
                FROM productos 
                LEFT JOIN categorias ON productos.id_categoria = categorias.id_categoria 
                ORDER BY productos.id_producto ASC";
        return $this->pdo->query($sql)->fetchAll();
    }

    //Devuelve todos los productos sin categoría
    public function obtener_todos() {
        return $this->pdo->query("SELECT * FROM productos ORDER BY nombre_producto ASC")->fetchAll();
    }

    //Busca un producto por su ID
    public function obtener_por_id($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM productos WHERE id_producto = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    //Inserta un nuevo producto
    public function insertar($nombre, $id_categoria, $precio, $stock) {
        $stmt = $this->pdo->prepare("INSERT INTO productos (nombre_producto, id_categoria, precio_unitario, stock) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$nombre, $id_categoria, $precio, $stock]);
    }

    //Actualiza un producto existente
    public function actualizar($id, $nombre, $id_categoria, $precio, $stock) {
        $stmt = $this->pdo->prepare("UPDATE productos SET nombre_producto = ?, id_categoria = ?, precio_unitario = ?, stock = ? WHERE id_producto = ?");
        return $stmt->execute([$nombre, $id_categoria, $precio, $stock, $id]);
    }

    //Elimina un producto
    public function eliminar($id) {
        $stmt = $this->pdo->prepare("DELETE FROM productos WHERE id_producto = ?");
        return $stmt->execute([$id]);
    }

    //Verifica si un producto ya existe por nombre
    public function existe_nombre($nombre) {
        $stmt = $this->pdo->prepare("SELECT id_producto FROM productos WHERE nombre_producto = ?");
        $stmt->execute([$nombre]);
        return $stmt->fetch() ? true : false;
    }

    //Descuenta stock (usado al vender)
    public function descontar_stock($id, $cantidad) {
        $stmt = $this->pdo->prepare("UPDATE productos SET stock = stock - ? WHERE id_producto = ?");
        return $stmt->execute([$cantidad, $id]);
    }

    //Obtiene las categorías para el selector
    public function obtener_categorias() {
        return $this->pdo->query("SELECT * FROM categorias")->fetchAll();
    }

    // Cuenta cuántos productos hay por cada categoría (para la gráfica)
    public function contar_productos_por_categoria() {
        $sql = "
            SELECT categorias.nombre_catg, COUNT(productos.id_producto) as cantidad
            FROM productos
            LEFT JOIN categorias ON productos.id_categoria = categorias.id_categoria
            GROUP BY categorias.id_categoria
        ";
        return $this->pdo->query($sql)->fetchAll();
    }
}