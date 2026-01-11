<?php

namespace Controllers;

use MVC\Router;
use Model\Mensaje;

class MensajeController {

    public static function eliminar() {
        if($_SERVER['REQUEST_METHOD']) {
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if($id) {

                $tipo = $_POST['tipo'];

                if(validarTipoContenido($tipo)) {
                    $mensaje = Mensaje::find($id);
                    $resultado = $mensaje->eliminar();

                    if($resultado) {
                        header('Location: /admin?seccion=mensajes');
                    }
                }
            }
        }
    }

}