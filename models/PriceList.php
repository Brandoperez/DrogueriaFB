<?php 
namespace Model;

class PriceList extends ActiveRecord{
    protected static $tabla = 'price_lists';
    protected static $columnasDB = ['id', 'name', 'description', 'active'];

    public $id;
    public $name;
    public $description;
    public $active;


}




?>