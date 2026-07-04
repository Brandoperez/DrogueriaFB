<?php 
namespace Controllers;

use MVC\Router;
use Model\Cliente;
use Model\Producto;

class PedidosController{
    public static function crear(Router $router){
         isRole('admin');

        $router->render('admin/pedidos/crear', [
            'titulo' => 'Carga de Pedido Manual',
        ], 'admin-layout');
    }

    public static function excel(Router $router){
         isRole('admin');

        $router->render('admin/pedidos/excel', [
            'titulo' => 'Carga masiva de pedidos con excel'
        ], 'admin-layout');
    }

    public static function confirmar(Router $router){
         isRole('admin');

    $router->render('admin/pedidos/confirmar', [
        'titulo' => 'Confirmar Pedido',
    ], 'admin-layout');
    }

    public static function buscarClientes(){
        isRole('admin');

        $termino = $_GET['q'] ?? '';
        $termino = trim($termino);

            if(strlen($termino) < 2){
                header('Content-Type: application/json');
                echo json_encode([]);
                return;
            }
            $clientes = Cliente::buscarParaPedido($termino);
            header('Content-Type: application/json');
            echo json_encode($clientes);
    }

    public static function buscarProductos(){
        isRole('admin');

        $termino = $_GET['q'] ?? '';
        $termino = trim($termino);

            if(strlen($termino) < 2){
                header('Content-Type: application/json');
                echo json_encode([]);
                return;
            }
            $productos = Producto::buscarParaPedido($termino);
            header('Content-Type: application/json');
            echo json_encode($productos);
    }

    public static function resultado(Router $router){
         isRole('admin');
        $router->render('admin/pedidos/resultado', [
            'titulo' => 'Resultado'
        ], 'admin-layout');
    }

    public static function listado(Router $router){
         isRole('admin');
        $router->render('admin/pedidos/listado', [
            'titulo' => 'Listado de Pedidos'
        ], 'admin-layout');
    }
}




?>