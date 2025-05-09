<?php
require_once('C:\xampp\htdocs\2A27\pro\config.php');
$conn = config::getConnexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username'], $_POST['email'], $_POST['numero'], $_POST['mdp'], $_POST['role'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $numero = $_POST['numero'];
        $mdp = $_POST['mdp'];
        $role = $_POST['role'];

        // Validate email and number
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "L'email est invalide.";
            exit();
        } elseif (!preg_match('/^\d{8}$/', $numero)) {
            echo "Le numéro doit être composé de 8 chiffres.";
            exit();
        }

        try {
            $stmt = $conn->prepare("INSERT INTO utilisateurs (username, email, numero, mdp, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$username, $email, $numero, $mdp, $role]);

            // Redirect after successful insert
            header("Location: http://127.0.0.1/2A27/admin/user");
            exit();
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            exit();
        }
    } else {
        echo "Tous les champs sont obligatoires.";
        exit();
    }
}
?>
