<?php
class InventarioControlador {

    //muestra el listado de productos con su categoría
        public function mostrar(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        $pdto = new Producto();
        $productos = $pdto->obtener_todos_con_categoria();
        $categorias = $pdto->obtener_categorias(); // Para los botones de filtro

        // Datos para la gráfica (cuántos productos hay por categoría)
        $datosGrafica = $pdto->contar_productos_por_categoria();
        $labelsCat = [];
        $valoresCat = [];
        while ($row = $datosGrafica->fetch_assoc()) {
            $labelsCat[] = $row['nombre_catg'];
            $valoresCat[] = $row['cantidad'];
        }

        require_once APP_PATH . '/views/inventario.php';
    }
}