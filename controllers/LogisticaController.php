<?php 
namespace Controllers;

use Model\Usuario;
use MVC\Router;

class LogisticaController{
    public static function index(Router $router){
         isRole('admin');

         $usuarios = Usuario::all();

    $router->render('admin/usuarios/index', [
        'titulo' => 'Usuarios',
        'usuarios' => $usuarios
    ], 'admin-layout');
    }
}



?>