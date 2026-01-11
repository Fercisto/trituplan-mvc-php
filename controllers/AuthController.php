<?php

namespace Controllers;

use MVC\Router;
use Model\Usuario;

class AuthController {

    public static function login(Router $router) {

        $inciado = $_SESSION['email'] ?? '';
        
        if($_SERVER['PATH_INFO'] === '/login' && $inciado) {
            header('Location: /admin?seccion=proyectos&page=1');
            exit();
        }

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarLogin();

            if(empty($alertas)) {
                // Verificar si el usuario existe
                $usuario = Usuario::where('email', $usuario->email);
                if(!$usuario) {
                    Usuario::setAlerta('error', 'El usuario no existe');
                } else {

                    if(password_verify($_POST['password'], $usuario->password)) {
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;
                        header('Location: /admin?seccion=proyectos&page=1');
                        exit();
                    } else {
                        Usuario::setAlerta('error', 'Password Incorrecto');
                    }

                }

            }
        }

        $alertas = Usuario::getAlertas();
               
        // Render a la vista 
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas
        ]);
    }

    public static function logout() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            $_SESSION = [];
            header('Location: /login');
            exit();
        }
    }

}