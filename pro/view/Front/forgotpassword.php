<?php
session_start();
require 'C:\xampp\htdocs\lezm\pro\model\user.php';
require 'C:\xampp\htdocs\lezm\pro\controler\userc.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = trim($_POST['email']);

    $userController = new UserController();
    $user = $userController->getUserByEmail($email); // 📥 Check if email exists in database

    if ($user) {
        $code = random_int(100000, 999999); // 🔥 Generate 6-digit code
        $_SESSION['verification_code'] = $code;
        $_SESSION['reset_email'] = $email;

        $mail = new PHPMailer(true);

        try {
            // SMTP Configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'delizrdaddou@gmail.com'; // Your sender email
            $mail->Password = 'rpjkorvdmwlvysdm';            // Your app password (secure it later)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Sender and recipient
            $mail->setFrom('delizrdaddou@gmail.com', 'Delizar Chaaraoui');
            $mail->addAddress($email);

            // Email content
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->Subject = 'Code de vérification - Réinitialisation du mot de passe';
            $mail->Body = "
                <html>
                <head><meta charset='UTF-8'></head>
                <body>
                    Bonjour,<br><br>
                    Voici votre <strong>code de vérification</strong> : <strong>$code</strong><br><br>
                    Veuillez utiliser ce code pour réinitialiser votre mot de passe.<br><br>
                    Cordialement,<br>
                    Delizar Chaaraoui
                </body>
                </html>
            ";

            $mail->send();
            header('Location: verification-code.php'); // 🚀 Redirect after sending
            exit;

        } catch (Exception $e) {
            $message = "Erreur d'envoi de l'email : " . htmlspecialchars($mail->ErrorInfo);
        }

    } else {
        $message = "❌ Cette adresse email n'existe pas dans notre base de données.";
    }
}
?>

<!-- HTML Part -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST'] ?>/lezm/pro/view/assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Mot de passe oublié ?</h2>
        <p>Veuillez saisir votre adresse e-mail pour recevoir un code de vérification.</p>

        <?php if (!empty($message)): ?>
            <p style="color:red;"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form method="POST" onsubmit="return validateEmail()">
            <input type="email" id="email" name="email" placeholder="Votre adresse e-mail" required>
            <div id="email-error" style="color: red; font-size: 0.9em; margin-top: 5px;"></div>
            <button type="submit">Continuer</button>
        </form>

        <a href="lezm/login" class="back-btn">Retour à la connexion</a>
    </div>

    <script>
        function validateEmail() {
            const emailField = document.getElementById('email');
            const emailError = document.getElementById('email-error');
            const emailValue = emailField.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (emailValue === '') {
                emailError.textContent = 'Veuillez saisir votre adresse email.';
                return false;
            } else if (!emailRegex.test(emailValue)) {
                emailError.textContent = 'Adresse email invalide.';
                return false;
            } else {
                emailError.textContent = '';
                return true;
            }
        }
    </script>
</body>
</html>
