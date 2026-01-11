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

            // Variables para almacenar las imágenes procesadas
            $imagen_jpg = null;
            $imagen_webp = null;
            $nombreImagen = null;

            // Realiza un resize a la imagen con la intervetion
            if($_FILES['proyecto']['tmp_name']['imagen']) {

                $tipoImagen = $_FILES['proyecto']['type']['imagen'];

                // Verificar si el tipo de imagen es PNG
                if ($tipoImagen === 'image/jpeg' || $tipoImagen === 'image/jpg') {

                    // Generar nombre de la imagen
                    $nombreImagen = md5( uniqid( rand(), true ));

                    $imagen_jpg = Image::make($_FILES['proyecto']['tmp_name']['imagen'])->fit(800,900)->encode('jpg', 80);
                    $imagen_webp = Image::make($_FILES['proyecto']['tmp_name']['imagen'])->fit(800,900)->encode('webp', 80);
                    $imagen_jpg->orientate();
                    $imagen_webp->orientate();

                    // Asignar temporalmente el nombre para que pase la validación
                    $proyecto->setImagen($nombreImagen);

                } else {
                    Proyecto::setAlerta('error', 'La imagen debe ser en formato JPG/JPEG');
                }

            }

            $alertas = $proyecto->validar();

            if(empty($alertas)) {

                // Solo guardar la imagen si pasó la validación
                if($nombreImagen) {

                    // Crear la carpeta
                    if(!is_dir(CARPETA_IMAGENES)) {
                        mkdir(CARPETA_IMAGENES, 0755, true);
                    }

                    // Guarda la imagen en el servidor
                    $imagen_jpg->save(CARPETA_IMAGENES . $nombreImagen . ".jpg");
                    $imagen_webp->save(CARPETA_IMAGENES . $nombreImagen . ".webp");
                }

                $resultado = $proyecto->guardar();

                if($resultado) {
                    header('Location: /admin?seccion=proyectos&page=1');
                }
            } else {
                // Si hay errores, limpiar la imagen temporal del modelo
                $proyecto->imagen = '';
            }

        } 

        $alertas = Proyecto::getAlertas();

        $router->render('admin/proyectos/crear', [
            'titulo' => 'Crear Proyecto',
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

            // Variables para almacenar las imágenes procesadas
            $imagen_jpg = null;
            $imagen_webp = null;
            $nombreImagen = null;

            // Realiza un resize a la imagen con la intervetion
            if($_FILES['proyecto']['tmp_name']['imagen']) {

                $tipoImagen = $_FILES['proyecto']['type']['imagen'];

                // Verificar si el tipo de imagen es válido
                if ($tipoImagen === 'image/jpeg' || $tipoImagen === 'image/jpg') {

                    // Generar nombre de la imagen
                    $nombreImagen = md5( uniqid( rand(), true ));

                    $imagen_jpg = Image::make($_FILES['proyecto']['tmp_name']['imagen'])->fit(800,900)->encode('jpg', 80);
                    $imagen_webp = Image::make($_FILES['proyecto']['tmp_name']['imagen'])->fit(800,900)->encode('webp', 80);
                    $imagen_jpg->orientate();
                    $imagen_webp->orientate();

                    // Asignar temporalmente el nombre para que pase la validación
                    $proyecto->setImagen($nombreImagen);

                } else {
                    Proyecto::setAlerta('error', 'La imagen debe ser en formato JPG/JPEG');
                }
            }

            $alertas = $proyecto->validar();

            if(empty($alertas)) {

                // Solo guardar la imagen si pasó la validación
                if($nombreImagen) {

                    // Crear la carpeta si no existe
                    if(!is_dir(CARPETA_IMAGENES)) {
                        mkdir(CARPETA_IMAGENES, 0755, true);
                    }

                    // Almacena la imagen
                    $imagen_jpg->save(CARPETA_IMAGENES . $nombreImagen . ".jpg");
                    $imagen_webp->save(CARPETA_IMAGENES . $nombreImagen . ".webp");
                } else {
                    // Si no hay nueva imagen, restaurar la imagen original
                    $imagenOriginal = Proyecto::find($proyecto->id);
                    $proyecto->imagen = $imagenOriginal->imagen;
                }

                $resultado = $proyecto->guardar();

                if($resultado) {
                    header('Location: /admin?seccion=proyectos&page=1');
                }

            } else {
                // Si hay errores y se intentó subir nueva imagen, restaurar la imagen original
                if($nombreImagen) {
                    $imagenOriginal = Proyecto::find($proyecto->id);
                    $proyecto->imagen = $imagenOriginal->imagen;
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
                        header('Location: /admin?seccion=proyectos&page=1');
                    }
                }
            }
        }

    }
}
