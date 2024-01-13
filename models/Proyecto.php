<?php

namespace Model;

class Proyecto extends ActiveRecord {

    protected static $tabla = 'proyectos';
    protected static $columnasDB = ['id', 'titulo', 'descripcion', 'destino' ,'imagen'];

    public $id;
    public $titulo;
    public $descripcion;
    public $destino;
    public $imagen;
    
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->titulo = $args['titulo'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->destino = $args['destino'] ?? '';
        $this->imagen = $args['imagen'] ?? '';
    }


    public function validar() {

        if(!$this->titulo) {
            self::$alertas['error'][] = 'El Título del Proyecto es Obligatorio';
        } 

        if(!$this->descripcion) {
            self::$alertas['error'][] = 'La descripción del Proyecto es obligatoria';
        }

        if(strlen($this->descripcion) < 15 || strlen($this->descripcion) > 200) {
            self::$alertas['error'][] = 'La descripción debe contener entre 15 y 200 caracteres';
        }

        if(!$this->destino) {
            self::$alertas['error'][] = 'La descripción del Proyecto es obligatoria';
        }

        if(!$this->imagen) {
            self::$alertas['error'][] = 'La imagen del Proyecto es obligatoria';
        }

        return self::$alertas;
        
    }

}