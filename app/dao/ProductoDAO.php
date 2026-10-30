<?php
class ProductoDAO {
    private $pdo, $qb;

    public function __construct() {
        $this->pdo = Conexion::conectar();
        $this->qb = new QueryBuilder();
    }

    public function obtener_todos_con_categoria() {
        $sql = "SELECT p.*, c.nombre_catg FROM productos p 
                LEFT JOIN categorias c ON p.id_categoria = c.id_categoria ORDER BY p.id_producto ASC";
        $res = $this->pdo->query($sql)->fetchAll();
        return array_map(fn($fila) => new Producto($fila), $res);
    }

    public function obtener_por_id($id): ?Producto {
        $stmt = $this->pdo->prepare("SELECT p.*, c.nombre_catg FROM productos p 
                                    LEFT JOIN categorias c ON p.id_categoria = c.id_categoria 
                                    WHERE p.id_producto = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch();
        
        return $fila ? new Producto($fila) : null;
    }

    public function insertar($nombre, $id_categoria, $precio, $stock) {
        $stmt = $this->pdo->prepare("INSERT INTO productos (nombre_producto, id_categoria, precio_unitario, stock) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$nombre, $id_categoria, $precio, $stock]);
    }

    public function actualizar($id, $nombre, $id_categoria, $precio, $stock) {
        $stmt = $this->pdo->prepare("UPDATE productos SET nombre_producto=?, id_categoria=?, precio_unitario=?, stock=? WHERE id_producto=?");
        return $stmt->execute([$nombre, $id_categoria, $precio, $stock, $id]);
    }

    public function eliminar($id) {
        $stmt = $this->pdo->prepare("DELETE FROM productos WHERE id_producto = ?");
        return $stmt->execute([$id]);
    }

    public function existe_nombre($nombre) {
        $stmt = $this->pdo->prepare("SELECT id_producto FROM productos WHERE nombre_producto = ?");
        $stmt->execute([$nombre]);
        return $stmt->fetch() ? true : false;
    }

    public function descontar_stock($id, $cantidad) {
        $stmt = $this->pdo->prepare("UPDATE productos SET stock = stock - ? WHERE id_producto = ?");
        return $stmt->execute([$cantidad, $id]);
    }

    public function obtener_categorias() {
        $res = $this->pdo->query("SELECT * FROM categorias")->fetchAll();
        return array_map(fn($fila) => new Categoria($fila), $res);
    }

    public function contar_productos_por_categoria() {
        $sql = "SELECT c.nombre_catg, COUNT(p.id_producto) as cantidad FROM productos p
                LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
                GROUP BY c.id_categoria, c.nombre_catg";
        return $this->pdo->query($sql)->fetchAll();
    }
}