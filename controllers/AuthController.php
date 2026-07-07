<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use Model\Cliente;
use MVC\Router;

class AuthController {
    public static function login(Router $router) {

    iniciarSession();
    if(isset($_SESSION['login']) && $_SESSION['login'] === true){
        redirectbyRole();
    }

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();
            
            if(empty($alertas)) {
                // Verificar quel el usuario exista
                $usuario = Usuario::where('email', $auth->email);
                if($usuario) {

                if(!$usuario->active) {
                    $alertas['error'][] = 'El usuario no existe o no está Confirmado';
                } elseif(!password_verify($auth->password, $usuario->password)) {
                    $alertas['error'][] = 'La Contraseña es Incorrecta';
                }else{
                        iniciarSession();  
                        session_regenerate_id(true);


                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['name'] = $usuario->name;
                        $_SESSION['first_name'] = $usuario->first_name;
                        $_SESSION['last_name'] = $usuario->last_name;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['role'] = $usuario->role ?? null;
                        $_SESSION['client_id'] = $usuario->client_id;
                        $_SESSION['login'] = true;

                       redirectByRole();
                }
            }else{
                $cliente = Cliente::where('email', $auth->email);
                    if(!$cliente || !$cliente->active){
                        $alertas['error'][] = 'El usuario no existe o no está Confirmado';
                    }elseif(!password_verify($auth->password, $cliente->password)){
                        $alertas['error'][] = 'La Contraseña es Incorrecta';
                    }else{
                        iniciarSession();
                        session_regenerate_id(true);

                        $_SESSION['id'] = null;
                        $_SESSION['name'] = $cliente->name;
                        $_SESSION['first_name'] = $cliente->name;
                        $_SESSION['last_name'] = '';
                        $_SESSION['email'] = $cliente->email;
                        $_SESSION['role'] = 'client';
                        $_SESSION['client_id'] = $cliente->id;
                        $_SESSION['login'] = true;

                        redirectByRole();
                    }
            }
        }
    }
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas
        ]);
    }

    public static function logout() {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            iniciarSession();
            $_SESSION = [];
            session_destroy();
            header('Location: /login');
            exit;
        }
       
    }

    public static function registro(Router $router) {
        
        $alertas = [];
        $usuario = new Usuario;
        $cliente = new Cliente;

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario->sincronizar($_POST);
            $cliente->sincronizar([
                'name' => $_POST['empresa'] ?? '',
                'cuit' => $_POST['cuit'] ?? '',
                'address' => $_POST['direccion'] ?? '',
                'phone' => $_POST['telefono'] ?? '',
                'email' => $_POST['email'] ?? '',
                'province' => $_POST['provincia'] ?? ''
            ]);


            $alertaUsuario = $usuario->validar_cuenta();
            $alertaCliente = $cliente->validar();

            $alertas = array_merge_recursive($alertaUsuario, $alertaCliente );

            if(empty($alertas)) {
                $existeUsuario = Usuario::where('email', $usuario->email);

                if($existeUsuario) {
                    Usuario::setAlerta('error', 'El Usuario ya esta registrado');
                    $alertas = Usuario::getAlertas();
                } else {
                    $usuario->name = trim($usuario->first_name . ' ' . $usuario->last_name);
                    $usuario->role = 'client';
                    $usuario->active = false;
                    $usuario->client_id = null;

                    // Hashear el password
                    $usuario->hashPassword();

                    // Eliminar password2
                    unset($usuario->password2);

                    // Generar el Token
                    $usuario->crearToken();
                    // Crear un nuevo usuario
                    $resultado =  $usuario->guardar();

                    if($resultado) {
                        $email = new Email($usuario->email, $usuario->name, $usuario->token);
                        $email->enviarConfirmacion();
                        header('Location: /mensaje');
                        exit;
                    }
                }
            }
        }

        // Render a la vista
        $router->render('auth/registro', [
            'titulo' => 'Crear Cuenta',
            'usuario' => $usuario, 
            'cliente' => $cliente,
            'alertas' => $alertas
        ]);
    }

    public static function olvide(Router $router) {
        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if(empty($alertas)) {
                // Buscar el usuario
                $usuario = Usuario::where('email', $auth->email);

                if(!$usuario || !$usuario->active) {
                    $alertas['error'][] = 'El usuario no existe o no esta activo'; 
                }else{
                    // Generar un nuevo token
                    $usuario->crearToken();
                    unset($usuario->password2);

                    //actualizar el usuario
                    $resultado = $usuario->guardar();
                    if($resultado){
                        $email = new Email($usuario->email, $usuario->name, $usuario->token);
                        $email->enviarInstrucciones();

                        $alertas['exito'][] = 'Hemos enviado las intrucciones a tu correo electrónico.';
                    }
                }   
            }
        }

        $router->render('auth/olvide', [
            'titulo' => 'Olvide mi Contraseña',
            'alertas' => $alertas
        ]);
    }

    public static function restablecer(Router $router) {
        $alertas = [];
        $token = s($_GET['token']) ?? '';
        $token_valido = true;

        if(!$token) header('Location: /login');

        // Identificar el usuario con este token
        $usuario = Usuario::where('token', $token);

        if(!$usuario) {
            $alertas['error'][] = 'Token no válido, intente de nuevo.';
            $token_valido = false;
        }


        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Añadir el nuevo password
            $usuario->sincronizar($_POST);

            // Validar el password
            $alertas = $usuario->validarPassword();

            if(empty($alertas)) {
                // Hashear el nuevo password
                $usuario->hashPassword();

                // Eliminar el Token
                $usuario->token = null;
                unset($usuario->password2);

                // Guardar el usuario en la BD
                $resultado = $usuario->guardar();

                // Redireccionar
                if($resultado) {
                    header('Location: /login');
                    exit;
                }
            }
        }

        $router->render('auth/restablecer', [
            'titulo' => 'Restablecer Contraseña',
            'alertas' => $alertas,
            'token_valido' => $token_valido,
            'token' => $token
        ]);
    }

    public static function mensaje(Router $router) {

    $router->render('auth/mensaje', [
        'tipo' => 'exito',
        'titulo' => 'Revisa tu correo electrónico',
        'mensaje' => 'Hemos enviado las instrucciones para confirmar tu cuenta a tu correo electrónico.',
        'textoBoton' => 'Iniciar Sesión',
        'urlBoton' => '/login'
    ]);
}

    public static function confirmar(Router $router) {
        $alertas = [];
        $token = s($_GET['token'] ?? '');

        if(!$token){
           header('Location: /login'); 
           exit;
        } 

        // Encontrar al usuario con este token
        $usuario = Usuario::where('token', $token);

        if(!$usuario) {
            // No se encontró un usuario con ese token
            $alertas['error'][] = 'Token no valido o expirado';
        } else {
            // Confirmar la cuenta
            $usuario->active = true;
            $usuario->token = null;
            unset($usuario->password2);

            // Guardar en la BD
            $resultado = $usuario->guardar();
            if($resultado){
                $alertas['exito'][] = 'Cuenta Comprobada Correctamente, ya podés iniciar Sesión';
            }
        }
        $router->render('auth/confirmar', [
            'titulo' => 'Cuenta Confirmada',
            'alertas' => $alertas
        ]);
    }
}