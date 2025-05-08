<?php
session_start(); // Toujours au début

$message = "";

// Vérification du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['code'])) {
        $enteredCode = trim($_POST['code']);
        $correctCode = isset($_SESSION['verification_code']) ? (string)$_SESSION['verification_code'] : null; // 👈 convertir en string

        if ($correctCode && $enteredCode === $correctCode) {
            // ✅ Code correct → aller à la page de nouveau mot de passe
            header('Location: new-password.php');
            exit;
        } else {
            // ❌ Code incorrect
            $message = "❌ Code de vérification incorrect.";
        }
    } else {
        $message = "Veuillez entrer le code de vérification.";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vérification du Code</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .container { max-width: 400px; margin: 50px auto; padding: 20px; background: #fff; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); text-align: center; }
        .error-message { color: red; margin-top: 10px; margin-bottom: 15px; font-weight: 500; }
        input[type="text"] { width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #ccc; }
        button { width: 100%; padding: 10px; background: #4CAF50; color: white; border: none; border-radius: 5px; font-weight: bold; }
        button:hover { background: #45a049; }
        a { color: #4CAF50; font-weight: bold; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="container">
    <h2>Vérification du Code</h2>
    <p>Un code a été envoyé à votre email 📧</p>

    <form method="POST">
        <label for="code">Entrez le code :</label>
        <input type="text" name="code" id="code" placeholder="Code de vérification">
        
        <?php if (!empty($message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <button type="submit">Valider</button>
    </form>

    <p>Pas reçu ? <a href="forgotpassword.php">Renvoyer le code</a></p>
</div>
</body>
</html>
