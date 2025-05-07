<?php
require_once __DIR__ . '/../lib/phpmailer/PHPMailer.php';
require_once __DIR__ . '/../lib/phpmailer/SMTP.php';
require_once __DIR__ . '/../lib/phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService {
    public static function envoyerMailRemerciement($emailClient, $nomClient) {
        error_log("Tentative d'envoi d'email à : " . $emailClient);
        
        $mail = new PHPMailer(true);

        try {
            // Configuration du serveur SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Remplacez par votre serveur SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'sabsoubbdziri@gmail.com'; // Remplacez par votre email
            $mail->Password = 'rebb obpc qqdm ojur'; // Remplacez par votre mot de passe
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Paramètres de l'email
            $mail->setFrom('votre-email@gmail.com', 'Nom de votre site');
            $mail->addAddress($emailClient, $nomClient);
            $mail->CharSet = 'UTF-8';

            // Contenu
            $mail->isHTML(true);
            $mail->Subject = 'Merci pour votre avis !';
            $mail->Body = "
                <h2>Merci {$nomClient} !</h2>
                <p>Nous avons bien reçu votre avis et nous vous en remercions.</p>
                <p>Votre opinion est importante pour nous et nous permettra d'améliorer nos services.</p>
            ";

            $mail->send();
            error_log("Email envoyé avec succès à : " . $emailClient);
            return true;
        } catch (Exception $e) {
            error_log("Erreur d'envoi d'email : " . $mail->ErrorInfo);
            return false;
        }
    }
}
