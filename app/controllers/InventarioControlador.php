<?php
class InventarioControlador {
    public function mostrar(){
        if (session_status() === PHP_SESSION_NONE) session_start();
        $pdtoDAO = new ProductoDAO();
        $catDAO  = new CategoriaDAO();

        $productos = $pdtoDAO->obtener_todos_con_categoria(); // Array de Objetos
        $categorias = $catDAO->obtener_todas();               // Array de Objetos

        $datosGrafica = $pdtoDAO->contar_productos_por_categoria(); // Array normal para el chart
        $labelsCat = array_column($datosGrafica, 'nombre_catg');
        $valoresCat = array_column($datosGrafica, 'cantidad');

        require_once APP_PATH . '/views/inventario.php';
    }
}