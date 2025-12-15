<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';
include("conexion.php");

if(isset($_POST['email'])){
    $email = $_POST['email'];

    $stmt = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $tipo = "";
    $id = null;

    if($result->num_rows > 0){
        $tipo = "usuarios";
        $id = $result->fetch_assoc()['id_usuario'];
    } else {
        $stmt = $conexion->prepare("SELECT id_cliente FROM clientes WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            $tipo = "clientes";
            $id = $result->fetch_assoc()['id_cliente'];
        }
    }

    if($tipo != ""){
        $codigo = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $expira = date("Y-m-d H:i:s", strtotime("+15 minutes"));

        if($tipo == "usuarios"){
            $stmt = $conexion->prepare("UPDATE usuarios SET codigo_recuperacion=?, expiracion_codigo=? WHERE id_usuario=?");
        } else {
            $stmt = $conexion->prepare("UPDATE clientes SET codigo_recuperacion=?, expiracion_codigo=? WHERE id_cliente=?");
        }
        $stmt->bind_param("ssi", $codigo, $expira, $id);
        $stmt->execute();

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.hostinger.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'pruebas@avasis-mx.com';
            $mail->Password = '0q6%ak4hYj.4Eg{G';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('pruebas@avasis-mx.com', 'Soporte tecnico');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Recuperar clave';

            $mailContent = '
            <html>
            <head>
                <style>
                    body {font-family: Arial, sans-serif; background-color: #f4f4f4; margin:0; padding:0;}
                    .container {max-width: 600px; margin: 50px auto; background-color: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);}
                    .header {text-align: center; padding-bottom: 20px;}
                    .header img {width: 120px;}
                    .content {font-size: 16px; color: #333;}
                    .code {display: block; text-align: center; font-size: 24px; font-weight: bold; background: #f0f0f0; padding: 10px; margin: 20px 0; border-radius: 5px; letter-spacing: 5px;}
                    .footer {font-size: 12px; color: #777; text-align: center; padding-top: 20px;}
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="content">
                        <p>Hola,</p>
                        <p>Hemos recibido una solicitud para restablecer tu contraseña. Ingresa el siguiente código en la página de recuperación:</p>
                        <span class="code">'.$codigo.'</span>
                        <p>Este código expirará en 15 minutos.</p>
                        <p>Si no solicitaste este cambio, ignora este correo.</p>
                    </div>
                    <div class="footer">
                        <p>© '.date('Y').' Avasis. Todos los derechos reservados.</p>
                    </div>
                </div>
            </body>
            </html>';

            $mail->Body = $mailContent;
            $mail->send();
            header("Location: codigo_de_recuperacion.php?email=" . urlencode($email) . "&msg=enviado");
            exit;

        } catch (Exception $e) {
            header("Location: auth_reset_password.php?msg=error_envio");
            exit;
        }

    } else {
        header("Location: auth_reset_password.php?msg=email_no_registrado");
        exit;
    }
} else {
    header("Location: auth_reset_password.php");
    exit;
}
?>
