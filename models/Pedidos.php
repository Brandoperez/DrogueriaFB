<?php 
namespace Model;

use Model\ActiveRecord;
use Model\Producto;
use PDO;
use PDOException;

class Pedidos extends ActiveRecord{
    protected static $tabla = 'orders';
    protected static $columnasDB = ['id', 'client_id', 'seller_id', 'status', 'observations', 'total'];

    public $id;
    public $client_id;
    public $seller_id;
    public $status;
    public $observations;
    public $total;
    public $created_at;
    public $productos;

    public function __construct($args = []){
       $this->id = $args['id'] ?? null;
       $this->client_id = $args['client_id'] ?? null;
       $this->seller_id = $args['seller_id'] ?? null;
       $this->status = $args['status'] ?? '';
       $this->observations = $args['observations'] ?? '';
       $this->total = $args['total'] ?? 0;
       $this->created_at = $args['created_at'] ?? null;
       $this->productos = $args['productos'] ?? [];
    }
    public static function crearConProductos(array $datosPedido, array $productos){
        try{
            self::$db->beginTransaction();
                $stmt = self::$db->prepare(
                "INSERT INTO orders (client_id, seller_id, status, observations, total)
                 VALUES (?, ?, ?, ?, ?)
                 RETURNING id" );
                 $stmt->execute([
                    $datosPedido['client_id'],
                    $datosPedido['seller_id'],
                    'pending',
                    $datosPedido['observations'],
                    $datosPedido['total']
                ]);

                
                $orderId = $stmt->fetchColumn();

                $stmtItem = self::$db->prepare(
                    "INSERT INTO order_items (order_id, product_id, quantity, price, subtotal)
                    VALUES (?, ?, ?, ?, ?)"
                );
                    foreach($productos as $item){
                    $stmtItem->execute([
                        $orderId,
                        $item['producto_id'],
                        $item['cantidad'],
                        $item['precio'],
                        $item['precio'] * $item['cantidad']
                    ]);
                }
                self::$db->commit();
                return $orderId;
        }catch(PDOException $e){
            self::$db->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }

    public static function obtenerTodos(){
        global $db;

    $query = "SELECT o.id, o.status, o.total, o.observations, o.created_at,
                     c.name AS client_name,
                     u.first_name || ' ' || u.last_name AS seller_name
              FROM orders o
              LEFT JOIN clients c ON o.client_id = c.id
              LEFT JOIN users u ON o.seller_id = u.id
              ORDER BY o.created_at DESC";

    $stmt = $db->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerDetallePedido($id){
        global $db;
        
        $query = "SELECT o.id, o.status, o.total, o.observations, o.created_at,
                    c.name AS client_name,
                    u.first_name || ' ' || u.last_name AS seller_name
                    FROM orders o
                    LEFT JOIN clients c ON o.client_id = c.id
                    LEFT JOIN users u ON o.seller_id = u.id
                    WHERE o.id = ?";
                $stmt = $db->prepare($query);
                $stmt->execute([$id]);
                $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

                if(!$pedido){
                    return null;
                }

                $queryItems = "SELECT oi.quantity, oi.price, oi.subtotal,
                                p.description AS product_name
                                FROM order_items oi
                                LEFT JOIN products p ON oi.product_id = p.id
                                WHERE oi.order_id = ?";

                                $stmtItems = $db->prepare($queryItems);
                                $stmtItems->execute([$id]);
                                $pedido['items'] = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

                                return $pedido;
     }

    public static function buscarConFiltros($filtros = []){
        global $db;

         $query = "SELECT o.id, o.status, o.total, o.observations, o.created_at,
                     c.name AS client_name,
                     u.first_name || ' ' || u.last_name AS seller_name
              FROM orders o
              LEFT JOIN clients c ON o.client_id = c.id
              LEFT JOIN users u ON o.seller_id = u.id
              WHERE 1=1";

        $params = [];

        if(!empty($filtros['fecha'])){
            $query .= " AND DATE(o.created_at) = ?";
            $params[] = $filtros['fecha'];
        }

        if(!empty($filtros['vendedor'])){
            $query .= " AND o.seller_id = ?";
            $params[] = $filtros['vendedor'];
        }

        if(!empty($filtros['cliente'])){
            $query .= " AND o.client_id = ?";
            $params[] = $filtros['cliente'];
        }

        if(!empty($filtros['estado'])){
            $query .= " AND o.status = ?";
            $params[] = $filtros['estado'];
        }

        if(!empty($filtros['buscar'])){
            $query .= " AND (
                LPAD(CAST(o.id AS TEXT), 6, '0') ILIKE ?
                OR c.name ILIKE ?
            )";

            $termino = ltrim(trim($filtros['buscar']), '#');
            $busqueda = '%' . $termino . '%';
            $params[] = $busqueda;
            $params[] = $busqueda;
        }

        $query .= " ORDER BY o.created_at DESC";

        $stmt = $db->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function validarOrden(){
        $alertas = [];
        if(!$this->client_id){
        $alertas['error'][] = 'Debes seleccionar un cliente';
            }

        if(empty($this->productos)){
            $alertas['error'][] = 'Debes agregar al menos un producto al pedido';
            }

        return $alertas;
        }

    public static function validarPedidoExcel($filas, $priceListId){
        $errores = [];
        $itemsProcesados = [];
        $total = 0;

        foreach(array_slice($filas, 1) as $index => $fila){
            $numeroFila = $index + 2;
            $codigo = trim($fila[0] ?? '');

            if(empty($codigo)){
                continue;
            }

            $cantidad = $fila[2] ?? null;
            $erroresFila = [];

            if(!is_numeric($cantidad) || $cantidad <= 0){
                $erroresFila[] = 'La cantidad no es válida';
            } 
            $producto = Producto::where('code', $codigo);
                if(!$producto){
                    $erroresFila[] = "El código {$codigo} no existe";
                }

                if(!empty($erroresFila)){
                    $errores[] = [
                        'fila' => $numeroFila,
                        'codigo' => $codigo,
                        'errores' => $erroresFila
                    ];

                    continue;
                }
                $precio = Producto::obtenerPrecioLista($producto->id, $priceListId);
                $subtotal = $precio * $cantidad;
                $total += $subtotal;

                $itemsProcesados[] = [
                    'producto_id' => $producto->id,
                    'code' => $codigo,
                    'description' => $producto->description,
                    'cantidad' => (int) $cantidad,
                    'precio' => $precio,
                    'subtotal' => $subtotal,
                    'estado' => 'Válido'
                ];
                    }

                    return [
                        'totalRegistros' => count($itemsProcesados) + count($errores),
                        'validos' => count($itemsProcesados),
                        'errores' => $errores,
                        'itemsProcesados' => $itemsProcesados,
                        'total' => $total
                    ];
    }

    public static function obtenerDatosCorreo($id){
        global $db;

        $query = "SELECT
                o.id,
                c.name,
                c.email
            FROM orders o
            INNER JOIN clients c
                ON o.client_id = c.id
            WHERE o.id = ?";

            $stmt = $db->prepare($query);
            $stmt->execute([$id]);

            return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function obtenerEstadisticas(){
        global $db;

        $query = "SELECT
                    COUNT(*) AS total,
                    COUNT(*) FILTER (WHERE status = 'pending') AS pendientes,
                    COUNT(*) FILTER (WHERE status = 'completed') AS completados,
                    COUNT(*) FILTER (WHERE status = 'cancelled') AS cancelados
                  FROM orders";

                  $stmt = $db->query($query);
                  return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function obtenerUltimos($limite = 5){
        global $db;

        $query = "SELECT o.id, o.status, o.total, o.created_at,
                         c.name AS client_name,
                         u.first_name || ' ' || u.last_name AS seller_name
                  FROM orders o
                  LEFT JOIN clients c ON o.client_id = c.id
                  LEFT JOIN users u ON o.seller_id = u.id
                  ORDER BY o.created_at DESC
                  LIMIT " . (int) $limite;

        $stmt = $db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function cambiarEstado($id, $nuevoEstado){
    global $db;

    $queryEstado = "SELECT status FROM orders WHERE id = ?";
    $stmtEstado = $db->prepare($queryEstado);
    $stmtEstado->execute([$id]);
    $estadoActual = $stmtEstado->fetchColumn();

            if(!$estadoActual){
                return false;
            }

            $transicionesPermitidas = [
                'pending' => ['confirmed', 'cancelled'],
                'confirmed' => ['completed', 'cancelled']
            ];

            if(
                !isset($transicionesPermitidas[$estadoActual]) ||
                !in_array($nuevoEstado, $transicionesPermitidas[$estadoActual])
            ){
                return false;
            }

            $query = "UPDATE orders 
                    SET status = ?
                    WHERE id = ?";

            $stmt = $db->prepare($query);
            return $stmt->execute([$nuevoEstado, $id]);
        }

        public static function existenPedidosDeCliente($clienteId){
            global $db;

            $query = "SELECT COUNT(*) FROM orders WHERE client_id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$clienteId]);

            return (int) $stmt->fetchColumn() > 0;
}
}

    




?>