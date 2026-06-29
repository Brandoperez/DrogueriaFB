<?php 
namespace Controllers;

use MVC\Router;

class AdminController{
    public static function index(Router $router){
         isRole('admin');
        
    $router->render('admin/dashboard/index', [
        'titulo' => 'Panel Administrativo',
        'descripcion' => 'Gestiona pedidos, productos, clientes y usuarios en un mismo lugar.'
    ], 'admin-layout');
    }
}



?>