<?php

define('CARPETA_IMAGENES', $_SERVER['DOCUMENT_ROOT'] . '/public/imagenes/');

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

function restearFecha($fecha) {

    $fecha = date("d/m/Y", strtotime($fecha));
    
    return $fecha;

}