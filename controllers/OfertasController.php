<?php
namespace Controllers;

use MVC\Router;
use Intervention\Image\ImageManagerStatic as Image;

class OfertasController {

    const SLOTS = [1, 2, 3];

    public static function index(Router $router) {
        isRole('admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            self::procesarSubida();
        }

        $router->render('admin/ofertas/index', [
            'titulo' => 'Ofertas del Mes'
        ], 'admin-layout');
    }

    private static function procesarSubida() {
        $slot = (int) ($_POST['slot'] ?? 0);

        if (!in_array($slot, self::SLOTS, true)) {
            $_SESSION['alerta'] = ['tipo' => 'error', 'mensaje' => 'Imagen inválida'];
            header('Location: /admin/ofertas');
            exit;
        }

        $archivo = $_FILES['imagen'] ?? null;

        if (!$archivo || $archivo['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['alerta'] = ['tipo' => 'error', 'mensaje' => 'No se pudo subir la imagen'];
            header('Location: /admin/ofertas');
            exit;
        }

        $permitidos = ['image/jpeg', 'image/png', 'image/webp'];
        $tipo = mime_content_type($archivo['tmp_name']);

        if (!in_array($tipo, $permitidos, true)) {
            $_SESSION['alerta'] = ['tipo' => 'error', 'mensaje' => 'Formato no permitido. Usá JPG, PNG o WEBP'];
            header('Location: /admin/ofertas');
            exit;
        }

        if ($archivo['size'] > 5 * 1024 * 1024) {
            $_SESSION['alerta'] = ['tipo' => 'error', 'mensaje' => 'La imagen no puede pesar más de 5MB'];
            header('Location: /admin/ofertas');
            exit;
        }

        $carpetaDestino = __DIR__ . '/../public/uploads/ofertas/';

        if (!is_dir($carpetaDestino)) {
            mkdir($carpetaDestino, 0755, true);
        }

        // Normalizamos cualquier formato subido a PNG, así el src de la vista no cambia nunca
        $imagen = Image::make($archivo['tmp_name']);
        $imagen->encode('png')->save($carpetaDestino . "promo{$slot}.png");

        $_SESSION['alerta'] = ['tipo' => 'success', 'mensaje' => "Oferta {$slot} actualizada correctamente"];
        header('Location: /admin/ofertas');
        exit;
    }
}