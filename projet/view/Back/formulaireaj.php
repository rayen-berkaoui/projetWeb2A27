<?php
include('../../config.php'); // Vérifie si le chemin est correct
$conn = config::getConnexion();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Utilisateur</title>
    <link rel="stylesheet" href="../assets/css/styleb.css" />
    <link rel="stylesheet" href="../assets/css/stylef.css" />
</head>
<body>
    <!-- Barre latérale -->
    <?php include('sidebar.php'); ?>
    <div class="form-container">
        <h2>Ajouter un Utilisateur</h2>

        <form action="ajout_succes.php" method="POST" id="form-ajouter" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="username">Nom d'utilisateur:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="numero">Numéro (8 chiffres):</label>
                <input type="text" id="numero" name="numero" required>
            </div>

            <div class="form-group">
                <label for="mdp">Mot de passe :</label>
                <input type="password" id="mdp" name="mdp" required>
            </div>

            <div class="form-group">
                <label for="role">Rôle:</label>
                <select id="role" name="role" required>
                    <option value="utilisateur">Utilisateur</option>
                    <option value="organisateur">Organisateur</option>
                </select>
            </div>

            <button type="submit" class="submit-btn">Ajouter</button>
        </form>
    </div>

    <script src="../assets/js/scriptf.js" defer></script>
</body>
</html>
