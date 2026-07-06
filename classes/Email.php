<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {

    public $email;
    public $name;
    public $token;
    
    public function __construct($email = null, $name = null, $token = null)
    {
        $this->email = $email;
        $this->name = $name;
        $this->token = $token;
    }

    public function enviarConfirmacion() {

         // create a new object
         $mail = new PHPMailer();
         $mail->isSMTP();
         $mail->Host = $_ENV['EMAIL_HOST'];
         $mail->SMTPAuth = true;
         $mail->Port = $_ENV['EMAIL_PORT'];
         $mail->Username = $_ENV['EMAIL_USER'];
         $mail->Password = $_ENV['EMAIL_PASS'];
     
         $mail->setFrom('noreply@drogueriafb.com');
         $mail->addAddress($this->email, $this->name);
         $mail->Subject = 'Confirma tu Cuenta en Droguería FB';

         // Set HTML
         $mail->isHTML(TRUE);
         $mail->CharSet = 'UTF-8';

         $contenido = '<html>';
         $contenido .= "<p><strong>Hola {$this->name}</strong></p>";
         $contenido .= "<p>Has registrado correctamente tu cuenta en Droguería FB. Confirma tu cuenta ahora.</p>";
         $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['HOST'] . "/confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta</a>";       
         $contenido .= "<p>Si tu no creaste esta cuenta; puedes ignorar el mensaje</p>";
         $contenido .= '</html>';
         $mail->Body = $contenido;

         //Enviar el mail
         if(!$mail->send()) {
    debuguear($mail->ErrorInfo);
}

    }

    public function enviarInstrucciones() {

        // create a new object
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];
    
        $mail->setFrom('noreply@drogueriafb.com');
        $mail->addAddress($this->email, $this->name);
        $mail->Subject = 'Recuperación de Contraseña - Droguería FB';

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p><strong>Hola {$this->name}</strong></p>";
        $contenido .= "<p>Has solicitado restablecer tu contraseña en Droguería FB, haz click en el siguiente enlace.</p>";
        $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['HOST'] . "/restablecer?token=" . $this->token . "'>Restablecer Password</a>";        
        $contenido .= "<p>Si tu no solicitaste este cambio, puedes ignorar el mensaje</p>";
        $contenido .= '</html>';
        $mail->Body = $contenido;

        //Enviar el mail
        $mail->send();
    }

    public function enviarConfirmacionPedido($numeroPedido){
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('noreply@drogueriafb.com');
        $mail->addAddress($this->email, $this->name);
        $mail->Subject = 'Pedido Confirmado - Droguería FB';
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<div style='text-align:center; margin-bottom:20px;'>
                       <img src='" . $_ENV['HOST'] . "/build/img/logo.jpg' alt='Droguería FB' style='max-width:220px;'></div>";
        $contenido .= "<p><strong>Hola {$this->name}</strong></p>";
        $contenido .= "<p>Tu pedido <strong>#{$numeroPedido}</strong> ha sido confirmado correctamente.</p>";
        $contenido .= "<p>El pedido ya está preparado y será entregado dentro de las próximas 24 horas.</p>";
        $contenido .= "<p>Gracias por confiar en Droguería FB.</p>";
        $contenido .= '</html>';

        $mail->Body = $contenido;

        if(!$mail->send()){
            error_log('Error correo pedido confirmado: ' . $mail->ErrorInfo);
            return false;
        }

        error_log('Correo pedido confirmado enviado correctamente');
        return true;
    } 

    public function enviarAvisoFacturacion($pedido){

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = $_ENV['EMAIL_HOST'];
    $mail->SMTPAuth = true;
    $mail->Port = $_ENV['EMAIL_PORT'];
    $mail->Username = $_ENV['EMAIL_USER'];
    $mail->Password = $_ENV['EMAIL_PASS'];

    $mail->setFrom('noreply@drogueriafb.com', 'Droguería FB');
    $mail->addAddress($_ENV['EMAIL_FACTURACION']);

    $numeroPedido = str_pad($pedido['id'], 6, '0', STR_PAD_LEFT);

    $mail->Subject = "Nuevo Pedido #{$numeroPedido} - Droguería FB";

    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';

    $contenido = '<html>';
    $contenido .= "<h2>Nuevo pedido recibido</h2>";
    $contenido .= "<p><strong>N° Pedido:</strong> #{$numeroPedido}</p>";
    $contenido .= "<p><strong>Cliente:</strong> {$pedido['client_name']}</p>";
    $contenido .= "<p><strong>Vendedor:</strong> {$pedido['seller_name']}</p>";
    $contenido .= "<p><strong>Fecha:</strong> " . date('d/m/Y H:i', strtotime($pedido['created_at'])) . "</p>";
    $contenido .= "<p><strong>Total:</strong> $" . number_format($pedido['total'], 2, ',', '.') . "</p>";

    if(!empty($pedido['observations'])){
        $contenido .= "<p><strong>Observaciones:</strong> {$pedido['observations']}</p>";
    }

    $contenido .= "<h3>Productos</h3>";
    $contenido .= "<table border='1' cellpadding='8' cellspacing='0' width='100%'>";
    $contenido .= "<thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Subtotal</th>
                    </tr>
                  </thead>";
    $contenido .= "<tbody>";

    foreach($pedido['items'] as $item){
        $contenido .= "<tr>";
        $contenido .= "<td>{$item['product_name']}</td>";
        $contenido .= "<td>{$item['quantity']}</td>";
        $contenido .= "<td>$" . number_format($item['price'], 2, ',', '.') . "</td>";
        $contenido .= "<td>$" . number_format($item['subtotal'], 2, ',', '.') . "</td>";
        $contenido .= "</tr>";
    }

    $contenido .= "</tbody>";
    $contenido .= "</table>";
    $contenido .= "<p>Ingresá al panel administrativo para revisar el pedido.</p>";
    $contenido .= '</html>';

    $mail->Body = $contenido;

    return $mail->send();
}
}