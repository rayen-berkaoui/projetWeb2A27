<?php
session_start();

require_once('../../model/user.php');
require_once('../../controler/userc.php');

if (!isset($_SESSION['user'])) {
    header("Location: l2.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $numero = trim($_POST['numero']);

    $oldUsername = $_SESSION['user']['username']; // Identify current user

    $controller = new UserController();
    $updated = $controller->updateUser($oldUsername, $username, $email, $numero);

    if ($updated) {
        // Update session data with new values
        $_SESSION['user']['username'] = $username;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['numero'] = $numero;

        // Set a flag for success
        $_SESSION['update_success'] = true;
    } else {
        // Set a flag for failure if needed (optional)
        $_SESSION['update_error'] = "Erreur lors de la mise à jour.";
    }

    header("Location: profile.php");
    exit();
}
?>
