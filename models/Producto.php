<?php 
namespace Model;
use Model\ActiveRecord;
use PDO;

class Producto extends ActiveRecord{
    protected static $tabla = 'products';
    protected static $columnasDB = ['id', 'code', 'description', 'laboratory', 'monodrug', 'base_price', 'stock','active'];

    public $id;
    public $code;
    public $description;
    public $laboratory;
    public $monodrug;
    public $base_price;
    public $stock;
    public $active;
    public $created_at;
    public $updated_at;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->code = $args['code'] ?? '';
        $this->description = $args['description'] ?? '';
        $this->laboratory = $args['laboratory'] ?? '';
        $this->monodrug = $args['monodrug'] ?? '';
        $this->base_price = $args['base_price'] ?? 0;
        $this->stock = $args['stock'] ?? 0;
        $this->active = $args['active'] ?? true;
        $this->created_at = $args['created_at'] ?? null;
        $this->updated_at = $args['updated_at'] ?? null;
    }

    public static function buscarParaPedido($termino, $priceListId = null) {
        global $db;

         $query = "SELECT p.id, p.code, p.description, p.laboratory, p.stock,
                COALESCE(
                pp.price - (pp.price * pp.discount_percentage / 100),
                p.base_price
            ) AS precio
            FROM products p
            LEFT JOIN product_prices pp
                ON pp.product_id = p.id
                AND pp.price_list_id = :price_list_id
            WHERE p.active = true
            AND (
                p.code ILIKE :termino
                OR p.description ILIKE :termino
                OR p.laboratory ILIKE :termino
            )
            ORDER BY p.description ASC
            LIMIT 10";

        $stmt = $db->prepare($query);
        $stmt->execute([
            ':termino' => "%{$termino}%",
            ':price_list_id' => $priceListId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerPrecioLista($productId, $priceListId){
        global $db;

        $query = "SELECT COALESCE(
                    pp.price - (pp.price * pp.discount_percentage / 100),
                    p.base_price
                    ) AS precio
                    FROM products p
                    LEFT JOIN product_prices pp
                        ON pp.product_id = p.id AND pp.price_list_id = ?
                    WHERE p.id = ?";

                    $stmt = $db->prepare($query);
                    $stmt->execute([$priceListId, $productId]);

                    return (float) $stmt->fetchColumn();
    }

    public static function obtenerListaPrecios($priceListId){
    global $db;

    $query = "SELECT p.code, p.description, p.laboratory, p.stock,
            COALESCE(
                pp.price - (pp.price * pp.discount_percentage / 100),
                p.base_price
            ) AS precio
            FROM products p
            LEFT JOIN product_prices pp
                ON pp.product_id = p.id
                AND pp.price_list_id = :price_list_id
            WHERE p.active = true
            ORDER BY p.description ASC";

    $stmt = $db->prepare($query);
    $stmt->execute([':price_list_id' => $priceListId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}



?>