<?php

namespace Model;

class Usuario extends ActiveRecord {
    protected static $tabla = 'users';
    protected static $columnasDB = ['id', 'first_name', 'last_name', 'name', 'email', 'password', 'role', 'active', 'client_id', 'token'];

    public $id;
    public $first_name;
    public $last_name;
    public $name;
    public $email;
    public $password;
    public $password2;
    public $role;
    public $client_id;
    public $token;
    public $active;
    public $created_at;
    public $password_actual;
    public $password_nuevo;

    
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->name = $args['name'] ?? '';
        $this->first_name = $args['first_name'] ?? '';
        $this->last_name = $args['last_name'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->role = $args['role'] ?? 'client';
        $this->active = $args['active'] ?? false;
        $this->client_id = $args['client_id'] ?? null;
        $this->token = $args['token'] ?? null;
        $this->created_at = $args['created_at'] ?? null;
    }

    // Validar el Login de Usuarios
    public function validarLogin() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email del Usuario es Obligatorio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no válido';
        }
        if(!$this->password) {
            self::$alertas['error'][] = 'El Password no puede ir vacio';
        }
        return self::$alertas;

    }

    // Validación para cuentas nuevas
    public function validarNuevoUsuario() {
        $alertas = [];
        if(!$this->first_name) {
            $alertas['error'][] = 'El Nombre es Obligatorio';
        }
        if(!$this->last_name) {
            $alertas['error'][] = 'El Apellido es Obligatorio';
        }
        if(!$this->email) {
            $alertas['error'][] = 'El Email es Obligatorio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $alertas['error'][] = 'Email no válido';
        }
        if(!$this->password) {
            $alertas['error'][] = 'El Password no puede ir vacio';
        }
        if(strlen($this->password) < 8) {
            $alertas['error'][] = 'El password debe contener al menos 8 caracteres';
        }
        if($this->password !== $this->password2) {
            $alertas['error'][] = 'Los password son diferentes';
        }
        return $alertas;
    }

    //Validar Editar Usuario
    public function validarEditarUsuario() {
        $alertas = [];
        if(!$this->first_name) {
            $alertas['error'][] = 'El Nombre es Obligatorio';
        }
        if(!$this->last_name) {
            $alertas['error'][] = 'El Apellido es Obligatorio';
        }
        if(!$this->email) {
            $alertas['error'][] = 'El Email es Obligatorio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $alertas['error'][] = 'Email no válido';
        }
        
        return $alertas;
    }

    // Valida un email
    public function validarEmail() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no válido';
        }
        return self::$alertas;
    }

    // Valida el Password 
    public function validarPassword() {
        if(!$this->password) {
            self::$alertas['error'][] = 'El Password no puede ir vacio';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres';
        }
        return self::$alertas;
    }

    public function nuevo_password() : array {
        if(!$this->password_actual) {
            self::$alertas['error'][] = 'El Password Actual no puede ir vacio';
        }
        if(!$this->password_nuevo) {
            self::$alertas['error'][] = 'El Password Nuevo no puede ir vacio';
        }
        if(strlen($this->password_nuevo) < 6) {
            self::$alertas['error'][] = 'El Password debe contener al menos 6 caracteres';
        }
        return self::$alertas;
    }

    // Comprobar el password
    public function comprobar_password() : bool {
        return password_verify($this->password_actual, $this->password );
    }

    // Hashea el password
    public function hashPassword() : void {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    // Generar un Token
    public function crearToken() : void {
        $this->token = bin2hex(random_bytes(32));
    }
}