<?php
require_once '../../../src/models/Partner.php';
require_once '../../../config/Database.php';
require_once '../../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (empty($_POST)) {
            throw new Exception('No data provided');
        }

        $partner = new Partner();
        $result = $partner->addPartner($_POST);

        // Send email after partner is added
        $mail = new PHPMailer(true);
        $config = require '../../../config/mail.php';
        
        try {
            $mail->isSMTP();
            $mail->Host = $config['smtp']['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $config['smtp']['username'];
            $mail->Password = $config['smtp']['password'];
            $mail->SMTPSecure = $config['smtp']['encryption'];
            $mail->Port = $config['smtp']['port'];

            $mail->setFrom($config['smtp']['username'], 'Marketing Team');
            $mail->addAddress($_POST['email'], $_POST['nom_entreprise']);

            $mail->isHTML(true);
            $mail->Subject = 'Welcome to Our Marketing Campaign!';
            $mail->Body = "
                <h2>Hello {$_POST['nom_entreprise']}!</h2>
                <p>Thank you for becoming our partner.</p>
                <p>We're excited to have you on board and look forward to a successful partnership.</p>
                <br>
                <p>Best regards,<br>Marketing Team</p>
            ";

            $mail->send();
        } catch (Exception $e) {
            error_log("Email sending failed: {$mail->ErrorInfo}");
        }

        echo json_encode([
            'success' => true,
            'message' => 'Partner added successfully and email sent'
        ]);
        
    } else {
        throw new Exception('Invalid request method');
    }
} catch (Exception $e) {
    error_log("Error in add_partner.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred',
        'error' => $e->getMessage()
    ]);
}
