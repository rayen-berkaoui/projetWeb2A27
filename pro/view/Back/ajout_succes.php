<?php
include('../../config.php'); // Vérifie si le chemin est correct
$conn = config::getConnexion();

// Traitement du formulaire d'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification des données reçues
    if (isset($_POST['username'], $_POST['email'], $_POST['numero'], $_POST['mdp'], $_POST['role'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $numero = $_POST['numero'];
        $mdp = $_POST['mdp']; // Plus de password_hash ici
        $role = $_POST['role'];

        // Validation de l'email et du numéro
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "L'email est invalide.";
        } elseif (!preg_match('/^\d{8}$/', $numero)) {
            echo "Le numéro doit être composé de 8 chiffres.";
        } else {
            // Insérer les données dans la base de données
            try {
                $stmt = $conn->prepare("INSERT INTO utilisateurs (username, email, numero, mdp, role) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$username, $email, $numero, $mdp, $role]);

                // Rediriger vers la page avec le message de succès
                header("Location: ajout_succes.php?ajout=success");
                exit();
            } catch (PDOException $e) {
                // Gérer l'erreur si l'insertion échoue
                echo "Erreur : " . $e->getMessage();
            }
        }
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout Réussi</title>
    <link rel="stylesheet" href="../assets/css/styleb.css" />
    <link rel="stylesheet" href="../assets/css/styless.css" />
    <script src="../assets/js/scripts.js"></script>
</head>
<body>
    <!-- Barre latérale -->
    <?php include('sidebar.php'); ?>

    <div class="main-content">
        <meta http-equiv="refresh" content="1; url=utilisateurs.php">
        <p>Veuillez patienter un moment...</p>
    </div>
</body>
</html>
