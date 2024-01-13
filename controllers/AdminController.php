<?php

namespace Controllers;

use Model\Mensaje;
use MVC\Router;
use Model\Proyecto;
use Classes\Paginacion;

class AdminController {

    public static function index(Router $router) {
        
        $proyectos = Proyecto::all();
        $mensajes = Mensaje::all();

        $resultado = $_GET['resultado'] ?? '';

        $pagina_actual = $_GET['page'];
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);

        if(!$pagina_actual) {
            header('Location: /admin?page=1');
        }


        $registros_por_pagina = 5;
        $total = Proyecto::total();

        $paginacion = new Paginacion($pagina_actual, $registros_por_pagina, $total);

        if($paginacion->total_paginas() < $pagina_actual || $pagina_actual < 1) {
            header('Location: /admin?page=1');
        }


        $proyectos = Proyecto::paginar($registros_por_pagina, $paginacion->offset());

        $router->render('admin/index', [
            'titulo' => 'Administrar',
            'proyectos' => $proyectos,
            'mensajes' => $mensajes,
            'resultado' => $resultado,
            'paginacion' => $paginacion->paginacion()
        ]);
    }

}