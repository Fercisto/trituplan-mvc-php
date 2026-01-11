<?php

namespace Controllers;

use Model\Mensaje;
use MVC\Router;
use Model\Proyecto;
use Model\Cotizacion;
use Classes\Paginacion;

class AdminController {

    public static function index(Router $router) {

        // Detectar sección activa
        $seccion = $_GET['seccion'] ?? 'cotizaciones';

        // Validar que la sección sea válida
        $secciones_validas = ['proyectos', 'mensajes', 'cotizaciones'];
        if(!in_array($seccion, $secciones_validas)) {
            $seccion = 'cotizaciones';
        }

        $resultado = $_GET['resultado'] ?? '';
        $mensajes = Mensaje::all();
        $proyectos = [];
        $cotizaciones = [];
        $paginacion_html = '';

        // Lógica para sección de proyectos
        if($seccion === 'proyectos') {
            $pagina_actual = $_GET['page'] ?? null;
            $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);

            if(!$pagina_actual || $pagina_actual < 1) {
                header('Location: /admin?seccion=proyectos&page=1');
                exit();
            }

            $registros_por_pagina = 10;
            $total = Proyecto::total();

            $paginacion = new Paginacion($pagina_actual, $registros_por_pagina, $total);

            // Si hay 0 proyectos, permitir ver página 1 vacía
            if($total > 0 && $paginacion->total_paginas() < $pagina_actual) {
                header('Location: /admin?seccion=proyectos&page=1');
                exit();
            }

            $proyectos = Proyecto::paginar($registros_por_pagina, $paginacion->offset());
            $paginacion_html = $paginacion->paginacion();
        }

        // Lógica para sección de cotizaciones
        if($seccion === 'cotizaciones') {
            $pagina_actual = $_GET['page'] ?? null;
            $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);

            if(!$pagina_actual || $pagina_actual < 1) {
                header('Location: /admin?seccion=cotizaciones&page=1');
                exit();
            }

            $registros_por_pagina = 10;
            $total = Cotizacion::total();

            $paginacion = new Paginacion($pagina_actual, $registros_por_pagina, $total);

            // Si hay 0 cotizaciones, permitir ver página 1 vacía
            if($total > 0 && $paginacion->total_paginas() < $pagina_actual) {
                header('Location: /admin?seccion=cotizaciones&page=1');
                exit();
            }

            $cotizaciones = Cotizacion::paginar($registros_por_pagina, $paginacion->offset());
            $paginacion_html = $paginacion->paginacion();
        }

        $router->render('admin/index', [
            'titulo' => 'Administrar',
            'seccion' => $seccion,
            'proyectos' => $proyectos,
            'cotizaciones' => $cotizaciones,
            'mensajes' => $mensajes,
            'resultado' => $resultado,
            'paginacion' => $paginacion_html
        ]);
    }

}