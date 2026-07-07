<?php 
namespace Controllers;



use Model\Cliente;
use Model\Pedidos;
use Model\Producto;
use MVC\Router;

class AdminController{
    public static function index(Router $router){
         isRole('admin');
         $alertas = [];

         $estadisticas = Pedidos::obtenerEstadisticas();
         $ultimosPedidos = Pedidos::obtenerUltimos(5);
         $totalClientes = Cliente::contar();
         $totalProductos = Producto::contar();
        
    $router->render('admin/dashboard/index', [
        'titulo' => 'Panel Administrativo',
        'alertas' => $alertas,
        'descripcion' => 'Gestiona pedidos, productos, clientes y usuarios en un mismo lugar.',
        'estadisticas' => $estadisticas,
        'ultimosPedidos' => $ultimosPedidos,
        'totalClientes' => $totalClientes,
        'totalProductos' => $totalProductos
    ], 'admin-layout');
    }
}



?>