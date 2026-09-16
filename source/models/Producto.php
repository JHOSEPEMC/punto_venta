<?php
class Producto{
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
        INNER JOIN categorias
        ON productos.id_categoria = categorias.id_categoria
        WHERE productos.id_producto =" . $id.";
        "; #Consulta para el sql :SillyDev:
        $resultado = $this->bd->query($sql);
        $nameProducto = $resultado->fetch_assoc()['nameProducto'];
        $nameCategoria = $resultado->fetch_assoc()['nameCategoria'];
        $precio = $resultado->fetch_assoc()['precio'];
        $stock = $resultado->fetch_assoc()['stock'];
        $info = [$nameProducto, $nameCategoria, $precio, $stock];
        return $info;
    }


    public function obtener_nombre($id){
        $sql = '
        SELECT productos.nombre_producto FROM productos WHERE id_producto = '.$id.'
        ';
        $resultado = $this->bd->query($sql);
        return $resultado->fetch_assoc()['nombre_producto'];
    }
    public function obtener_categoria($id){
        $sql = '
        SELECT categorias.nombre_catg
        FROM productos
        INNER JOIN categorias
        ON categorias.id_categoria = productos.id_categoria
        WHERE id_producto = '.$id.'
        ';
        $resultado = $this->bd->query($sql);
        return $resultado->fetch_assoc()['nombre_catg'];
    }
    public function obtener_precio($id){
        $sql = '
        SELECT productos.precio_unitario FROM productos WHERE id_producto = '.$id.'
        ';
        $resultado = $this->bd->query($sql);
        return $resultado->fetch_assoc()['precio_unitario'];
    }

    public function obtener_stock($id){
        $sql = '
        SELECT productos.stock FROM productos WHERE id_producto = '.$id.'
        ';
        $resultado = $this->bd->query($sql);
        return $resultado->fetch_assoc()['stock'];
    }
}
?>