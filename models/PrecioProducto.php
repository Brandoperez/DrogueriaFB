<?php 
namespace Model;

class PrecioProducto extends ActiveRecord{
    protected static $tabla = 'product_prices';
    protected static $columnasDB = ['id', 'product_id', 'price_list_id', 'price', 'discount_percentage'];

    public $id;
    public $product_id;
    public $price_list_id;
    public $price;
    public $discount_percentage;
    public $created_at;
    public $updated_at;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->product_id = $args['product_id'] ?? null;
        $this->price_list_id = $args['price_list_id'] ?? null;
        $this->price = $args['price'] ?? 0;
        $this->discount_percentage = $args['discount_percentage'] ?? 0;
        $this->created_at = $args['created_at'] ?? null;
        $this->updated_at = $args['updated_at'] ?? null;
    }
}




?>