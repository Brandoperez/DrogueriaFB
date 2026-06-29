<?php 
namespace Controllers;

use Model\ImportacionProducto;
use Model\Producto;
use MVC\Router;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Model\PrecioProducto;

class ProductosController{
    public static function excel(Router $router){
         isRole('admin');
         

         if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $archivoTemporal = $_FILES['archivo']['tmp_name'];
            $spreadsheet = IOFactory::load($archivoTemporal);
            
            $hoja = $spreadsheet->getActiveSheet();
            $filas = $hoja->toArray();
            $resultado = self::ImportarProductos($filas);

            self::registrarImportacion(
                $_FILES['archivo']['name'],$resultado
            );


                if(!empty($resultado['errores'])){
                    $_SESSION['alerta'] = [
                    'tipo' => 'warning',
                    'mensaje' => 'Importación completada con errores: ' . count($resultado['errores'])
                ];
                ;}else{
                    $_SESSION['alerta'] = [
                    'tipo' => 'success',
                    'mensaje' => 'Archivo importado correctamente. Productos Nuevos: ' . $resultado['nuevos'] . ' / Actualizados: ' . $resultado['actualizados']];
                }

                $router->render('/admin/productos/excel', [
                    'titulo' => 'Carga masiva de Productos',
                    'step' => 2,
                    'resultado' => $resultado
                ], 'admin-layout');
                return;
         }

        $router->render('admin/productos/excel', [
            'titulo' => 'Carga masiva de Productos',
            'step' => 1,
            'resultado' => null
        ], 'admin-layout');
    }

    private static function ImportarProductos(array $filas){
        $errores = [];
        $nuevos = 0;
        $actualizados = 0;
        $productosProcesados = [];

        foreach(array_slice($filas, 1) as $fila){
            if(empty(trim($fila[0]))){
                continue;
            }
                $codigo = trim($fila[0]);
                $producto = Producto::where('code', $codigo);
                    if(!$producto){
                        $producto = new Producto([
                            'code' => $codigo,
                            'description' => trim($fila[1]),
                            'laboratory' => trim($fila[2]),
                            'monodrug' => trim($fila[3]),
                            'base_price' => (float)$fila[8],
                            'stock' => (int)$fila[7],
                            'active' => true
                        ]);
                            $producto->guardar();
                            $nuevos++;
                    }else{
                        $producto->description = trim($fila[1]);
                        $producto->laboratory = trim($fila[2]);
                        $producto->monodrug = trim($fila[3]);
                        $producto->base_price = (float)$fila[8];
                        $producto->stock = (int)$fila[7];

                        $producto->guardar();
                        $actualizados++;
                    }

                    self::ImportarPrecios($producto, $fila);
                    $productosProcesados[] = [
                        'code' => $producto->code,
                        'description' => $producto->description,
                        'laboratory' => $producto->laboratory,
                        'price' => $producto->base_price,
                        'stock' => $producto->stock,
                        'estado' => 'Válido'
                    ];
        }
        $totalRegistros = $nuevos + $actualizados;
        $productosProcesados = array_slice($productosProcesados, 0, 10);

        return [
            'totalRegistros' => $totalRegistros,
            'nuevos' => $nuevos,
            'actualizados' => $actualizados,
            'errores' => $errores,
            'productosProcesados' => $productosProcesados
        ];
    }

    private static function ImportarPrecios(Producto $producto, array $fila){
        $precios = [
            1 => [ 'price_list_id' => 1, 'price' => (float)$fila[4], 'discount' => (float)$fila[9]],
            2 => [ 'price_list_id' => 2, 'price' => (float)$fila[5], 'discount' => (float)$fila[10]],
            3 => [ 'price_list_id' => 3, 'price' => (float)$fila[6], 'discount' => (float)$fila[11]],
        ];

        foreach($precios as $listaId => $data){
            $precio = PrecioProducto::whereTwo('product_id', $producto->id, 'price_list_id', $listaId);

                if(!$precio){
                    $precio = new PrecioProducto([
                        'product_id' => $producto->id,
                        'price_list_id' => $listaId,
                        'price' => $data['price'],
                        'discount_percentage' => $data['discount']
                    ]);
                }else{
                    $precio->price = $data['price'];
                    $precio->discount_percentage = $data['discount'];
                }
                $precio->guardar();
        }
    }

    private static function registrarImportacion($archivo, $resultado){
        
            $importar = new ImportacionProducto([
                'file_name' => $archivo,
                'user_id' => $_SESSION['id'],
                'total_records' => $resultado['nuevos'] + $resultado['actualizados'],
                'new_products' => $resultado['nuevos'],
                'updated_products' => $resultado['actualizados'],
                'errors_count' => 0,
                'status' => 'completed'
            ]); 
            $importar->guardar();
    }
    

    public static function confirmar(Router $router){
         isRole('admin');

    $router->render('admin/productos/confirmar', [
        'titulo' => 'Confirmar Producto',
    ], 'admin-layout');
    }

    public static function resultado(Router $router){
         isRole('admin');
        $router->render('admin/productos/resultado', [
            'titulo' => 'Resultado de Importación'
        ], 'admin-layout');
    }

    public static function listado(Router $router){
         isRole('admin');
        $router->render('admin/productos/listado', [
            'titulo' => 'Historial de Cargas'
        ], 'admin-layout');
    }
}





?>