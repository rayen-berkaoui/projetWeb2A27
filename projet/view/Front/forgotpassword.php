<?php
session_start();

require_once('../../model/user.php');
require_once('../../controler/userc.php');

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? ''; // Récupérer l'email soumis

    // Créer un objet controller
    $controller = new UserController();
    $user = $controller->getUserByEmail($email); // Vérifier si l'email existe dans la base de données

    if ($user) {
        // Générer un code de vérification aléatoire
        $verificationCode = rand(100000, 999999); 
        $_SESSION['verification_code'] = $verificationCode; // Stocker le code de vérification dans la session
    
        // Logique pour envoyer le code par email (remplacer avec une vraie fonction d'envoi d'email)
        // mail($email, 'Verification Code', 'Your verification code is: ' . $verificationCode);
    
        // Message de succès
        $message = "We have sent a verification code to your email. Please check your inbox.";
    
        // Rediriger vers la page de vérification
        header("Location: verificationcode.php?email=test@example.com&timestamp=" . time());
        exit();
        
        
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css"> <!-- Assurez-vous que ce chemin est correct -->
    <script src="../assets/js/script.js" defer></script> <!-- Assurez-vous que ce chemin est correct -->
    <title>Forgot Password</title>
</head>
<body>
    <div class="container">
        <h2>Forgot Your Password?</h2>
        <?php if ($message) echo "<p style='color:red;'>" . htmlspecialchars($message) . "</p>"; ?> <!-- Affiche le message d'erreur si nécessaire -->
        <form id="forgot-password-form" method="POST">
            <label for="email">Enter Your Email Address</label>
            <input type="email" name="email" id="email" placeholder="Email Address" required>
            <button type="submit">Continue</button>
        </form>
        <a href="l2.php" class="back-btn">Return to Sign In</a> <!-- Lien pour revenir à la page de connexion -->
    </div>

    <script>
        // Validation de l'email avant la soumission du formulaire
        document.getElementById('forgot-password-form').addEventListener('submit', function(event) {
            const email = document.getElementById('email').value;
            const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

            // Vérifie si l'email soumis est valide
            if (!emailPattern.test(email)) {
                alert('Please enter a valid email address.');
                event.preventDefault(); // Empêche la soumission si l'email est invalide
            }
        });
    </script>
</body>
</html>
