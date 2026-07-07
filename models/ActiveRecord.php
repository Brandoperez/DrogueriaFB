<?php
namespace Model;
use PDO;

class ActiveRecord {

    // Base DE DATOS
    protected static $db;
    protected static $tabla = '';
    protected static $columnasDB = [];

    // Alertas y Mensajes
    protected static $alertas = [];
    
    // Definir la conexión a la BD - includes/database.php
    public static function setDB($database) {
        self::$db = $database;
    }

    // Setear un tipo de Alerta
    public static function setAlerta($tipo, $mensaje) {
        static::$alertas[$tipo][] = $mensaje;
    }

    // Obtener las alertas
    public static function getAlertas() {
        return static::$alertas;
    }

    // Validación que se hereda en modelos
    public function validar() {
        static::$alertas = [];
        return static::$alertas;
    }

    // Consulta SQL para crear un objeto en Memoria (Active Record)
    public static function consultarSQL($query) {
    $stmt = self::$db->query($query);
    $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $array = [];
    foreach($registros as $registro) {
        $array[] = static::crearObjeto($registro);
    }

    return $array;
}

    // Crea el objeto en memoria que es igual al de la BD
    protected static function crearObjeto($registro) {
        $objeto = new static;

        foreach($registro as $key => $value ) {
            if(property_exists( $objeto, $key  )) {
                $objeto->$key = $value;
            }
        }
        return $objeto;
    }

    // Identificar y unir los atributos de la BD
    public function atributos() {
        $atributos = [];
        foreach(static::$columnasDB as $columna) {
            if($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    // Sanitizar los datos antes de guardarlos en la BD
    public function sanitizarAtributos() {
    return $this->atributos();
    }

    // Sincroniza BD con Objetos en memoria
    public function sincronizar($args=[]) { 
        foreach($args as $key => $value) {
          if(property_exists($this, $key) && !is_null($value)) {
            if($key === 'active') {
                $this->$key = $value !== ''
                    ? filter_var($value, FILTER_VALIDATE_BOOLEAN)
                    : true;
            } else {
                $this->$key = $value;
            }
}
        }
    }

    // Registros - CRUD
    public function guardar() {
        $resultado = '';
        if(!is_null($this->id)) {
            // actualizar
            $resultado = $this->actualizar();
        } else {
            // Creando un nuevo registro
            $resultado = $this->crear();
        }
        return $resultado;
    }

    // Obtener todos los Registros
    public static function all() {
        $query = "SELECT * FROM " . static::$tabla . " ORDER BY id DESC";
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // Busca un registro por su id
    public static function find($id) {
        $query = "SELECT * FROM " . static::$tabla  . " WHERE id = ? LIMIT 1";
        $stmt = self::$db->prepare($query);
        $stmt->execute([$id]);

        $registro = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$registro){
            return null;
        }
        
        return static::crearObjeto($registro);
    }

    // Obtener Registros con cierta cantidad
    public static function get($limite) {
        $query = "SELECT * FROM " . static::$tabla . " ORDER BY id DESC LIMIT {$limite}" ;
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // Busqueda Where con Columna 
    public static function where($columna, $valor) {
    $query = "SELECT * FROM " . static::$tabla . " WHERE {$columna} = ? LIMIT 1";

    $stmt = self::$db->prepare($query);
    $stmt->execute([$valor]);

    $registro = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$registro) {
        return null;
    }

    return static::crearObjeto($registro);
    }

    //Busqueda de varios con Where
    public static function whereAll($columna, $valor) {

    $query = "SELECT * FROM " . static::$tabla . " WHERE {$columna} = ?";

    $stmt = self::$db->prepare($query);
    $stmt->execute([$valor]);

    $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $array = [];

    foreach($registros as $registro) {
        $array[] = static::crearObjeto($registro);
    }

    return $array;
    }
    
    public static function whereTwo($col1, $val1, $col2, $val2){
    $query = "SELECT * FROM " . static::$tabla . "
              WHERE {$col1} = ? AND {$col2} = ?
              LIMIT 1";

    $stmt = self::$db->prepare($query);
    $stmt->execute([$val1, $val2]);

    $registro = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$registro) {
        return null;
    }

    return static::crearObjeto($registro);
    }

    // crea un nuevo registro
    public function crear() {

    $atributos = $this->sanitizarAtributos();

    $columnas = implode(', ', array_keys($atributos));
    $placeholders = implode(', ', array_fill(0, count($atributos), '?'));

    $query = "INSERT INTO " . static::$tabla . " ($columnas) VALUES ($placeholders)";

    $stmt = self::$db->prepare($query);

    $i = 1;
    foreach($atributos as $valor) {
        if(is_bool($valor)) {
            $stmt->bindValue($i, $valor, PDO::PARAM_BOOL);
        } elseif(is_null($valor)) {
            $stmt->bindValue($i, null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue($i, $valor);
        }

        $i++;
    }

    $resultado = $stmt->execute();

    return [
        'resultado' => $resultado
    ];
}

    // Actualizar el registro
    public function actualizar() {

    $atributos = $this->sanitizarAtributos();

    $valores = [];
    foreach(array_keys($atributos) as $atributo) {
        $valores[] = "{$atributo} = ?";
    }

    $query = "UPDATE " . static::$tabla . " SET ";
    $query .= implode(', ', $valores);
    $query .= " WHERE id = ?";

    $stmt = self::$db->prepare($query);

    $datos = array_map(function($valor) {
    if(is_bool($valor)) {
        return $valor ? 1 : 0;
    }
    return $valor;
    }, array_values($atributos));

    $datos[] = $this->id;

    return $stmt->execute($datos);
    }

    // Eliminar un Registro por su ID
    public function eliminar() {
        $query = "DELETE FROM "  . static::$tabla . " WHERE id = ?";
        $stmt = self::$db->prepare($query);
        return $stmt->execute([$this->id]);
    }

    //CONTADOR
    public static function contar() {
        $query = "SELECT COUNT(*) FROM " . static::$tabla;
        $stmt = self::$db->query($query);
        return (int) $stmt->fetchColumn();
    }

    //Limpiar las alertas
    public static function limpiarAlertas() {
    static::$alertas = [];
}
}