<?php
class Carrito {
    //inicia el carrito en la sesión si no existe
    public static function iniciar(){
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
    }

    //agrega un producto al carrito (si ya existe, suma la cantidad)
    public static function agregar($id_producto, $nombre, $precio, $cantidad){
        //recorremos el carrito para ver si ya existe ese producto
        foreach ($_SESSION['carrito'] as $indice => $item) {
            if ($item['id_producto'] == $id_producto) {
                //si ya existe, sumamos la cantidad
                $_SESSION['carrito'][$indice]['cantidad'] += $cantidad;
                return;
            }
        }
        //si no existe, lo agregamos como nuevo item
        $_SESSION['carrito'][] = [
            'id_producto' => $id_producto,
            'nombre'      => $nombre,
            'precio'      => $precio,
            'cantidad'    => $cantidad,
        ];
    }

    //quita un producto del carrito por su id
    public static function quitar($id_producto){
        foreach ($_SESSION['carrito'] as $indice => $item) {
            if ($item['id_producto'] == $id_producto) {
                unset($_SESSION['carrito'][$indice]);
                //reindexamos el array para que no queden huecos
                $_SESSION['carrito'] = array_values($_SESSION['carrito']);
                return;
            }
        }
    }

    //vacía todo el carrito
    public static function vaciar(){
        $_SESSION['carrito'] = [];
    }

    //devuelve todos los items del carrito
    public static function obtener(){
        return isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
    }

    //calcula el total del carrito
    public static function total(){
        $total = 0;
        foreach ($_SESSION['carrito'] as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        return $total;
    }

    //cuenta cuántos items distintos hay en el carrito
    public static function contar(){
        return count($_SESSION['carrito']);
    }
}