<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../lib/phpmailer/src/Exception.php';
require_once __DIR__ . '/../lib/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../lib/phpmailer/src/SMTP.php';

/* Lecture du fichier .env */
$env = parse_ini_file(__DIR__ . '/../.env');

function templateEmail($message)
{
    return "
    <div style='background-color:#f6f1e8; padding:30px; font-family:Arial, sans-serif; color:#333;'>
        <div style='max-width:600px; margin:0 auto; background-color:#ffffff; padding:30px; border-radius:10px;'>
            
            <h1 style='margin-top:0; color:#8b5e34; text-align:center;'>
                Vite & Gourmand
            </h1>

            <div style='font-size:16px; line-height:1.6;'>
                $message
            </div>

            <hr style='border:none; border-top:1px solid #ddd; margin:25px 0;'>

            <p style='font-size:13px; color:#777; text-align:center;'>
                Ceci est un email automatique, merci de ne pas y répondre directement.
            </p>

        </div>
    </div>
    ";
}

function envoyerEmail($destinataire, $sujet, $message)
{
    global $env;

    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = $env['MAIL_HOST'];
    $mail->Port = (int) $env['MAIL_PORT'];

    if (!empty($env['MAIL_USERNAME'])) {
        $mail->SMTPAuth = true;
        $mail->Username = $env['MAIL_USERNAME'];
        $mail->Password = $env['MAIL_PASSWORD'];
    }

    $mail->setFrom($env['MAIL_FROM'], $env['MAIL_FROM_NAME']);
    $mail->addAddress($destinataire);

    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = $sujet;
    $mail->Body = templateEmail($message);
    $mail->AltBody = strip_tags($message);

    return $mail->send();
}