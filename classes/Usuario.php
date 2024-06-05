<?php

class Usuario{
    public $nombre;
    public $edad;
    public $genero;
    public $profesion;

    function __construct($nombre, $edad, $genero, $profesion)
    {
        $this->nombre = $nombre;
        $this->edad = $edad;
        $this->genero = $genero;
        $this->profesion = $profesion;
    }
}