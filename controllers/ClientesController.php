<?php 
namespace Controllers;

use Model\Cliente;
use Model\PriceList;
use Model\Usuario;
use MVC\Router;
use PDO;


class ClientesController{
    public static function index(Router $router){
         isRole('admin');
         $clientes = Cliente::all();
         $vendedores = Usuario::whereAll('role', 'seller');
         $listaPrecios = PriceList::all();
         $provincias = Cliente::getProvincias();

    $router->render('admin/clientes/index', [
        'titulo' => 'Clientes',
        'clientes' => $clientes,
        'vendedores' => $vendedores,
        'listaPrecio' => $listaPrecios,
        'provincias' => $provincias
    ], 'admin-layout');
    }

     public static function crear(Router $router) {
        isRole('admin');
        
        $alertas = [];
        $cliente = new Cliente();
        $vendedores = Usuario::whereAll('role', 'seller');
        $listaPrecios = PriceList::all();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $cliente->sincronizar($_POST);

            $cliente->cuit = trim($cliente->cuit);
            $cliente->email = strtolower(trim($cliente->email));
            $alertas = $cliente->validarNuevoCliente();


            if(empty($alertas)) {
                $existeCliente = Cliente::where('cuit', $cliente->cuit);

                if($existeCliente) {
                    Cliente::setAlerta('error', 'El Cuit ya se usó para otro Cliente');
                    $alertas = Cliente::getAlertas();
                } else {
                    $cliente->active = true;
                    $cliente->hashPassword();
                    unset($cliente->password2);
                    $cliente->crearToken();
                    $resultado =  $cliente->guardar();

                    if($resultado) {
                        header('Location: /admin/clientes');
                        exit;
                    }
                }
            }
        }

        // Render a la vista
        $router->render('admin/clientes/crear', [
            'titulo' => 'Nuevo Cliente',
            'cliente' => $cliente, 
            'alertas' => $alertas,
            'vendedores' => $vendedores,
            'listaPrecios' => $listaPrecios
        ]);
    }

    public static function ver(Router $router){
        isRole('admin');
        
        $id = $_GET['id'] ?? null;
            if(!$id || !is_numeric($id)){
                header('Location: /admin/clientes');
                exit;
            }
        
        $cliente = Cliente::find($id);
            if(!$cliente){
                header('Location: /admin/clientes');
                exit;
            }

    $router->render('admin/clientes/ver', [
        'titulo' => 'Detalle Cliente', 
        'cliente' => $cliente
    ], 'admin-layout');
    }

    public static function editar(Router $router){
        isRole('admin');
        
        $id = $_GET['id'] ?? null;
            if(!$id || !is_numeric($id)){
                header('Location: /admin/clientes');
                exit;
            }
        $cliente = Cliente::find($id);
            if(!$cliente){
                header('Location: /admin/clientes');
                exit;
            }

        $alertas = [];
        $vendedores = Usuario::whereAll('role', 'seller');
        $listaPrecios = PriceList::all();

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $cliente->sincronizar($_POST);
            $alertas = $cliente->validarEdicionCliente();

            $clienteExistente = Cliente::where('cuit', $cliente->cuit);
                if($clienteExistente && $clienteExistente->id != $cliente->id) {
                    $alertas['error'][] = 'Ya existe un cliente con ese CUIT';
                }
            
            $clienteEmail = Cliente::where('email', $cliente->email);
                if($clienteEmail && $clienteEmail->id != $cliente->id) {
                    $alertas['error'][] = 'Ya existe un cliente con ese correo electrónico';
                }


                if(empty($alertas)){
                    $resultado = $cliente->guardar();
                    if($resultado){
                        header('Location: /admin/clientes');
                        exit;
                    }
                }
        }

        $router->render('admin/clientes/editar', [
            'titulo' => 'Editar Cliente',
            'alertas' => $alertas,
            'cliente' => $cliente,
            'vendedores' => $vendedores,
            'listaPrecios' => $listaPrecios
        ]);
        }

        public static function estado(Router $router){
             isRole('admin');

        $id = $_GET['id'] ?? null;
            if(!$id || !is_numeric($id)){
                echo json_encode(['resultado' => false]);
                return;
            }
        $cliente = Cliente::find($id);
            if(!$cliente){
                echo json_encode(['resultado' => false]);
                return;
            }

            // toggle estado
            $cliente->active = !$cliente->active;
            $resultado = $cliente->guardar();

            echo json_encode([
                'resultado' => $resultado,
                'nuevo_estado' => $cliente->active
            ]);
        }

        public static function buscar(Router $router){
            isRole('admin');

            $datos = json_decode(file_get_contents('php://input'), true);

            global $db;

            $query = "SELECT c.*, 
                     u.first_name || ' ' || u.last_name AS seller_name,
                     pl.name AS price_list_name
                        FROM clients c
                        LEFT JOIN users u ON c.seller_id = u.id
                        LEFT JOIN price_lists pl ON c.price_list_id = pl.id
                        WHERE 1=1";
            $params = [];

            //BUSCAR POR NOMBRE O CUIT
            if (!empty($datos['buscar'])) {
                $query .= " AND (c.name ILIKE :buscar OR c.cuit ILIKE :buscar2)";
                $params[':buscar']  = "%{$datos['buscar']}%";
                $params[':buscar2'] = "%{$datos['buscar']}%";
            }

            // VENDEDOR
            if (!empty($datos['vendedor'])) {
                $query .= " AND seller_id = :vendedor";
                $params[':vendedor'] = $datos['vendedor'];
            }

            // ESTADO
            if (isset($datos['estado']) && $datos['estado'] !== '') {
                $query .= " AND c.active = :estado::boolean";
                $params[':estado'] = $datos['estado'] === '1' ? 'true' : 'false';
            }

            //LOCALIDAD
            if (!empty($datos['localidad'])) {
                $query .= " AND c.province ILIKE :localidad";
                $params[':localidad'] = "%{$datos['localidad']}%";
            }

            $stmt = $db->prepare($query);
            $stmt->execute($params);
            $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            header('Content-Type: application/json');
            echo json_encode($clientes);
        }

        public static function eliminar(Router $router){
            isRole('admin');

            $id = $_GET['id'] ?? null;
                if(!$id || !is_numeric($id)){
                    header('Location: /admin/clientes');
                    exit;
                }
            $cliente = Cliente::find($id);
                if(!$cliente){
                    header('Location: /admin/clientes');
                    exit;
                }

            $resultado = $cliente->eliminar();
            if($resultado){
                header('Location: /admin/clientes');
                exit;
            }
        }
}



?>