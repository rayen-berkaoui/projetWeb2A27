<?php
session_start();

require_once('C:/xampp/htdocs/2A27/pro/model/user.php');
require_once('C:/xampp/htdocs/2A27/pro/controler/userc.php');

// Init messages
$_SESSION['message_signup'] = $_SESSION['message_signup'] ?? '';
$_SESSION['message_signin'] = $_SESSION['message_signin'] ?? '';

// Password generator
function generatePassword($length = 12) {
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+-=[]{}|;:,.<>?';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $password;
}
$generatedPassword = generatePassword(12);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new UserController();

    // SIGN UP
    if (isset($_POST['sign_up'])) {
        $username = trim($_POST['username']);
        $email    = trim($_POST['email']);
        $numero   = '00000000';
        $mdp      = trim($_POST['password']);
        $role     = 1; // Default role ID (e.g., '1' for standard user)

        if ($username !== '' && $email !== '' && $mdp !== '') {
            $user   = new User($username, $role, $email, $mdp);
            $result = $controller->addUser($user);
            $_SESSION['message_signup'] = $result
                ? '✅ Utilisateur enregistré avec succès !'
                : '❌ Erreur lors de l\'enregistrement.';
        } else {
            $_SESSION['message_signup'] = '❌ Tous les champs doivent être remplis.';
        }

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }

    // SIGN IN
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new UserController();

    // SIGN IN
    if (isset($_POST['sign_in'])) {
        $identifier = trim($_POST['username']);
        $password   = trim($_POST['password']);

        $userObj = $controller->verifyCredentials($identifier, $password);
        if ($userObj) {
            $_SESSION['user'] = [
                'id'       => $userObj->getIdUser(),
                'username' => $userObj->getUsername(),
                'email'    => $userObj->getEmail(),
                'role'     => $userObj->getRole(),
                'numero'   => $userObj->getNumero(),
            ];
            $_SESSION['message_signin'] = '';
           header('Location: /2A27/profile');
            exit();
        } else {
            $_SESSION['message_signin'] = '❌ Identifiants invalides !';
            header('Location: ' . $_SERVER['PHP_SELF']); // Stay on the current page on failure
            exit();
        }
    }
}

}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentification</title>
    <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST'] ?>/2A27/pro/view/assets/css/l2.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        .alert { position: fixed; top: 10px; left: 50%; transform: translateX(-50%); padding: 15px; background-color: #f44336; color: #fff; border-radius: 5px; font-size: 16px; z-index: 1000; width: 80%; text-align: center; }
        .alert.success { background-color: #4CAF50; }
        .alert.error   { background-color: #f44336; }
        .error-message { color: red; font-size: 12px; }
    </style>
</head>
<body>

<?php if ($_SESSION['message_signup']): ?>
    <div class="alert <?= strpos($_SESSION['message_signup'], '✅') !== false ? 'success' : 'error' ?>">
        <?= $_SESSION['message_signup'] ?>
    </div>
    <?php $_SESSION['message_signup'] = ''; ?>
<?php endif; ?>

<?php if ($_SESSION['message_signin']): ?>
    <div class="alert <?= strpos($_SESSION['message_signin'], '✅') !== false ? 'success' : 'error' ?>">
        <?= $_SESSION['message_signin'] ?>
    </div>
    <?php $_SESSION['message_signin'] = ''; ?>
<?php endif; ?>

<div id="container" class="container">
    <div class="row">
        <!-- SIGN UP -->
        <div class="col align-items-center flex-col sign-up">
            <div class="form-wrapper align-items-center">
                <div class="form sign-up">
                    <form id="signupForm" method="POST" action="">
                        <div class="input-group">
                            <i class="bx bxs-user"></i>
                            <input type="text" name="username" id="signupUsername" placeholder="Nom d'utilisateur" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                            <span id="signupUsernameError" class="error-message"></span>
                        </div>
                        <div class="input-group">
                            <i class="bx bx-mail-send"></i>
                            <input type="email" name="email" id="signupEmail" placeholder="Email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                            <span id="signupEmailError" class="error-message"></span>
                        </div>
                        <div class="input-group">
                            <i class="bx bxs-lock-alt"></i>
                            <input type="text" name="password" id="signupPassword" placeholder="Mot de passe généré" value="<?= htmlspecialchars($generatedPassword) ?>">
                            <span id="signupPasswordError" class="error-message"></span>
                        </div>
                        <small style="color: green;">💡 Mot de passe généré : <?= htmlspecialchars($generatedPassword) ?></small>
                        <button type="submit" name="sign_up">S'inscrire</button>
                    </form>
                    <p>Vous avez déjà un compte ? <b onclick="toggle()">Se connecter ici</b></p>
                </div>
            </div>
        </div>

        <!-- SIGN IN -->
        <div class="col align-items-center flex-col sign-in">
            <div class="form-wrapper align-items-center">
                <div class="form sign-in">
                    <form id="signinForm" method="POST" action="">
                        <div class="input-group">
                            <i class="bx bxs-user"></i>
                            <input type="text" name="username" id="signinUsername" placeholder="Nom d'utilisateur">
                            <span id="signinUsernameError" class="error-message"></span>
                        </div>
                        <div class="input-group">
                            <i class="bx bxs-lock-alt"></i>
                            <input type="password" name="password" id="signinPassword" placeholder="Mot de passe">
                            <span id="signinPasswordError" class="error-message"></span>
                        </div>
                        <button type="submit" name="sign_in">Se connecter</button>
                    </form>
                    <p><a href="forgotpassword.php"><b>Mot de passe oublié ?</b></a></p>
                    <p>Vous n'avez pas de compte ? <b onclick="toggle()">S'inscrire ici</b></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="http://<?= $_SERVER['HTTP_HOST'] ?>/2A27/pro/view/assets/js/l2.js" defer></script>
<script>
    window.addEventListener('load', () => {
    const container = document.getElementById("container");
    container.classList.add('sign-in');  // Ensures the page loads with the sign-in form visible
    setTimeout(() => container.classList.add('start-animation'), 100);
});


    function toggle() {
    const container = document.getElementById('container');
    container.classList.toggle('sign-in'); // Add/Remove sign-in class
    container.classList.toggle('sign-up'); // Add/Remove sign-up class
}


    document.getElementById('signupForm').addEventListener('submit', function (event) {
        let isValid = true;
        document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

        if (!document.getElementById('signupUsername').value.trim()) {
            document.getElementById('signupUsernameError').textContent = "Le nom d'utilisateur est requis.";
            isValid = false;
        }
        if (!document.getElementById('signupEmail').value.trim()) {
            document.getElementById('signupEmailError').textContent = "L'email est requis.";
            isValid = false;
        }
        if (!document.getElementById('signupPassword').value.trim()) {
            document.getElementById('signupPasswordError').textContent = "Le mot de passe est requis.";
            isValid = false;
        }

        if (!isValid) event.preventDefault();
    });
</script>
</body>
</html>
