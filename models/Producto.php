<?php 
namespace Model;
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

    public static function buscarParaPedido($termino) {
        global $db;

        $query = "SELECT id, code, description, laboratory, stock
                FROM products
                WHERE active = true
                AND (
                    code ILIKE :termino
                    OR description ILIKE :termino
                    OR laboratory ILIKE :termino
                )
                ORDER BY description ASC
                LIMIT 10";

        $stmt = $db->prepare($query);
        $stmt->execute([
            ':termino' => "%{$termino}%"
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}



?>