<?php 
namespace Controllers;

use MVC\Router;
use Model\Cliente;
use Model\Producto;
use Model\Pedidos;
use Model\Usuario;

class PedidosController{
    public static function crear(Router $router){
         isRole('admin');
         $pedido = new Pedidos([
            'client_id' => $_POST['cliente_id'] ?? '',
            'observations' => $_POST['observacions'] ?? '',
            'productos' => json_decode($_POST['productos'] ?? '', true) ?? []
         ]);
         $alertas = [];

         if($_SERVER['REQUEST_METHOD'] === 'POST'){
           $alertas = $pedido->validarOrden();

           $cliente = $pedido->client_id ? Cliente::find($pedido->client_id) : null;
            if(!$pedido->client_id && !$cliente){
                $alertas['error'][] = 'El cliente seleccionado no es válido';
            }

            if(empty($alertas)){
            $total = 0;
            foreach($pedido->productos as $item){
                $total += $item['precio'] * $item['cantidad'];
            }

            $orderId = Pedidos::crearConProductos([
                'client_id' => $cliente->id,
                'seller_id' => $cliente->seller_id,
                'observacions' => $pedido->observacions,
                'total' => $total
            ], $pedido->productos);

            if($orderId){
                $_SESSION['alerta'] = [
                    'tipo' => 'success',
                    'mensaje' => 'Pedido cargado correctamente'
                ];
                header('Location: /admin/pedidos/listado');
                exit;
            }
             $alertas['error'][] = 'Ocurrió un error al guardar el pedido, intentá de nuevo';
            }     
    }

        $router->render('admin/pedidos/crear', [
            'titulo' => 'Carga de Pedido Manual',
            'alertas' => $alertas
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
        $priceListId = $_GET['price_list_id'] ?? null;
         $priceListId = ($priceListId !== null && $priceListId !== '' && ctype_digit((string)$priceListId)) ? (int) $priceListId : null;

            if(strlen($termino) < 2){
                header('Content-Type: application/json');
                echo json_encode([]);
                return;
            }
            $productos = Producto::buscarParaPedido($termino, $priceListId);
            header('Content-Type: application/json');
            echo json_encode($productos);
    }

    public static function buscarPedidos(){
        isRole('admin');
            $filtros = [
                'fecha'    => trim($_GET['fecha'] ?? ''),
                'vendedor' => trim($_GET['vendedor'] ?? ''),
                'cliente'  => trim($_GET['cliente'] ?? ''),
                'estado'   => trim($_GET['estado'] ?? ''),
                'buscar'   => trim($_GET['buscar'] ?? '')
            ];
            $pedidos = Pedidos::buscarConFiltros($filtros);
            header('Content-Type: application/json');
            echo json_encode($pedidos);
    }

    public static function resultado(Router $router){
         isRole('admin');
        $router->render('admin/pedidos/resultado', [
            'titulo' => 'Resultado'
        ], 'admin-layout');
    }

    public static function listado(Router $router){
         isRole('admin');

         $pedidos = Pedidos::obtenerTodos();
         $vendedores = Usuario::whereAll('role', 'seller');
         $clientes = Cliente::all();
        $router->render('admin/pedidos/listado', [
            'titulo' => 'Listado de Pedidos',
            'pedidos' => $pedidos,
            'vendedores' => $vendedores,
            'clientes' => $clientes
        ], 'admin-layout');
    }
}




?>