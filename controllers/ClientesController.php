<?php 
namespace Controllers;

use Model\Cliente;
use Model\PriceList;
use Model\Usuario;
use Model\Pedidos;
use Model\Producto;
use MVC\Router;
use PDO;
use PhpOffice\PhpSpreadsheet\IOFactory;


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
        $provincias = Cliente::getProvincias();

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
                        $_SESSION['alerta'] = [
                            'tipo' => 'success',
                            'mensaje' => 'Cliente creado correctamente'
                        ];
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
            'listaPrecios' => $listaPrecios,
            'provincias' => $provincias
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
        $provincias = Cliente::getProvincias();

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
                        $_SESSION['alerta'] = [
                                'tipo' => 'success',
                                'mensaje' => 'Cliente actualizado correctamente'
                            ];
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
            'listaPrecios' => $listaPrecios,
            'provincias' => $provincias
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

                if(Pedidos::existenPedidosDeCliente($cliente->id)){
                    $_SESSION['alerta'] = [
                        'tipo' => 'error',
                        'mensaje' => 'No se puede eliminar: el cliente tiene pedidos cargados. Podés desactivarlo en su lugar.'
                    ];
                    header('Location: /admin/clientes');
                    exit;
                }

            $resultado = $cliente->eliminar();
            if($resultado){
                $_SESSION['alerta'] = [
                    'tipo' => 'success',
                    'mensaje' => 'Cliente eliminado correctamente'
                ];
                header('Location: /admin/clientes');
                exit;
            }
        }

        public static function buscarLocalidades(){
            isRole('admin');

            $provincia = $_GET['provincia'] ?? '';

            if(!$provincia){
                header('Content-Type: application/json');
                echo json_encode([]);
                return;
            }

            $url = 'https://apis.datos.gob.ar/georef/api/localidades?provincia=' . urlencode($provincia) . '&campos=nombre&max=1000&orden=nombre';

            $respuesta = @file_get_contents($url);
            $localidades = [];

            if($respuesta){
                $datos = json_decode($respuesta, true);
                foreach(($datos['localidades'] ?? []) as $localidad){
                    $localidades[] = $localidad['nombre'];
                }
                $localidades = array_unique($localidades);
                sort($localidades);
            }

            header('Content-Type: application/json');
            echo json_encode(array_values($localidades));
        }

        public static function clientes(Router $router){
            isRole('client');

                $cliente = Cliente::find($_SESSION['client_id']);
                $vendedor = $cliente->vendedor();
                $listaPrecios = $cliente->listaPrecios();
                $pedidos = Pedidos::buscarConFiltros(['cliente' => $cliente->id]);
                $ultimosPedidos = array_slice($pedidos, 0, 5);

            $router->render('cliente//index', [
                'titulo' => 'Mis Pedidos',
                'cliente' => $cliente,
                'vendedor' => $vendedor,
                'listaPrecios' => $listaPrecios,
                'pedidos' => $ultimosPedidos
            ], 'cliente-layout');
        }

        public static function descargarListaPrecios(){
            isRole('client');

            $cliente = Cliente::find($_SESSION['client_id']);
            $productos = Producto::obtenerListaPrecios($cliente->price_list_id);

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $hoja = $spreadsheet->getActiveSheet();

            $hoja->setCellValue('A1', 'Codigo');
            $hoja->setCellValue('B1', 'Descripcion');
            $hoja->setCellValue('C1', 'Laboratorio');
            $hoja->setCellValue('D1', 'Stock');
            $hoja->setCellValue('E1', 'Precio');
            $hoja->getStyle('A1:E1')->getFont()->setBold(true);

            $fila = 2;
            foreach($productos as $producto){
                $hoja->setCellValue('A' . $fila, $producto['code']);
                $hoja->setCellValue('B' . $fila, $producto['description']);
                $hoja->setCellValue('C' . $fila, $producto['laboratory']);
                $hoja->setCellValue('D' . $fila, $producto['stock']);
                $hoja->setCellValue('E' . $fila, $producto['precio']);
                $fila++;
            }

            foreach(['A', 'B', 'C', 'D', 'E'] as $columna){
                $hoja->getColumnDimension($columna)->setAutoSize(true);
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="lista-precios.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            exit;
}
}



?>