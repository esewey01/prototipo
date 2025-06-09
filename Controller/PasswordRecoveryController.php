<?php
require_once '../Model/Conexion.php';
require_once '../PHPMailer/PHPMailer.php';
require_once '../PHPMailer/SMTP.php';
require_once '../PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

    if (!$email) {
        session_start();
        $_SESSION['mensaje'] = "Correo electrónico inválido.";
        $_SESSION['alerta'] = "alert-danger";
        header("Location: ../index.php");
        exit;
    }

    try {
        // Conectar a la base de datos
        $conexion = new Conexion();
        $db = $conexion->getConnection();

        // Buscar usuario por email
        $usuario = $conexion->getUserByEmail($email); // <-- Usamos el método ya existente

        if (!$usuario) {
            session_start();
            $_SESSION['mensaje'] = "No se encontró ningún usuario con ese correo.";
            $_SESSION['alerta'] = "alert-warning";
            header("Location: ../index.php");
            exit;
        }


        // Generar una nueva contraseña temporal
        $nueva_contrasena = bin2hex(random_bytes(6)); // Contraseña temporal segura
        //$hashedPassword = hash('sha256', utf8_encode($nueva_contrasena));

        // Actualizar en la base de datos usando el método updatePassword
        $resultadoUpdate = $conexion->updatePassword($usuario['id_usuario'], $nueva_contrasena);

        if (!$resultadoUpdate) {
            session_start();
            $_SESSION['mensaje'] = "Error al actualizar la contraseña en la base de datos.";
            $_SESSION['alerta'] = "alert-danger";
            header("Location: ../index.php");
            exit;
        }

        // Envío del correo
        $mail = new PHPMailer(true);
        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Cambia esto si usas otro proveedor
            $mail->SMTPAuth   = true;
            $mail->Username   = 'dmau639@gmail.com'; // Tu correo
            $mail->Password   = 'kzao ahwl ynyv kocd'; // Contraseña de aplicación
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Destinatarios
            $mail->setFrom('dmau639@gmail.com', 'Soporte UPIICSA FOOD');
            $mail->addAddress($email, $usuario['login']);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Nuevo Password - UPIICSA FOOD';

            $mensaje = '
                <h3>Recuperación de Contraseña</h3>
                <p>Hola <strong>' . htmlspecialchars($usuario['login']) . '</strong>,</p>
                <p>Se ha generado una nueva contrasena temporal:</p>
                <p><strong>Tu nueva contrasena es:</strong> <code>' . htmlspecialchars($nueva_contrasena) . '</code></p>
                <p>Te recomendamos cambiarla despues de iniciar sesion.</p>
                <br>
                <p>Saludos,<br>Equipo de Soporte UPIICSA FOOD</p>
            ';

            $mail->Body = $mensaje;

            $mail->send();

            // Mensaje de éxito
            session_start();
            $_SESSION['mensaje'] = "La nueva contraseña fue enviada a tu correo.";
            $_SESSION['alerta'] = "alert-success";
        } catch (Exception $e) {
            session_start();
            $_SESSION['mensaje'] = "No se pudo enviar el correo. Error: " . $mail->ErrorInfo;
            $_SESSION['alerta'] = "alert-danger";
        }
    } catch (Exception $e) {
        session_start();
        $_SESSION['mensaje'] = "Error interno: " . $e->getMessage();
        $_SESSION['alerta'] = "alert-danger";
    }

    header("Location: ../index.php");
    exit;
}
