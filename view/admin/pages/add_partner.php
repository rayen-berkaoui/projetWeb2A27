<?php
require_once '../../../src/models/Partner.php';
require_once '../../../config/Database.php';

// Manually include PHPMailer if composer not set up
require_once '../../../vendor/phpmailer/phpmailer/src/Exception.php';
require_once '../../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once '../../../vendor/phpmailer/phpmailer/src/SMTP.php';

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

        // Send email
        try {
            $mail = new PHPMailer(true);
            $config = require '../../../config/mail.php';

            // Server settings
            $mail->SMTPDebug = 0; // Disable debug output
            $mail->isSMTP();
            $mail->Host = $config['smtp']['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $config['smtp']['username'];
            $mail->Password = $config['smtp']['password'];
            $mail->SMTPSecure = $config['smtp']['encryption'];
            $mail->Port = $config['smtp']['port'];

            // Recipients
            $mail->setFrom($config['smtp']['username'], 'Marketing Team');
            $mail->addAddress($_POST['email']);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Welcome to Our Partner Program';
            $mail->Body = "
                <h2>Welcome {$_POST['nom_entreprise']}!</h2>
                <p>Thank you for becoming our partner. We look forward to working with you.</p>
                <p>Your partnership details have been successfully recorded in our system.</p>
            ";

            $mail->send();
            echo json_encode([
                'success' => true,
                'message' => 'Partner added and email sent successfully'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => true,
                'message' => 'Partner added but email failed: ' . $e->getMessage()
            ]);
        }
    } else {
        throw new Exception('Invalid request method');
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
