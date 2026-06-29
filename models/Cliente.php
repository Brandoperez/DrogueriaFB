<?php 
namespace Model;
use PDO;

class Cliente extends ActiveRecord{
    protected static $tabla = 'clients';
    protected static $columnasDB = ['id', 'name', 'type', 'cuit', 'address', 'phone', 'email', 'province', 'active', 'seller_id', 'price_list_id', 'password', 'token'];


    public $id;
    public $name;
    public $type;
    public $cuit;
    public $address;
    public $phone;
    public $email;
    public $province;
    public $active;
    public $seller_id;
    public $created_at;
    public $price_list_id;
    public $password;
    public $password2;
    public $token;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->name = $args['name'] ?? '';
        $this->type = $args['type'] ?? 'farmacia';
        $this->cuit = $args['cuit'] ?? '';
        $this->address = $args['address'] ?? '';
        $this->phone = $args['phone'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->province = $args['province'] ?? '';
        $this->active = $args['active'] ?? false;
        $this->seller_id = $args['seller_id'] ?? null;
        $this->created_at = $args['created_at'] ?? null;
        $this->price_list_id = $args['price_list_id'] ?? null;
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->token = $args['token'] ?? null;
    }

    public function validarNuevoCliente(){
        $alertas = [];
        if(!$this->name) {
        $alertas['error'][] = 'El nombre de la empresa es obligatorio';
    }

    if(!$this->cuit) {
        $alertas['error'][] = 'El CUIT es obligatorio';
    }

    if(!ctype_digit($this->cuit)){
        $alertas['error'][] = 'El CUIT sólo puede contener números';
    }

    if(strlen($this->cuit) !== 11) {
        $alertas['error'][] = 'El CUIT no es válido';
    }

    if(!$this->address) {
        $alertas['error'][] = 'La dirección es obligatoria';
    }

    if(!$this->phone) {
        $alertas['error'][] = 'El teléfono es obligatorio';
    }

    if(!$this->email) {
        $alertas['error'][] = 'El email es Obligatorio';
    }

    if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
        $alertas['error'][] = 'Email no válido';
    }

    if(!$this->province) {
        $alertas['error'][] = 'La provincia es obligatoria';
    }

    if(!$this->type) {
        $alertas['error'][] = 'El tipo de cliente es obligatorio';
    }

    if(!$this->price_list_id){
        $alertas['error'][] = 'Debe seleccionar una lista de precios';
    }

    if(!$this->password) {
    $alertas['error'][] = 'La contraseña es obligatoria';
}

    if(strlen($this->password) < 8) {
        $alertas['error'][] = 'La contraseña debe tener al menos 8 caracteres';
    }

    if($this->password !== $this->password2) {
        $alertas['error'][] = 'Las contraseñas no coinciden';
    }

    if(!$this->seller_id){
    $alertas['error'][] = 'Debe seleccionar un vendedor';
    }

    return $alertas;
    }

    public function validarEdicionCliente(){
        $alertas = [];

        if(!$this->name) {
        $alertas['error'][] = 'El nombre de la empresa es obligatorio';
    }

    if(!$this->cuit) {
        $alertas['error'][] = 'El CUIT es obligatorio';
    }

    if(!ctype_digit($this->cuit)){
        $alertas['error'][] = 'El CUIT sólo puede contener números';
    }

    if(strlen($this->cuit) !== 11) {
        $alertas['error'][] = 'El CUIT no es válido';
    }

    if(!$this->address) {
        $alertas['error'][] = 'La dirección es obligatoria';
    }

    if(!$this->phone) {
        $alertas['error'][] = 'El teléfono es obligatorio';
    }

    if(!$this->email) {
        $alertas['error'][] = 'El email es Obligatorio';
    }

    if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
        $alertas['error'][] = 'Email no válido';
    }

    if(!$this->province) {
        $alertas['error'][] = 'La provincia es obligatoria';
    }

    if(!$this->type) {
        $alertas['error'][] = 'El tipo de cliente es obligatorio';
    }

    if(!$this->price_list_id){
        $alertas['error'][] = 'Debe seleccionar una lista de precios';
    }
    if(!$this->seller_id){
    $alertas['error'][] = 'Debe seleccionar un vendedor';
    }

    return $alertas;

    }

    // Hashea el password
    public function hashPassword() : void {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    // Generar un Token
    public function crearToken() : void {
        $this->token = bin2hex(random_bytes(32));
    }

    //Nombre del Vendedor
    public function vendedor() {
    return Usuario::find($this->seller_id);
    }
    
    //Listado de Precios
    public function listaPrecios() {
        return PriceList::find($this->price_list_id);
    }

    public static function getProvincias(): array {
    $stmt = self::$db->query("SELECT DISTINCT province FROM clients WHERE province IS NOT NULL AND province <> '' ORDER BY province ASC");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}
}




?>