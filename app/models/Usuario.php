<?php
class Usuario {
    public $nombre = "";

    //constructor con nombre opcional para permitir instanciar sin argumentos
    public function __construct($name = ''){
        $this->nombre = $name;
    }

    //devuelve el nombre del usuario
    public function ob_nombre(){
        return $this->nombre;
    }
}