<?php 
namespace Model;

class ImportacionProducto extends ActiveRecord{
    protected static $tabla = 'product_imports';
    protected static $columnasDB = ['id', 'file_name', 'user_id', 'total_records', 'new_products', 'updated_products', 'errors_count', 'status'];

    public $id;
    public $file_name;
    public $user_id;
    public $total_records;
    public $new_products;
    public $updated_products;
    public $errors_count;
    public $status;
    public $created_at;
    public $usuario;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->file_name = $args['file_name'] ?? '';
        $this->user_id = $args['user_id'] ?? null;
        $this->total_records = $args['total_records'] ?? 0;
        $this->new_products = $args['new_products'] ?? 0;
        $this->updated_products = $args['updated_products'] ?? 0;
        $this->errors_count = $args['errors_count'] ?? 0;
        $this->status = $args['status'] ?? 'completed';
        $this->created_at = $args['created_at'] ?? null;
        $this->usuario = $args['usuario'] ?? null; 

    }

    public static function obtenerHistorial() {
    $query = "SELECT 
                pi.id,
                pi.file_name,
                pi.total_records,
                pi.new_products,
                pi.updated_products,
                pi.errors_count,
                pi.status,
                pi.created_at,
                u.first_name || ' ' || u.last_name AS usuario
            FROM product_imports pi
            LEFT JOIN users u ON pi.user_id = u.id
            ORDER BY pi.created_at DESC";

    return self::consultarSQL($query);
}
}




?>