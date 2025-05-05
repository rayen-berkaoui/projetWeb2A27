<?php
require_once __DIR__ . '/../lib/phpmailer/PHPMailer.php';
require_once __DIR__ . '/../lib/phpmailer/SMTP.php';
require_once __DIR__ . '/../lib/phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService {
    public static function envoyerMailRemerciement($emailClient, $nomClient) {
        $mail = new PHPMailer(true);

        try {
            // SMTP Gmail
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'sabsoubbdziri@gmail.com'; 
            $mail->Password   = 'rebb obpc qqdm ojur'; // mot de passe d'application
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('sabsoubbdziri@gmail.com', 'GreenMind');
            $mail->addAddress($emailClient, $nomClient);

            $mail->isHTML(true);
            $mail->Subject = 'Merci pour votre avis !';
            $mail->Body    = "Bonjour <strong>$nomClient</strong>,<br><br>Merci pour votre avis sur GreenMind 🌿<br><br>L'équipe GreenMind.";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Erreur email : " . $mail->ErrorInfo);
            return false;
        }
    }
}
