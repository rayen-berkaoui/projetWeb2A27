<?php
session_start();

require_once('../../model/user.php');
require_once('../../controler/userc.php');

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user']['id'])) {
    header("Location: l2.php");
    exit();
}

// Traitement du formulaire de mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $newUsername = trim($_POST['username']);
    $newEmail    = trim($_POST['email']);
    $newNumero   = trim($_POST['numero']);

    // Récupérer l'ID de l'utilisateur actuel
    $userId = $_SESSION['user']['id'];

    // Connexion directe à la BDD pour la mise à jour
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=projetweb2a;charset=utf8", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare(
            "UPDATE utilisateurs
             SET username = ?, email = ?, numero = ?
             WHERE id_user = ?"
        );
        $updated = $stmt->execute([
            $newUsername,
            $newEmail,
            $newNumero,
            $userId
        ]);

        if ($updated) {
            // Mettre à jour les données en session
            $_SESSION['user']['username'] = $newUsername;
            $_SESSION['user']['email']    = $newEmail;
            $_SESSION['user']['numero']   = $newNumero;
            $_SESSION['update_success']   = true;
        } else {
            $_SESSION['update_error'] = "❌ Erreur lors de la mise à jour.";
        }

    } catch (PDOException $e) {
        $_SESSION['update_error'] = "❌ Échec BDD : " . $e->getMessage();
    }

    header("Location: profile.php");
    exit();
}
?>
