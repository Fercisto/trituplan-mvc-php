<?php

namespace Model;

class Cotizacion extends ActiveRecord {

    protected static $tabla = 'cotizaciones';
    protected static $columnasDB = ['id', 'folio', 'fecha', 'destinatario', 'items', 'subtotal', 'iva', 'total', 'total_letra', 'condiciones_generales'];

    public $id;
    public $folio;
    public $fecha;
    public $destinatario;
    public $items;
    public $subtotal;
    public $iva;
    public $total;
    public $total_letra;
    public $condiciones_generales;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->folio = $args['folio'] ?? '';
        $this->fecha = $args['fecha'] ?? '';
        $this->destinatario = $args['destinatario'] ?? '';
        $this->items = $args['items'] ?? '';
        $this->subtotal = $args['subtotal'] ?? 0.00;
        $this->iva = $args['iva'] ?? 0.00;
        $this->total = $args['total'] ?? 0.00;
        $this->total_letra = $args['total_letra'] ?? '';
        $this->condiciones_generales = $args['condiciones_generales'] ?? '';
    }

    public function validar() {

        if(!$this->folio) {
            self::$alertas['error'][] = 'El Folio es Obligatorio';
        }

        if(!$this->fecha) {
            self::$alertas['error'][] = 'La Fecha es Obligatoria';
        }

        if(!$this->destinatario) {
            self::$alertas['error'][] = 'El Destinatario es Obligatorio';
        }

        if(!$this->items) {
            self::$alertas['error'][] = 'Debes agregar al menos un producto o servicio';
        }

        if($this->subtotal <= 0) {
            self::$alertas['error'][] = 'El Subtotal debe ser mayor a 0';
        }

        if($this->total <= 0) {
            self::$alertas['error'][] = 'El Total debe ser mayor a 0';
        }

        return self::$alertas;
    }

    // Generar folio automático
    public static function generarFolio() {
        $year = date('Y');
        $query = "SELECT COUNT(*) as total FROM " . static::$tabla . " WHERE folio LIKE 'COT-{$year}-%'";
        $resultado = self::$db->query($query);
        $row = $resultado->fetch_assoc();
        $numero = $row['total'] + 1;

        return 'COT-' . $year . '-' . str_pad($numero, 3, '0', STR_PAD_LEFT);
    }

}