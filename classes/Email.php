<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    public $email;
    public $name;
    public $token;

    public function __construct($email = null, $name = null, $token = null)
    {
        $this->email = $email;
        $this->name = $name;
        $this->token = $token;
    }

    /**
     * Configuración general de PHPMailer
     */
    private function configurarMailer(): PHPMailer
    {
        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom(
            $_ENV['EMAIL_USER'],
            'Droguería FB'
        );

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        return $mail;
    }

    /**
     * Escapa valores que serán utilizados dentro de HTML
     */
    private function escapar(string $valor): string
    {
        return htmlspecialchars(
            $valor,
            ENT_QUOTES,
            'UTF-8'
        );
    }

    /**
     * Plantilla base utilizada por los correos
     */
    private function plantillaBase(string $contenido): string
    {
        $logo = $_ENV['HOST'] . '/build/img/logo.jpg';

        return "
        <!DOCTYPE html>
        <html lang='es'>

        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Droguería FB</title>
        </head>

        <body style='margin:0; padding:0; background-color:#f4f6f8; font-family:Arial, Helvetica, sans-serif; color:#333333;'>

            <table
                width='100%'
                cellpadding='0'
                cellspacing='0'
                border='0'
                style='background-color:#f4f6f8; padding:30px 15px;'
            >
                <tr>
                    <td align='center'>

                        <table
                            width='600'
                            cellpadding='0'
                            cellspacing='0'
                            border='0'
                            style='max-width:600px; width:100%; background-color:#ffffff; border-radius:10px; overflow:hidden;'
                        >

                            <!-- HEADER -->
                            <tr>
                                <td
                                    align='center'
                                    style='padding:25px 20px; background-color:#ffffff;'
                                >
                                    <img
                                        src='{$logo}'
                                        alt='Droguería FB'
                                        style='display:block; max-width:220px; width:100%; height:auto; border:0;'
                                    >
                                </td>
                            </tr>

                            <!-- CONTENIDO -->
                            <tr>
                                <td style='padding:30px 35px;'>
                                    {$contenido}
                                </td>
                            </tr>

                            <!-- FOOTER -->
                            <tr>
                                <td
                                    align='center'
                                    style='padding:20px 30px; background-color:#f8f9fa; color:#777777; font-size:12px; line-height:18px;'
                                >
                                    <strong style='color:#444444;'>Droguería FB</strong><br>
                                    Este correo fue enviado automáticamente.<br>
                                    Por favor, no respondas directamente a este mensaje.
                                </td>
                            </tr>

                        </table>

                    </td>
                </tr>
            </table>

        </body>
        </html>";
    }

    /**
     * Confirmación de cuenta
     */
    public function enviarConfirmacion()
    {
        $mail = $this->configurarMailer();

        $mail->addAddress(
            $this->email,
            $this->name
        );

        $mail->Subject = 'Confirma tu Cuenta en Droguería FB';

        $nombre = $this->escapar($this->name);

        $urlConfirmacion =
            $_ENV['HOST'] .
            '/confirmar-cuenta?token=' .
            urlencode($this->token);

        $contenido = "
            <h1 style='margin:0 0 20px; font-size:24px; color:#222222;'>
                Confirma tu cuenta
            </h1>

            <p style='margin:0 0 15px; font-size:16px; line-height:24px;'>
                Hola <strong>{$nombre}</strong>,
            </p>

            <p style='margin:0 0 25px; font-size:15px; line-height:24px; color:#555555;'>
                Has registrado correctamente tu cuenta en
                <strong>Droguería FB</strong>.
            </p>

            <p style='margin:0 0 25px; font-size:15px; line-height:24px; color:#555555;'>
                Para comenzar a utilizar tu cuenta, necesitás confirmar tu dirección de correo electrónico.
            </p>

            <table width='100%' cellpadding='0' cellspacing='0' border='0'>
                <tr>
                    <td align='center' style='padding:10px 0 25px;'>

                        <a
                            href='{$urlConfirmacion}'
                            style='display:inline-block; padding:14px 25px; background-color:#198754; color:#ffffff; text-decoration:none; border-radius:6px; font-size:15px; font-weight:bold;'
                        >
                            Confirmar mi cuenta
                        </a>

                    </td>
                </tr>
            </table>

            <p style='margin:0; font-size:13px; line-height:20px; color:#777777;'>
                Si vos no creaste esta cuenta, podés ignorar este mensaje.
            </p>
        ";

        $mail->Body = $this->plantillaBase($contenido);

        if (!$mail->send()) {
            debuguear($mail->ErrorInfo);
        }
    }

    /**
     * Recuperación de contraseña
     */
    public function enviarInstrucciones()
    {
        $mail = $this->configurarMailer();

        $mail->addAddress(
            $this->email,
            $this->name
        );

        $mail->Subject = 'Recuperación de Contraseña - Droguería FB';

        $nombre = $this->escapar($this->name);

        $urlRestablecer =
            $_ENV['HOST'] .
            '/restablecer?token=' .
            urlencode($this->token);

        $contenido = "
            <h1 style='margin:0 0 20px; font-size:24px; color:#222222;'>
                Recuperación de contraseña
            </h1>

            <p style='margin:0 0 15px; font-size:16px; line-height:24px;'>
                Hola <strong>{$nombre}</strong>,
            </p>

            <p style='margin:0 0 25px; font-size:15px; line-height:24px; color:#555555;'>
                Recibimos una solicitud para restablecer tu contraseña de
                <strong>Droguería FB</strong>.
            </p>

            <table width='100%' cellpadding='0' cellspacing='0' border='0'>
                <tr>
                    <td align='center' style='padding:10px 0 25px;'>

                        <a
                            href='{$urlRestablecer}'
                            style='display:inline-block; padding:14px 25px; background-color:#198754; color:#ffffff; text-decoration:none; border-radius:6px; font-size:15px; font-weight:bold;'
                        >
                            Restablecer contraseña
                        </a>

                    </td>
                </tr>
            </table>

            <p style='margin:0; font-size:13px; line-height:20px; color:#777777;'>
                Si vos no solicitaste este cambio, podés ignorar este mensaje.
            </p>
        ";

        $mail->Body = $this->plantillaBase($contenido);

        $mail->send();
    }

    /**
     * Email enviado al cliente cuando su pedido fue confirmado
     */
    public function enviarConfirmacionPedido($numeroPedido)
    {
        $mail = $this->configurarMailer();

        $mail->addAddress(
            $this->email,
            $this->name
        );

        $numeroPedido = str_pad(
            $numeroPedido,
            6,
            '0',
            STR_PAD_LEFT
        );

        $mail->Subject =
            "Pedido confirmado #{$numeroPedido} - Droguería FB";

        $nombre = $this->escapar($this->name);

        $contenido = "
            <h1 style='margin:0 0 20px; font-size:24px; color:#222222;'>
                ¡Pedido confirmado!
            </h1>

            <p style='margin:0 0 15px; font-size:16px; line-height:24px;'>
                Hola <strong>{$nombre}</strong>,
            </p>

            <p style='margin:0 0 25px; font-size:15px; line-height:24px; color:#555555;'>
                Tenemos buenas noticias. Tu pedido ha sido
                <strong>confirmado correctamente</strong>.
            </p>

            <!-- PEDIDO -->
            <table
                width='100%'
                cellpadding='0'
                cellspacing='0'
                border='0'
                style='margin-bottom:25px; background-color:#f8f9fa; border-radius:8px;'
            >
                <tr>
                    <td style='padding:20px;'>

                        <p style='margin:0 0 8px; font-size:13px; color:#777777;'>
                            NÚMERO DE PEDIDO
                        </p>

                        <p style='margin:0; font-size:22px; font-weight:bold; color:#222222;'>
                            #{$numeroPedido}
                        </p>

                    </td>
                </tr>
            </table>

            <!-- ESTADO -->
            <table
                width='100%'
                cellpadding='0'
                cellspacing='0'
                border='0'
                style='margin-bottom:25px;'
            >
                <tr>

                    <td
                        width='12'
                        style='background-color:#198754; border-radius:6px 0 0 6px;'
                    >
                        &nbsp;
                    </td>

                    <td style='padding:15px 18px; background-color:#eef8f2;'>

                        <p style='margin:0 0 5px; font-size:13px; color:#555555;'>
                            ESTADO DEL PEDIDO
                        </p>

                        <p style='margin:0; font-size:15px; font-weight:bold; color:#198754;'>
                            Confirmado
                        </p>

                    </td>

                </tr>
            </table>

            <p style='margin:0 0 15px; font-size:15px; line-height:24px; color:#555555;'>
                Tu pedido ya está preparado y será entregado dentro de las
                próximas <strong>24 horas</strong>.
            </p>

            <p style='margin:0 0 25px; font-size:15px; line-height:24px; color:#555555;'>
                Si tenés alguna consulta relacionada con tu pedido,
                podés comunicarte con Droguería FB.
            </p>

            <p style='margin:0; font-size:15px; line-height:24px; color:#555555;'>
                Gracias por confiar en <strong>Droguería FB</strong>.
            </p>
        ";

        $mail->Body = $this->plantillaBase($contenido);

        if (!$mail->send()) {
            error_log(
                'Error correo pedido confirmado: ' .
                $mail->ErrorInfo
            );

            return false;
        }

        error_log(
            'Correo pedido confirmado enviado correctamente'
        );

        return true;
    }

    /**
     * Email interno enviado al área de facturación
     */
    public function enviarAvisoFacturacion($pedido)
    {
        $mail = $this->configurarMailer();

        $mail->addAddress(
            $_ENV['EMAIL_FACTURACION']
        );

        $numeroPedido = str_pad(
            $pedido['id'],
            6,
            '0',
            STR_PAD_LEFT
        );

        $cliente = $this->escapar(
            $pedido['client_name']
        );

        $vendedor = $this->escapar(
            $pedido['seller_name']
        );

        $observaciones = $this->escapar(
            $pedido['observations'] ?? ''
        );

        $fecha = date(
            'd/m/Y H:i',
            strtotime($pedido['created_at'])
        );

        $mail->Subject =
            "Nuevo Pedido #{$numeroPedido} - Droguería FB";

        $contenido = "
            <h1 style='margin:0 0 10px; font-size:24px; color:#222222;'>
                Nuevo pedido recibido
            </h1>

            <p style='margin:0 0 25px; font-size:15px; line-height:24px; color:#666666;'>
                Se ha registrado un nuevo pedido que requiere revisión.
            </p>

            <!-- INFORMACIÓN DEL PEDIDO -->
            <table
                width='100%'
                cellpadding='0'
                cellspacing='0'
                border='0'
                style='margin-bottom:25px; background-color:#f8f9fa; border-radius:8px;'
            >
                <tr>
                    <td style='padding:20px;'>

                        <p style='margin:0 0 5px; font-size:12px; color:#777777;'>
                            NÚMERO DE PEDIDO
                        </p>

                        <p style='margin:0 0 18px; font-size:22px; font-weight:bold; color:#222222;'>
                            #{$numeroPedido}
                        </p>

                        <p style='margin:0 0 5px; font-size:13px; color:#555555;'>
                            <strong>Cliente:</strong> {$cliente}
                        </p>

                        <p style='margin:0 0 5px; font-size:13px; color:#555555;'>
                            <strong>Vendedor:</strong> {$vendedor}
                        </p>

                        <p style='margin:0; font-size:13px; color:#555555;'>
                            <strong>Fecha:</strong> {$fecha}
                        </p>

                    </td>
                </tr>
            </table>
        ";

        // OBSERVACIONES

        if (!empty($pedido['observations'])) {

            $contenido .= "
                <table
                    width='100%'
                    cellpadding='0'
                    cellspacing='0'
                    border='0'
                    style='margin-bottom:25px;'
                >
                    <tr>

                        <td
                            width='6'
                            style='background-color:#f0ad4e;'
                        >
                            &nbsp;
                        </td>

                        <td
                            style='padding:15px 18px; background-color:#fff8e8;'
                        >

                            <p style='margin:0 0 5px; font-size:12px; color:#777777;'>
                                OBSERVACIONES
                            </p>

                            <p style='margin:0; font-size:14px; line-height:22px; color:#555555;'>
                                {$observaciones}
                            </p>

                        </td>

                    </tr>
                </table>
            ";
        }

        // PRODUCTOS

        $contenido .= "
            <h2 style='margin:0 0 15px; font-size:18px; color:#222222;'>
                Productos
            </h2>

            <table
                width='100%'
                cellpadding='0'
                cellspacing='0'
                border='0'
                style='margin-bottom:25px; border-collapse:collapse; font-size:13px;'
            >

                <thead>

                    <tr style='background-color:#f1f3f5;'>

                        <th
                            align='left'
                            style='padding:12px 10px; border-bottom:1px solid #dddddd; color:#555555;'
                        >
                            Producto
                        </th>

                        <th
                            align='center'
                            style='padding:12px 10px; border-bottom:1px solid #dddddd; color:#555555;'
                        >
                            Cant.
                        </th>

                        <th
                            align='right'
                            style='padding:12px 10px; border-bottom:1px solid #dddddd; color:#555555;'
                        >
                            Precio
                        </th>

                        <th
                            align='right'
                            style='padding:12px 10px; border-bottom:1px solid #dddddd; color:#555555;'
                        >
                            Subtotal
                        </th>

                    </tr>

                </thead>

                <tbody>
        ";

        foreach ($pedido['items'] as $item) {

            $producto = $this->escapar(
                $item['product_name']
            );

            $precio = number_format(
                $item['price'],
                2,
                ',',
                '.'
            );

            $subtotal = number_format(
                $item['subtotal'],
                2,
                ',',
                '.'
            );

            $contenido .= "
                <tr>

                    <td
                        style='padding:12px 10px; border-bottom:1px solid #eeeeee; color:#444444;'
                    >
                        {$producto}
                    </td>

                    <td
                        align='center'
                        style='padding:12px 10px; border-bottom:1px solid #eeeeee; color:#444444;'
                    >
                        {$item['quantity']}
                    </td>

                    <td
                        align='right'
                        style='padding:12px 10px; border-bottom:1px solid #eeeeee; color:#444444;'
                    >
                        \${$precio}
                    </td>

                    <td
                        align='right'
                        style='padding:12px 10px; border-bottom:1px solid #eeeeee; color:#444444; font-weight:bold;'
                    >
                        \${$subtotal}
                    </td>

                </tr>
            ";
        }

        $contenido .= "
                </tbody>

            </table>
        ";

        // TOTAL

        $total = number_format(
            $pedido['total'],
            2,
            ',',
            '.'
        );

        $contenido .= "
            <table
                width='100%'
                cellpadding='0'
                cellspacing='0'
                border='0'
                style='margin-bottom:25px;'
            >
                <tr>

                    <td
                        align='right'
                        style='padding:15px 10px; font-size:15px; color:#555555;'
                    >
                        <strong>TOTAL DEL PEDIDO</strong>
                    </td>

                    <td
                        align='right'
                        style='padding:15px 10px; font-size:22px; font-weight:bold; color:#198754;'
                    >
                        \${$total}
                    </td>

                </tr>
            </table>

            <p style='margin:0; font-size:14px; line-height:22px; color:#666666;'>
                Ingresá al panel administrativo de Droguería FB para
                revisar y gestionar este pedido.
            </p>
        ";

        $mail->Body = $this->plantillaBase($contenido);

        if (!$mail->send()) {

            error_log(
                'Error correo facturación: ' .
                $mail->ErrorInfo
            );

            return false;
        }

        error_log(
            'Correo de facturación enviado correctamente'
        );

        return true;
    }

    /**
     * Email enviado al cliente cuando realiza un pedido
     */
    public function enviarPedidoRecibido($pedido)
    {
        $mail = $this->configurarMailer();

        $mail->addAddress(
            $this->email,
            $this->name
        );

        $numeroPedido = str_pad(
            $pedido['id'],
            6,
            '0',
            STR_PAD_LEFT
        );

        $nombre = $this->escapar(
            $this->name
        );

        $mail->Subject =
            "Recibimos tu pedido #{$numeroPedido} - Droguería FB";

        $contenido = "
            <h1 style='margin:0 0 20px; font-size:24px; color:#222222;'>
                ¡Pedido recibido!
            </h1>

            <p style='margin:0 0 15px; font-size:16px; line-height:24px;'>
                Hola <strong>{$nombre}</strong>,
            </p>

            <p style='margin:0 0 25px; font-size:15px; line-height:24px; color:#555555;'>
                Recibimos correctamente tu pedido y ya estamos trabajando en él.
            </p>

            <!-- INFORMACIÓN DEL PEDIDO -->
            <table
                width='100%'
                cellpadding='0'
                cellspacing='0'
                border='0'
                style='margin-bottom:25px; background-color:#f8f9fa; border-radius:8px;'
            >
                <tr>
                    <td style='padding:20px;'>

                        <p style='margin:0 0 8px; font-size:13px; color:#777777;'>
                            NÚMERO DE PEDIDO
                        </p>

                        <p style='margin:0; font-size:22px; font-weight:bold; color:#222222;'>
                            #{$numeroPedido}
                        </p>

                    </td>
                </tr>
            </table>

            <!-- ESTADO -->
            <table
                width='100%'
                cellpadding='0'
                cellspacing='0'
                border='0'
                style='margin-bottom:25px;'
            >
                <tr>

                    <td
                        width='12'
                        style='background-color:#198754; border-radius:6px 0 0 6px;'
                    >
                        &nbsp;
                    </td>

                    <td
                        style='padding:15px 18px; background-color:#eef8f2;'
                    >

                        <p style='margin:0 0 5px; font-size:13px; color:#555555;'>
                            ESTADO DEL PEDIDO
                        </p>

                        <p style='margin:0; font-size:15px; font-weight:bold; color:#198754;'>
                            En proceso
                        </p>

                    </td>

                </tr>
            </table>

            <p style='margin:0 0 15px; font-size:15px; line-height:24px; color:#555555;'>
                Tu pedido será procesado y te llegará dentro de las próximas
                <strong>24 a 48 horas</strong>.
            </p>

            <p style='margin:0 0 25px; font-size:15px; line-height:24px; color:#555555;'>
                Te enviaremos otro correo cuando tu pedido haya sido confirmado.
            </p>

            <p style='margin:0; font-size:15px; line-height:24px; color:#555555;'>
                Gracias por confiar en <strong>Droguería FB</strong>.
            </p>
        ";

        $mail->Body = $this->plantillaBase($contenido);

        if (!$mail->send()) {

            error_log(
                'Error correo pedido recibido: ' .
                $mail->ErrorInfo
            );

            return false;
        }

        error_log(
            'Correo pedido recibido enviado correctamente'
        );

        return true;
    }
}

