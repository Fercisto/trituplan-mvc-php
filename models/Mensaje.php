<?php

namespace Model;

class Mensaje extends ActiveRecord {

    protected static $tabla = 'mensajes';
    protected static $columnasDB = ['id', 'email' ,'mensaje', 'fecha'];

    public $id;
    public $email;
    public $mensaje;
    public $fecha;
    
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->email = $args['email'] ?? '';
        $this->mensaje = $args['mensaje'] ?? '';
        $this->fecha = $args['fecha'] ?? '';
    }

     public function validarMensaje() {
        if(!$this->email || !$this->mensaje) {
            self::$alertas['error'][] = 'Todos los campos son obligatorios';
        } 

        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no válido';
        }

        if(strlen($this->mensaje) < 10 || strlen($this->mensaje) > 300) {
            self::$alertas['error'][] = 'El Mensaje debe contener entre 10 y 300 caracteres';
        }

        return self::$alertas;
        
    }

}