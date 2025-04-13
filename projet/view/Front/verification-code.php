<?php
session_start();

// Récupérer le code de vérification envoyé par email
$sentCode = $_SESSION['verification_code'] ?? '';

// Message d'erreur
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enteredCode = $_POST['code'] ?? '';

    if ($enteredCode === $sentCode) {
        // Code vérifié avec succès, rediriger vers la page de réinitialisation du mot de passe
        header("Location: reset-password.php?email=" . urlencode($_GET['email']));
        exit();
    } else {
        $message = "The verification code is incorrect. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="../assets/js/script.js" defer></script>
    <title>Verification Code</title>
</head>
<body>
    <div class="container">
        <h2>Verification Code</h2>
        <p>We’ve sent you a verification code to your email </p>

        <?php if (!empty($message)) echo "<p style='color:red;'>$message</p>"; ?>

        <form id="verification-form" method="POST">
            <label for="code">Enter Verification Code</label>
            <input type="text" id="code" name="code" placeholder="Verification Code" required>
            <button type="submit">Submit</button>
        </form>

        <!-- Option to resend the verification code -->
        <p>If you didn't receive the code, <a href="forgotpassword.php">click here to resend it</a>.</p>
    </div>
</body>
</html>
