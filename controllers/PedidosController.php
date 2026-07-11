<?php 
namespace Controllers;

use MVC\Router;
use Model\Cliente;
use Model\Producto;
use Model\Pedidos;
use Model\Usuario;

use Classes\Email;

use PhpOffice\PhpSpreadsheet\IOFactory;

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

                self::enviarCorreoFacturacion($orderId);
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

         if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $clienteId = $_POST['cliente_id'] ?? null;
            $cliente = $clienteId ? Cliente::find($clienteId) : null;

                if(!$cliente){
                    $_SESSION['alerta'] = [
                        'tipo' => 'error',
                        'mensaje' => 'Debe seleccionar un cliente para continuar.'
                    ];
                    $router->render('admin/pedidos/excel', [
                        'titulo' => 'Carga masiva de Pedidos',
                        'step' => 1,
                        'resultado' => null
                    ], 'admin-layout');
                    return;
                }

                $archivoTemporal = $_FILES['archivo']['tmp_name'];
                $spreadsheet = IOFactory::load($archivoTemporal);
                $filas = $spreadsheet->getActiveSheet()->toArray();

                $resultado = Pedidos::validarPedidoExcel($filas, $cliente->price_list_id);

                $_SESSION['pedido_excel'] = [
                    'cliente_id' => $cliente->id,
                    'cliente_nombre' => $cliente->name,
                    'seller_id' => $cliente->seller_id,
                    'resultado' => $resultado
                ];

                $_SESSION['alerta'] = !empty($resultado['errores'])
                    ? ['tipo' => 'warning', 'mensaje' => 'El archivo tiene errores, corregilos antes de confirmar']
                    : ['tipo' => 'success', 'mensaje' => 'Archivo validado correctamente, podés confirmar la carga.'];
                     $router->render('admin/pedidos/excel', [
                        'titulo' => 'Carga masiva de Pedidos',
                        'step' => 2,
                        'resultado' => $resultado,
                        'cliente' => $cliente
                    ], 'admin-layout');
                    return;
         }
                    $router->render('admin/pedidos/excel', [
                        'titulo' => 'Carga masiva de Pedidos',
                        'step' => 1,
                        'resultado' => null
                    ], 'admin-layout');
         }

    public static function plantilla(){
        isRole('admin');

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $hoja = $spreadsheet->getActiveSheet();

                $hoja->setCellValue('A1', 'Codigo de Producto');
                $hoja->setCellValue('B1', 'Descripcion');
                $hoja->setCellValue('C1', 'Cantidad');
                $hoja->getStyle('A1:C1')->getFont()->setBold(true);

                $hoja->setCellValue('A2', 'PROD001');
                $hoja->setCellValue('B2', 'Ibuprofeno 600mg');
                $hoja->setCellValue('C2', 10);

                foreach(['A', 'B', 'C'] as $columna){
                    $hoja->getColumnDimension($columna)->setAutoSize(true);
                }
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment; filename="plantilla-pedidos.xlsx"');
                header('Cache-Control: max-age=0');

                $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save('php://output');
                exit;
    }
    public static function confirmar(Router $router){
         isRole('admin');

          if(empty($_SESSION['pedido_excel'])){
                header('Location: /admin/pedidos/excel');
                exit;
            }

            $datos = $_SESSION['pedido_excel'];
            $resultado = $datos['resultado'];

            if(!empty($resultado['errores'])){
                $_SESSION['alerta'] = ['tipo' => 'error', 'mensaje' => 'No se puede confirmar una carga con errores'];
                header('Location: /admin/pedidos/excel');
                exit;
            }

            $productos = array_map(fn($item) => [
                'producto_id' => $item['producto_id'],
                'cantidad' => $item['cantidad'],
                'precio' => $item['precio']
            ], $resultado['itemsProcesados']);

            $orderId = Pedidos::crearConProductos([
                'client_id' => $datos['cliente_id'],
                'seller_id' => $datos['seller_id'],
                'observations' => 'Pedido cargado por excel',
                'total' => $resultado['total']
            ], $productos);

            unset($_SESSION['pedido_excel']);

            if($orderId){
                self::enviarCorreoFacturacion($orderId);
                $_SESSION['alerta'] = ['tipo' => 'success', 'mensaje' => 'Pedido creado correctamente'];
                header('Location: /admin/pedidos/listado');
                exit;
            }

            $_SESSION['alerta'] = ['tipo' => 'error', 'mensaje' => 'Ocurrió un error al crear el pedido'];
            header('Location: /admin/pedidos/excel');
            exit;
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

    public static function completarPedido(){
        isRole('admin');

        $id = $_POST['id'] ?? null;
        $estado = $_POST['estado'] ?? null;

        $estadosPermitidos = ['confirmed', 'completed', 'cancelled'];

            if(!$id || !in_array($estado, $estadosPermitidos)){
                header('Content-Type: application/json');
                echo json_encode([
                    'resultado' => false
                ]);
                return;
    }

                $resultado = Pedidos::cambiarEstado($id, $estado);
                $emailEnviado = false;
                    if($resultado && $estado === 'confirmed'){
                        $datosCorreo = Pedidos::obtenerDatosCorreo($id);
                            if($datosCorreo && !empty($datosCorreo['email'])){
                                $email = new Email($datosCorreo['email'], $datosCorreo['name'], null);
                                $emailEnviado = $email->enviarConfirmacionPedido(
                                    str_pad($datosCorreo['id'], 6, '0', STR_PAD_LEFT)
                                );
                            }
                    }
                header('Content-Type: application/json');
                echo json_encode([
                    'resultado' => $resultado,
                    'estado' => $estado,
                    'email' => $emailEnviado
                ]);
    }

    private static function enviarCorreoFacturacion($orderId){
        $pedido = Pedidos::obtenerDetallePedido($orderId);

                if(!$pedido){
                return;
            }

            $email = new Email('', '', null);
            $email->enviarAvisoFacturacion($pedido);
    }

    public static function detalle(Router $router){
         isRole('admin');
         $id = $_GET['id'] ?? null;
         $pedido = $id ? Pedidos::obtenerDetallePedido($id) : null;
            if(!$pedido){
                $_SESSION['alerta'] = [
                    'tipo' => 'error',
                    'mensaje' => 'El pedido Solicitado no existe.'
                ];
                header('Location: /admin/pedidos/listado');
                exit;
            }
        $router->render('admin/pedidos/detalle', [
            'titulo' => 'Detalle del pedido',
            'pedido' => $pedido
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

    public static function crearCliente(Router $router){
        isRole('client');

        $cliente = Cliente::find($_SESSION['client_id']);
        $pedido = new Pedidos([
            'client_id' => $cliente->id,
            'observacions' => $_POST['observaciones'] ?? '',
            'productos' => json_decode($_POST['productos'] ?? '', true) ?? []
        ]);
        $alertas = [];

         if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $alertas = $pedido->validarOrden();

            if(empty($alertas)){
                $total = 0;
                foreach($pedido->productos as $item){
                    $total += $item['precio'] * $item['cantidad'];
                }

                $orderId = Pedidos::crearConProductos([
                    'client_id' => $cliente->id,
                    'seller_id' => $cliente->seller_id,
                    'observations' => $pedido->observacions,
                    'total' => $total
                ], $pedido->productos);
                

                if($orderId){
                    self::enviarCorreoFacturacion($orderId);
                    $_SESSION['alerta'] = [
                        'tipo' => 'success',
                        'mensaje' => 'Pedido cargado correctamente'
                    ];
                    header('Location: /cliente/pedidos/listado');
                    exit;
                }

                $alertas['error'][] = 'Ocurrió un error al guardar el pedido, intentá de nuevo';
            }
        }

        $router->render('cliente/pedidos/crear', [
            'titulo' => 'Nuevo Pedido',
            'cliente' => $cliente,
            'alertas' => $alertas
        ], 'cliente-layout');
    }

    public static function excelCliente(Router $router){
        isRole('client');

        $cliente = Cliente::find($_SESSION['client_id']);

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $archivoTemporal = $_FILES['archivo']['tmp_name'];
            $spreadsheet = IOFactory::load($archivoTemporal);
            $filas = $spreadsheet->getActiveSheet()->toArray();

            $resultado = Pedidos::validarPedidoExcel($filas, $cliente->price_list_id);

            $_SESSION['pedido_excel_cliente'] = [
                'cliente_id' => $cliente->id,
                'seller_id' => $cliente->seller_id,
                'resultado' => $resultado
            ];

            $_SESSION['alerta'] = !empty($resultado['errores'])
                ? ['tipo' => 'warning', 'mensaje' => 'El archivo tiene errores, corregilos antes de confirmar']
                : ['tipo' => 'success', 'mensaje' => 'Archivo validado correctamente, podés confirmar la carga.'];

            $router->render('cliente/pedidos/excel', [
                'titulo' => 'Carga masiva de Pedidos',
                'step' => 2,
                'resultado' => $resultado,
                'cliente' => $cliente
            ], 'cliente-layout');
            return;
        }
        $router->render('cliente/pedidos/excel', [
            'titulo' => 'Carga masiva de Pedidos',
            'step' => 1,
            'resultado' => null,
            'cliente' => $cliente
        ], 'cliente-layout');
    }

     public static function confirmarCliente(Router $router){
        isRole('client');

        if(empty($_SESSION['pedido_excel_cliente'])){
            header('Location: /cliente/pedidos/excel');
            exit;
        }

        $datos = $_SESSION['pedido_excel_cliente'];
        $resultado = $datos['resultado'];

        if(!empty($resultado['errores'])){
            $_SESSION['alerta'] = ['tipo' => 'error', 'mensaje' => 'No se puede confirmar una carga con errores'];
            header('Location: /cliente/pedidos/excel');
            exit;
        }

        $productos = array_map(fn($item) => [
            'producto_id' => $item['producto_id'],
            'cantidad' => $item['cantidad'],
            'precio' => $item['precio']
        ], $resultado['itemsProcesados']);

        $orderId = Pedidos::crearConProductos([
            'client_id' => $datos['cliente_id'],
            'seller_id' => $datos['seller_id'],
            'observations' => 'Pedido cargado por excel',
            'total' => $resultado['total']
        ], $productos);

        unset($_SESSION['pedido_excel_cliente']);

        if($orderId){
            self::enviarCorreoFacturacion($orderId);
            $_SESSION['alerta'] = ['tipo' => 'success', 'mensaje' => 'Pedido creado correctamente'];
            header('Location: /cliente/pedidos/listado');
            exit;
        }

        $_SESSION['alerta'] = ['tipo' => 'error', 'mensaje' => 'Ocurrió un error al crear el pedido'];
        header('Location: /cliente/pedidos/excel');
        exit;
    }

    public static function listadoCliente(Router $router){
        isRole('client');

        $pedidos = Pedidos::buscarConFiltros(['cliente' => $_SESSION['client_id']]);

        $router->render('cliente/pedidos/listado', [
            'titulo' => 'Mis Pedidos',
            'pedidos' => $pedidos
        ], 'cliente-layout');
    }

    public static function detalleCliente(Router $router){
        isRole('client');

        $id = $_GET['id'] ?? null;
        $pedido = $id ? Pedidos::obtenerDetallePedido($id) : null;

        // Verificamos que el pedido exista y pertenezca al cliente logueado
        $pedidoOwner = $id ? Pedidos::find($id) : null;

        if(!$pedido || !$pedidoOwner || (int) $pedidoOwner->client_id !== (int) $_SESSION['client_id']){
            $_SESSION['alerta'] = [
                'tipo' => 'error',
                'mensaje' => 'El pedido solicitado no existe.'
            ];
            header('Location: /cliente/pedidos/listado');
            exit;
        } 
        $router->render('cliente/pedidos/detalle', [
            'titulo' => 'Detalle del pedido',
            'pedido' => $pedido
        ], 'cliente-layout');
    }

    public static function buscarProductosCliente(){
        isRole('client');

        $termino = trim($_GET['q'] ?? '');

        if(strlen($termino) < 2){
            header('Content-Type: application/json');
            echo json_encode([]);
            return;
        }

        $cliente = Cliente::find($_SESSION['client_id']);
        $productos = Producto::buscarParaPedido($termino, $cliente->price_list_id);
        header('Content-Type: application/json');
        echo json_encode($productos);
    }

    public static function buscarPedidosCliente(){
        isRole('client');

        $filtros = [
            'fecha'    => trim($_GET['fecha'] ?? ''),
            'estado'   => trim($_GET['estado'] ?? ''),
            'buscar'   => trim($_GET['buscar'] ?? ''),
            'cliente'  => $_SESSION['client_id']
        ];

        $pedidos = Pedidos::buscarConFiltros($filtros);
        header('Content-Type: application/json');
        echo json_encode($pedidos);
    }
}




?>