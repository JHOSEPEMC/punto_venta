<?php
class Usuario {
    public $nombre = "";

    //Constructor con nombre
    public function __construct($name){
        $this->nombre = $name;
    }

    //Devuelve el nombre
    public function ob_nombre(){
        return $this->nombre;
    }
}