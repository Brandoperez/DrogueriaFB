<?php 
namespace Controllers;

use MVC\Router;
use Model\Usuario;

class UsuariosController{
    public static function index(Router $router){
         isRole('admin');

         $usuarios = Usuario::all();

    $router->render('admin/usuarios/index', [
        'titulo' => 'Usuarios',
        'usuarios' => $usuarios
    ], 'admin-layout');
    }
    

    public static function crear(Router $router) {
        isRole('admin');
        
        $alertas = [];
        $usuario = new Usuario;

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario->sincronizar($_POST);
            $usuario->email = strtolower(trim($usuario->email));
            $alertas = $usuario->validarNuevoUsuario();


            if(empty($alertas)) {
                $existeUsuario = Usuario::where('email', $usuario->email);

                if($existeUsuario) {
                    Usuario::setAlerta('error', 'El Usuario ya esta registrado');
                    $alertas = Usuario::getAlertas();
                } else {
                    $usuario->name = trim($usuario->first_name . ' ' . $usuario->last_name);
                    $usuario->active = true;

                    $usuario->hashPassword();
                    unset($usuario->password2);

                    $resultado =  $usuario->guardar();

                    if($resultado) {
                        header('Location: /admin/usuarios');
                        exit;
                    }
                }
            }
        }

        // Render a la vista
        $router->render('admin/usuarios/crear', [
            'titulo' => 'Nuevo Usuario',
            'usuario' => $usuario, 
            'alertas' => $alertas
        ]);
    }

    public static function ver(Router $router){
        isRole('admin');
        
        $id = $_GET['id'] ?? null;
            if(!$id || !is_numeric($id)){
                header('Location: /admin/clientes');
                exit;
            }
        
        $usuario = Usuario::find($id);
            if(!$usuario){
                header('Location: /admin/usuarios');
                exit;
            }

    $router->render('admin/usuarios/ver', [
        'titulo' => 'Detalle Usuario', 
        'usuario' => $usuario
    ], 'admin-layout');
    }

    public static function editar(Router $router){
        isRole('admin');
        
        $id = $_GET['id'] ?? null;
            if(!$id || !is_numeric($id)){
                header('Location: /admin/usuarios');
                exit;
            }
        $usuario = Usuario::find($id);
            if(!$usuario){
                header('Location: /admin/usuarios');
                exit;
            }

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarEditarUsuario();
            
            $usuarioEmail = Usuario::where('email', $usuario->email);
                if($usuarioEmail && $usuarioEmail->id != $usuario->id) {
                    $alertas['error'][] = 'Ya existe un usuario con ese correo electrónico';
                }


                if(empty($alertas)){
                    $resultado = $usuario->guardar();
                    if($resultado){
                        header('Location: /admin/usuarios');
                        exit;
                    }
                }
        }

        $router->render('admin/usuarios/editar', [
            'titulo' => 'Editar Cliente',
            'alertas' => $alertas,
            'usuario' => $usuario
        ]);
        }

        public static function eliminar(Router $router){
            isRole('admin');

            $id = $_GET['id'] ?? null;
                if(!$id || !is_numeric($id)){
                    header('Location: /admin/clientes');
                    exit;
                }
            $usuario = Usuario::find($id);
                if(!$usuario){
                    header('Location: /admin/clientes');
                    exit;
                }

            $resultado = $usuario->eliminar();
            if($resultado){
                header('Location: /admin/usuarios');
                exit;
            }
        }
    
}



?>