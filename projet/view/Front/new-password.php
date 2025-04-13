<?php
require_once('../../model/user.php');
require_once('../../controler/userc.php');

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_GET['email'] ?? '';
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    if ($newPassword !== $confirmPassword) {
        $message = "Passwords do not match!";
    } else {
        $controller = new UserController();
        if ($controller->updatePassword($email, $newPassword)) {
            $message = "Password successfully updated!";
            header("Location: password-changed.php");
            exit();
        } else {
            $message = "Failed to update the password!";
        }
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
    <title>Create New Password</title>
</head>
<body>
    <div class="container">
        <h2>Create a New Password</h2>
        <p>Please create a new password for your account.</p>

        <?php if ($message) echo "<p style='color:red;'>$message</p>"; ?>

        <form id="new-password-form" method="POST">
            <label for="password">Create Password</label>
            <input type="password" name="password" id="password" placeholder="New Password" required>

            <label for="confirm-password">Confirm Password</label>
            <input type="password" name="confirm-password" id="confirm-password" placeholder="Confirm Password" required>

            <button type="submit">Change Password</button>
        </form>
    </div>
</body>
</html>
