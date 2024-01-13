<?php

define('CARPETA_IMAGENES', $_SERVER['DOCUMENT_ROOT'] . '/imagenes/');

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

function pagina_actual($path) : bool {
    return str_contains($_SERVER['PATH_INFO'] ?? '/', $path) ? true : false;
}

function is_auth() : bool {
    
    if(!isset($_SESSION)) {
        session_start();
    }
    return isset($_SESSION['email']) && !empty($_SESSION);
}

function validarTipoContenido($tipo) {
    
    $tipos = ['proyecto', 'mensaje'];

    return in_array($tipo, $tipos);
}

function mostrarNotificacion($codigo) {
    
    $mensaje = '';

    switch($codigo) {
        case 1: $mensaje = 'Creado Correctamente';
            break;
        case 2: $mensaje = 'Actualizado Correctamente';
            break;
        case 3: $mensaje = 'Eliminado Correctamente';
            break;
        default:
            $mensaje = false;
            break;
    }

    return $mensaje;
}

function restearFecha($fecha) {

    $fecha = date("d/m/Y", strtotime($fecha));
    
    return $fecha;

}