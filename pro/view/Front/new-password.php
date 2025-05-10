<?php
session_start();
require_once('../../model/user.php');
require_once('../../controler/userc.php');
require_once('../../config.php');

$message = "";

// Protection: make sure reset_email exists
if (empty($_SESSION['reset_email'])) {
  // Mot de passe changé avec succès
header('Location: password-changed.php?success=true');
exit;

}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = trim($_POST['password'] ?? '');
    $confirmPassword = trim($_POST['confirm-password'] ?? '');

    if (!empty($newPassword) && !empty($confirmPassword)) {
        if ($newPassword === $confirmPassword) {
            $email = $_SESSION['reset_email'];

            try {
                $pdo = config::getConnexion();
                $stmt = $pdo->prepare("SELECT id_user FROM utilisateurs WHERE email = :email");
                $stmt->execute(['email' => $email]);
                $user = $stmt->fetch();

                if ($user) {
                    $userId = $user['id_user'];

                    // Update password directly (no hash)
                    $stmtUpdate = $pdo->prepare("UPDATE utilisateurs SET mdp = :mdp WHERE id_user = :user_id");
                    $stmtUpdate->execute([
                        'mdp' => $newPassword,
                        'user_id' => $userId
                    ]);

                    // Clean session sensitive data
                    unset($_SESSION['reset_email']);
                    unset($_SESSION['verification_code']);

                    // Redirect
                    header('Location: password-changed.php');
                    exit;
                } else {
                    $message = "Utilisateur non trouvé.";
                }
            } catch (PDOException $e) {
                $message = "Erreur base de données : " . htmlspecialchars($e->getMessage());
            }
        } else {
            $message = "❌ Les mots de passe ne correspondent pas.";
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialiser le mot de passe</title>
    <link rel="stylesheet" href="C:\xampp\htdocs\2A27\pro\view\assets\css\styles.css">
    <style>
        /* Your CSS here (same as you sent) */
    </style>
</head>
<body>
    <div class="container">
        <h2>Réinitialiser votre mot de passe</h2>

        <?php if (!empty($message)) : ?>
            <div class="error-message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form method="POST" onsubmit="return validatePasswords()">
            <label for="password">Nouveau mot de passe</label>
            <div class="password-container">
                <input type="password" name="password" id="password" placeholder="Nouveau mot de passe">
                <i id="toggle-eye-password" class="bx bx-hide eye-icon" onclick="togglePasswordVisibility('password', 'toggle-eye-password')"></i>
            </div>

            <label for="confirm-password">Confirmer le mot de passe</label>
            <div class="password-container">
                <input type="password" name="confirm-password" id="confirm-password" placeholder="Confirmer le mot de passe">
                <i id="toggle-eye-confirm-password" class="bx bx-hide eye-icon" onclick="togglePasswordVisibility('confirm-password', 'toggle-eye-confirm-password')"></i>
            </div>

            <button type="submit">Continuer</button>
        </form>
    </div>

    <script>
    function togglePasswordVisibility(inputId, eyeIconId) {
        const input = document.getElementById(inputId);
        const eyeIcon = document.getElementById(eyeIconId);

        if (input.type === "password") {
            input.type = "text";
            eyeIcon.className = "bx bx-show eye-icon";
        } else {
            input.type = "password";
            eyeIcon.className = "bx bx-hide eye-icon";
        }
    }

    function validatePasswords() {
        const password = document.getElementById("password").value.trim();
        const confirmPassword = document.getElementById("confirm-password").value.trim();

        if (password === "" || confirmPassword === "") {
            alert("Veuillez remplir tous les champs.");
            return false;
        }
        if (password !== confirmPassword) {
            alert("Les mots de passe ne correspondent pas.");
            return false;
        }
        if (password.length < 6) {
            alert("Le mot de passe doit contenir au moins 6 caractères.");
            return false;
        }
        return true;
    }
    </script>
</body>
</html>
