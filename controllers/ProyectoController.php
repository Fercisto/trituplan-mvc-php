<?php

namespace Controllers;
use MVC\Router;
use Model\Proyecto;
use Intervention\Image\ImageManagerStatic as Image;

class ProyectoController {

    public static function crear(Router $router) {
        
        $alertas = [];
        $proyecto = new Proyecto;

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $proyecto = new Proyecto($_POST['proyecto']);

            // Generar nombre de la imagen
            $nombreImagen = md5( uniqid( rand(), true ));

            // Realiza un resize a la imagen con la intervetion
            if($_FILES['proyecto']['tmp_name']['imagen']) {

                $tipoImagen = $_FILES['proyecto']['type']['imagen'];

                // Verificar si el tipo de imagen es PNG
                if ($tipoImagen === 'image/jpeg' || $tipoImagen === 'image/jpg') {
                    
                    $imagen_jpg = Image::make($_FILES['proyecto']['tmp_name']['imagen'])->fit(800,900)->encode('jpg', 80);
                    $imagen_webp = Image::make($_FILES['proyecto']['tmp_name']['imagen'])->fit(800,900)->encode('webp', 80);
                    $imagen_jpg->orientate();
                    $imagen_webp->orientate();
                    $proyecto->setImagen($nombreImagen);

                } else {
                    Proyecto::setAlerta('error', 'La imagen debe ser en formato JPG/JPEG');
                }

            }

            $alertas = $proyecto->validar();

            if(empty($alertas)) {

                // Crear la carpeta
                if(!is_dir(CARPETA_IMAGENES)) {
                    mkdir(CARPETA_IMAGENES);
                }

                // Guarda la imagen en el servidor
                $imagen_jpg->save(CARPETA_IMAGENES . $nombreImagen . ".jpg");
                $imagen_webp->save(CARPETA_IMAGENES . $nombreImagen . ".webp");

                $resultado = $proyecto->guardar();

                if($resultado) {
                    header('Location: /admin');
                }
            }

        } 

        $alertas = Proyecto::getAlertas();

        $router->render('admin/proyectos/crear', [
            'titulo' => 'Administrar',
            'alertas' => $alertas,
            'proyecto' => $proyecto
        ]);
    }

    public static function actualizar(Router $router) {

        $id = $_GET['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if(!$id) {
            header('Location: /');
        }

        $proyecto = Proyecto::find($id);
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $proyecto->sincronizar($_POST['proyecto']);
            $alertas = $proyecto->validar();
            
            // Generar nombre de la imagen
            $nombreImagen = md5( uniqid( rand(), true ));

            // Realiza un resize a la imagen con la intervetion
            if($_FILES['proyecto']['tmp_name']['imagen']) {

                $imagen_jpg = Image::make($_FILES['proyecto']['tmp_name']['imagen'])->fit(800,900)->encode('jpg', 80);
                $imagen_webp = Image::make($_FILES['proyecto']['tmp_name']['imagen'])->fit(800,900)->encode('webp', 80);
                $imagen_jpg->orientate();
                $imagen_webp->orientate();

                $proyecto->setImagen($nombreImagen);
            }

            if(empty($alertas)) {
                if($_FILES['proyecto']['tmp_name']['imagen']) {
                    //Almacena la imagen
                    $imagen_jpg->save(CARPETA_IMAGENES . $nombreImagen . ".jpg");
                    $imagen_webp->save(CARPETA_IMAGENES . $nombreImagen . ".webp");
                }

                $resultado = $proyecto->guardar();

                if($resultado) {
                    header('Location: /admin');
                }
    
            }

        }

        $router->render('admin/proyectos/actualizar', [
            'titulo' => 'Administrar',
            'alertas' => $alertas,
            'proyecto' => $proyecto
        ]);
    }

    public static function eliminar() {

        if($_SERVER['REQUEST_METHOD']) {
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if($id) {

                $tipo = $_POST['tipo'];

                if(validarTipoContenido($tipo)) {
                    $proyecto = Proyecto::find($id);
                    $resultado = $proyecto->eliminar();
                    if($resultado) {
                        header('Location: /admin');
                    }
                }
            }
        }

    }
}
