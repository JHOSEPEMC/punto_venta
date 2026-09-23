<?php
class HomeControlador {
    // Muestra el home
    public function mostrar() {
        require_once APP_PATH . '/views/home.php';
    }
}