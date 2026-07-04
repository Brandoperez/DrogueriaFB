<?php 
namespace Model;

use Model\ActiveRecord;
use PDO;
use PDOException;

class Pedidos extends ActiveRecord{
    protected static $tabla = 'orders';
    protected static $columnasDB = ['id', 'client_id', 'seller_id', 'status', 'observations', 'total'];

    public $id;
    public $client_id;
    public $seller_id;
    public $status;
    public $observacions;
    public $total;
    public $created_at;
    public $productos;

    public function __construct($args = []){
       $this->id = $args['id'] ?? null;
       $this->client_id = $args['client_id'] ?? null;
       $this->seller_id = $args['seller_id'] ?? null;
       $this->status = $args['status'] ?? '';
       $this->observacions = $args['observacions'] ?? '';
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

    public static function buscarConFiltros(){
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
                CAST(o.id AS TEXT) ILIKE ?
                OR c.name ILIKE ?
            )";

            $busqueda = '%' . $filtros['buscar'] . '%';
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
    }




?>