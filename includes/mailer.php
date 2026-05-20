<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../lib/phpMailer/src/Exception.php';
require_once __DIR__ . '/../lib/phpMailer/src/PHPMailer.php';
require_once __DIR__ . '/../lib/phpMailer/src/SMTP.php';

/*
    Lecture du fichier .env en local.
    Sur Heroku, le fichier .env n'existe pas :
    les valeurs seront récupérées depuis les Config Vars.
*/
$env = file_exists(__DIR__ . '/../.env')
    ? parse_ini_file(__DIR__ . '/../.env')
    : [];

function getConfigValue($key, $env, $fallbackKey = null)
{
    return getenv($key)
        ?: ($env[$key] ?? null)
        ?: ($fallbackKey ? (getenv($fallbackKey) ?: ($env[$fallbackKey] ?? null)) : null);
}

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

    try {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = getConfigValue('SMTP_HOST', $env, 'MAIL_HOST');
        $mail->Port = (int) getConfigValue('SMTP_PORT', $env, 'MAIL_PORT');

        $mail->SMTPAuth = true;
        $mail->Username = getConfigValue('SMTP_USERNAME', $env, 'MAIL_USERNAME');
        $mail->Password = getConfigValue('SMTP_PASSWORD', $env, 'MAIL_PASSWORD');

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        $mail->setFrom(
            getConfigValue('SMTP_FROM_EMAIL', $env, 'MAIL_FROM'),
            getConfigValue('SMTP_FROM_NAME', $env, 'MAIL_FROM_NAME')
        );

        $mail->addAddress($destinataire);

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $sujet;
        $mail->Body = templateEmail($message);
        $mail->AltBody = strip_tags($message);

        return $mail->send();
    } catch (Exception $e) {
        error_log('Erreur envoi email : ' . $e->getMessage());
        return false;
    }
}
