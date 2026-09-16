<?php
class Usuario{
    public $nombre = "";
    public function __construct($name){
        $this->nombre = $name;
    }

    public function ob_nombre(){ //Obtener nombre del usuario
        return $this->nombre;
    }
}
?>