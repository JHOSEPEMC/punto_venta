<?php
class InventarioControlador {

    //muestra el listado de productos con su categoría
    public function mostrar(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        //instancia el modelo de productos
        $pdto = new Producto();

        //obtiene todos los productos con categoría en una sola consulta
        $productos = $pdto->obtener_todos_con_categoria();

        //carga la vista del inventario
        require_once APP_PATH . '/views/inventario.php';
    }
}