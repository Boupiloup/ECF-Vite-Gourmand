<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../lib/phpmailer/src/Exception.php';
require_once __DIR__ . '/../lib/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../lib/phpmailer/src/SMTP.php';

function envoyerEmail($destinataire, $sujet, $message)
{
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'localhost';
    $mail->Port = 1025;

    $mail->setFrom('noreply@viteetgourmand.fr', 'Vite et Gourmand');
    $mail->addAddress($destinataire);

    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = $sujet;
    $mail->Body = $message;
    $mail->AltBody = strip_tags($message);

    return $mail->send();
}