<?php

namespace Controllers;

use MVC\Router;
use Model\Mensaje;
use Model\Proyecto;

class PaginasController {

    public static function index(Router $router) {

        $proyectos = Proyecto::get(4, 'ASC');
    
        // Render a la vista 
        $router->render('paginas/index', [
            'titulo' => 'Inicio',
            'proyectos' => $proyectos
        ]);
    }

    public static function nosotros(Router $router) {
    
        // Render a la vista 
        $router->render('paginas/nosotros', [
            'titulo' => 'Nosotros'
        ]);
    }

    public static function proyectos(Router $router) {

        $proyectos = Proyecto::all();
    
        // Render a la vista 
        $router->render('paginas/proyectos', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }

    public static function proyecto(Router $router) {

        $id = $_GET['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if(!$id) {
            header('Location: /');
        }

        $proyecto = Proyecto::find($id);

        $router->render('paginas/proyecto', [
            'titulo' => 'Proyecto',
            'proyecto' => $proyecto
        ]);
    }

    public static function servicios(Router $router) {
    
        // Render a la vista 
        $router->render('paginas/servicios', [
            'titulo' => 'Servicios'
        ]);
    }

    public static function ayuda(Router $router) {
    
        // Render a la vista 
        $router->render('paginas/ayuda', [
            'titulo' => 'FAQ'
        ]);
    }

    public static function contacto(Router $router) {

        $alertas = [];
        $mensaje = new Mensaje;
        $secretKey = $_ENV['CAPTCHA_KEY'];
            
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $ip = $_SERVER['REMOTE_ADDR'];
            $captcha = $_POST['g-recaptcha-response'];
            
            $respuesta = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captcha&remoteip=$ip");
            $atributos = json_decode($respuesta, TRUE);

            if(is_auth()) {
                Mensaje::setAlerta('error', 'Los administradores no pueden enviar un mensaje');
            } else {
                date_default_timezone_set('America/Mexico_City');
                $mensaje->fecha = date("Y-m-d");
                $mensaje->sincronizar($_POST);
                $alertas = $mensaje->validarMensaje();
    
                if(empty($alertas)) {

                    if(!$atributos['success']) {
                        Mensaje::setAlerta('error', 'Complete el captcha para enviar el mensaje');
                    } else {
                        $resultado = $mensaje->guardar();
                        if($resultado) {
                            Mensaje::setAlerta('exito', 'Mensaje enviado correctamente');
                        } else {
                            Mensaje::setAlerta('error', 'Error, intente de nuevo');
                        }
                    }
                } 
            }
        }

        $alertas = Mensaje::getAlertas();

        // Render a la vista 
        $router->render('paginas/contacto', [
            'titulo' => 'Contacto',
            'mensaje' => $mensaje,
            'alertas' => $alertas
        ]);

    }

    public static function error(Router $router) {



        $router->render('paginas/error', [
            'titulo' => 'Página no encontrada',
        ]);
    }

}