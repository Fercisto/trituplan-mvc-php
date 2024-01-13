<?php 

require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\AuthController;
use Controllers\AdminController;
use Controllers\MensajeController;
use Controllers\PaginasController;
use Controllers\CatalogoController;
use Controllers\ProyectoController;

$router = new Router();


// Login
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);

// Área privada
$router->get('/admin', [AdminController::class, 'index']);

$router->post('/admin/mensaje/eliminar', [MensajeController::class, 'eliminar']);

$router->get('/admin/proyecto/crear', [ProyectoController::class, 'crear']);
$router->post('/admin/proyecto/crear', [ProyectoController::class, 'crear']);
$router->get('/admin/proyecto/actualizar', [ProyectoController::class, 'actualizar']);
$router->post('/admin/proyecto/actualizar', [ProyectoController::class, 'actualizar']);
$router->post('/admin/proyecto/eliminar', [ProyectoController::class, 'eliminar']);

// Área pública
$router->get('/', [PaginasController::class, 'index']);
$router->get('/nosotros', [PaginasController::class, 'nosotros']);
$router->get('/proyectos', [PaginasController::class, 'proyectos']);
$router->get('/proyecto', [PaginasController::class, 'proyecto']);
$router->get('/contacto', [PaginasController::class, 'contacto']);
$router->get('/servicios', [PaginasController::class, 'servicios']);
$router->get('/ayuda', [PaginasController::class, 'ayuda']);
$router->get('/contacto', [PaginasController::class, 'contacto']);
$router->post('/contacto', [PaginasController::class, 'contacto']);
$router->get('/404', [PaginasController::class, 'error']);

$router->comprobarRutas();