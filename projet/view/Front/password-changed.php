<?php
// Assuming the password update success is passed in the URL query parameters.
$passwordUpdated = isset($_GET['success']) && $_GET['success'] == 'true';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Password Changed</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col align-items-center flex-col">
                <div class="text">
                    <h2>Your password has been changed</h2>
                    <p>Now you can log in with your new password </p>
                </div>
                <a href="l2.php">
                    <button>Login Now</button>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
