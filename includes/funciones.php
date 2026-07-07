<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

function s($html) : string {
    return htmlspecialchars($html ?? '', ENT_QUOTES, 'UTF-8');
}

function iniciarSession() : void{
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
}

function isAuth() {
    iniciarSession();

    if(!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
        header('Location: /login');
        exit;
    }
}

function isRole(string ...$roles) : void{
    iniciarSession();

    if(!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
        header('Location: /login');
        exit;
    }

    if(!isset($_SESSION['role']) || !in_array($_SESSION['role'], $roles, true)){
        redirectbyRole();
    }
}

function hasRole(string ...$roles) : bool {
    iniciarSession();
    return isset($_SESSION['role']) && in_array($_SESSION['role'], $roles, true);
}

function sessionUser(string $campo) : mixed {
    iniciarSession();
    return $_SESSION[$campo] ?? null;
}

function redirectByRole() : void {
    iniciarSession();
 
    $role = $_SESSION['role'] ?? null;
 
    $rutas = [
        'admin'     => '/admin/dashboard',
        'client'    => '/cliente/clientes',
        'seller'    => '/vendedor/dashboard',
        'logistics' => '/logistica/dashboard',
    ];
 
    $destino = $rutas[$role] ?? '/login';
 
    header('Location: ' . $destino);
    exit;
}

function paginaActual(string $path) : bool {
    return str_contains($_SERVER['PATH_INFO'] ?? '/', $path);
}

function ao_animation(){
    $efectos = ['fade-up', 'fade-down', 'fade-left', 'fade-right', 'zoom-in', 'zoom-in-up', 'zoom-out', 'zoom-down'];
    $efecto = array_rand($efectos, 1);
    echo $efectos[$efecto];
}

function claseEstado(string $status) : string {
    $clases = [
        'pending'   => 'estado--proceso',
        'confirmed' => 'estado--confirmado',
        'completed' => 'estado--completado',
        'cancelled' => 'estado--cancelado'
    ];

    return $clases[$status] ?? 'estado--nuevo';
}

    function fechaEnEspanol(?string $fecha = null) : string {
        $dias = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
        $meses = ['','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];

        $timestamp = $fecha ? strtotime($fecha) : time();

        $dia = $dias[date('w', $timestamp)];
        $numero = date('j', $timestamp);
        $mes = $meses[(int) date('n', $timestamp)];
        $anio = date('Y', $timestamp);

        return "{$dia} {$numero} de {$mes} de {$anio}";
    }
?>