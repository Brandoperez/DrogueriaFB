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
            $resultado = self::validarProductosExcel($filas);

           $_SESSION['productos_excel'] = [
            'archivo' => $_FILES['archivo']['name'],
            'filas' => $filas,
            'resultado' => $resultado
           ];


                if(!empty($resultado['errores'])){
                    $_SESSION['alerta'] = [
                    'tipo' => 'warning',
                    'mensaje' => 'El archivo tiene errores, corregilos antes de confirmar'];
                }else{
                    $_SESSION['alerta'] = [
                    'tipo' => 'success',
                    'mensaje' => 'Archivo validado correctamente, podés confirmar la carga.'];
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

    private static function validarProductosExcel(array $filas){
        $errores = [];
        $nuevos = 0;
        $actualizados = 0;
        $productosProcesados = [];

            foreach(array_slice($filas, 1) as $index => $fila){
                $numeroFila = $index + 2;
                    if(empty(trim($fila[0] ?? ''))){
                        continue;
                    }

                        $codigo = trim($fila[0] ?? '');
                        $descripcion = trim($fila[1] ?? '');
                        $laboratorio = trim($fila[2] ?? '');
                        $monodroga = trim($fila[3] ?? '');
                        $precioLista1 = $fila[4] ?? null;
                        $precioLista2 = $fila[5] ?? null;
                        $precioLista3 = $fila[6] ?? null;
                        $stock = $fila[7] ?? null;
                        $precioBase = $fila[8] ?? null;

                        $erroresFila = [];
                            if(!$descripcion){
                                $erroresFila[] = 'La descripcion es Obligatoria';
                            }
                            if(!is_numeric($precioLista1)) {
                                $erroresFila[] = 'El precio de Lista 1 no es válido';
                            }

                            if(!is_numeric($precioLista2)) {
                                $erroresFila[] = 'El precio de Lista 2 no es válido';
                            }

                            if(!is_numeric($precioLista3)) {
                                $erroresFila[] = 'El precio de Lista 3 no es válido';
                            }

                            if(!is_numeric($stock)) {
                                $erroresFila[] = 'El stock no es válido';
                            }

                            if(!is_numeric($precioBase)) {
                                $erroresFila[] = 'El precio base no es válido';
                            }

                            $producto = Producto::where('code', $codigo);
                            if($producto){
                                $actualizados++;
                                $estado = 'Actualización';
                            }else{
                                $nuevos++;
                                $estado = 'Nuevo';
                            }

                            if(!empty($erroresFila)){
                                $errores[] = [
                                    'fila' => $fila,
                                    'codigo' => $codigo,
                                    'errores' => $erroresFila
                                ];
                                $estado = 'Error';
                            }

                            $productosProcesados[] = [
                                'code' => $codigo,
                                'description' => $descripcion,
                                'laboratory' => $laboratorio,
                                'price' => $precioBase,
                                'stock' => $stock,
                                'estado' => $estado
                            ];
            }
            return [
                    'totalRegistros' => count($productosProcesados),
                    'nuevos' => $nuevos,
                    'actualizados' => $actualizados,
                    'errores' => $errores,
                    'productosProcesados' => array_slice($productosProcesados, 0, 10),
                    'todosLosProductos' => $productosProcesados
                ];
    }
    

    public static function confirmar(Router $router){
         isRole('admin');
            if(empty($_SESSION['productos_excel'])){
                header('Location: /admin/productos/excel');
                exit;
            }

            $datosExcel = $_SESSION['productos_excel'];
            $resultadoValidacion = $datosExcel['resultado'];
                if(!empty($resultadoValidacion['errores'])){
                    $_SESSION['alerta'] = [
                        'tipo' => 'error',
                        'mensaje' => 'No se puede confirmar una carga con errores'
                    ];

                    header('Location: /admin/productos/excel');
                    exit;
                }

                $resultado = self::ImportarProductos($datosExcel['filas']);
                self::registrarImportacion(
                    $datosExcel['archivo'], 
                    $resultado
                );

                unset($_SESSION['productos_excel']);
                $_SESSION['alerta'] = [
                    'tipo' => 'success',
                    'mensaje' => 'Archivo procesado correctamente.'
                ];

                header('Location: /admin/dashboard');
                exit;
    }

    public static function preview(Router $router){
         isRole('admin');

         if(empty($_SESSION['productos_excel'])){
            header('Location: /admin/productos/excel');
            exit;
         }

         $resultado = $_SESSION['productos_excel']['resultado'];

        $router->render('admin/productos/preview', [
            'titulo' => 'Vista Previa de Productos',
            'step' => 3,
            'productos' => $resultado['todosLosProductos'] ?? [],
            'resultado' => $resultado
        ], 'admin-layout');
    }

    public static function listado(Router $router){
         isRole('admin');
         $importaciones = ImportacionProducto::obtenerHistorial();

        $router->render('admin/productos/listado', [
            'titulo' => 'Historial de Cargas',
            'importaciones' => $importaciones
        ], 'admin-layout');
    }
}





?>