<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\AdminController;
use Controllers\AuthController;
use Controllers\ClientesController;
use Controllers\LogisticaController;
use Controllers\PedidosController;
use Controllers\ProductosController;
use Controllers\UsuariosController;
use MVC\Router;
$router = new Router();


// Login
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);

// Formulario de olvide mi password
$router->get('/olvide', [AuthController::class, 'olvide']);
$router->post('/olvide', [AuthController::class, 'olvide']);

// Colocar el nuevo password
$router->get('/restablecer', [AuthController::class, 'restablecer']);
$router->post('/restablecer', [AuthController::class, 'restablecer']);

// Confirmación de Cuenta
$router->get('/mensaje', [AuthController::class, 'mensaje']);
$router->get('/confirmar-cuenta', [AuthController::class, 'confirmar']);


//ZONA DE ADMINISTRACIÓN
//Administración
$router->get('/admin/dashboard', [AdminController::class, 'index']);

//Pedidos
$router->get('/admin/pedidos/crear', [PedidosController::class, 'crear']);
$router->post('/admin/pedidos/crear', [PedidosController::class, 'crear']);
$router->get('/admin/pedidos/excel', [PedidosController::class, 'excel']);
$router->post('/admin/pedidos/excel', [PedidosController::class, 'excel']);
$router->get('/admin/pedidos/confirmar', [PedidosController::class, 'confirmar']);
$router->post('/admin/pedidos/confirmar', [PedidosController::class, 'confirmar']);
$router->get('/admin/pedidos/detalle', [PedidosController::class, 'detalle']);
$router->get('/admin/pedidos/listado', [PedidosController::class, 'listado']);
$router->get('/admin/pedidos/plantilla', [PedidosController::class, 'plantilla']);

$router->get('/api/pedidos/clientes', [PedidosController::class, 'buscarClientes']);
$router->get('/api/pedidos/productos', [PedidosController::class, 'buscarProductos']);
$router->get('/api/pedidos/buscar', [PedidosController::class, 'buscarPedidos']);
$router->post('/api/pedidos/estado', [PedidosController::class, 'completarPedido']);

//Productos
$router->get('/admin/productos/excel', [ProductosController::class, 'excel']);
$router->post('/admin/productos/excel', [ProductosController::class, 'excel']);
$router->get('/admin/productos/confirmar', [ProductosController::class, 'confirmar']);
$router->post('/admin/productos/confirmar', [ProductosController::class, 'confirmar']);
$router->get('/admin/productos/preview', [ProductosController::class, 'preview']);
$router->get('/admin/productos/historial', [ProductosController::class, 'listado']);

//CLientes
$router->get('/admin/clientes', [ClientesController::class, 'index']);
$router->get('/admin/clientes/crear', [ClientesController::class, 'crear']);
$router->post('/admin/clientes/crear', [ClientesController::class, 'crear']);
$router->get('/admin/clientes/ver', [ClientesController::class, 'ver']);
$router->get('/admin/clientes/editar', [ClientesController::class, 'editar']);
$router->post('/admin/clientes/editar', [ClientesController::class, 'editar']);
$router->get('/admin/clientes/eliminar', [ClientesController::class, 'eliminar']);

$router->get('/api/clientes/estado', [ClientesController::class, 'estado']);
$router->post('/api/clientes/buscar', [ClientesController::class, 'buscar']);

//Usuarios
$router->get('/admin/usuarios', [UsuariosController::class, 'index']);
$router->get('/admin/usuarios/crear', [UsuariosController::class, 'crear']);
$router->post('/admin/usuarios/crear', [UsuariosController::class, 'crear']);
$router->get('/admin/usuarios/ver', [UsuariosController::class, 'ver']);
$router->get('/admin/usuarios/editar', [UsuariosController::class, 'editar']);
$router->post('/admin/usuarios/editar', [UsuariosController::class, 'editar']);
$router->get('/admin/usuarios/eliminar', [UsuariosController::class, 'eliminar']);

//ZONA GENERAL

$router->get('/', [AuthController::class, 'index']);

//Clientes
$router->get('/cliente/clientes', [ClientesController::class, 'clientes']);
$router->get('/cliente/pedidos/crear', [PedidosController::class, 'crearCliente']);
$router->post('/cliente/pedidos/crear', [PedidosController::class, 'crearCliente']);
$router->get('/cliente/pedidos/excel', [PedidosController::class, 'crearExcel']);
$router->post('/cliente/pedidos/excel', [PedidosController::class, 'crearExcel']);
$router->get('/cliente/pedidos/confirmar', [PedidosController::class, 'confirmarCliente']);
$router->post('/cliente/pedidos/confirmar', [PedidosController::class, 'confirmarCliente']);
$router->get('/cliente/pedidos/listado', [PedidosController::class, 'listadoCliente']);
$router->get('/cliente/pedidos/detalle', [PedidosController::class, 'detalleCliente']);

$router->get('/api/cliente/pedidos/productos', [PedidosController::class, 'buscarProductosCliente']);
$router->get('/api/cliente/pedidos/buscar', [PedidosController::class, 'buscarPedidosCliente']);



$router->comprobarRutas();