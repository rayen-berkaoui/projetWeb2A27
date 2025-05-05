<?php
session_start();

require_once('../../model/user.php');
require_once('../../controler/userc.php');

// Initialiser les messages
if (!isset($_SESSION['message_signup'])) $_SESSION['message_signup'] = "";
if (!isset($_SESSION['message_signin'])) $_SESSION['message_signin'] = "";

// Fonction pour générer un mot de passe sécurisé
function generatePassword($length = 12) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+-=[]{}|;:,.<>?';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $password;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new UserController();

    // SIGN UP
    if (isset($_POST['sign_up'])) {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $role = "organisateur";
        $status = "actif";
        $numero = "00000000";

        if (!empty($username) && !empty($email)) {
            // Générer un mot de passe sécurisé
            $mdp = generatePassword(12);

            // Stocker le mot de passe généré pour l'afficher dans le champ
            $_SESSION['generated_password'] = $mdp;

            $user = new User($username, $role, $status, $email, $mdp, $numero);
            $result = $controller->addUser($user);
            $_SESSION['message_signup'] = $result ? "✅ Utilisateur enregistré avec succès!" : "❌ Erreur lors de l'enregistrement.";
        } else {
            $_SESSION['message_signup'] = "❌ Tous les champs doivent être remplis.";
        }
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }

    // SIGN IN
    if (isset($_POST['sign_in'])) {
        $username = trim($_POST['username']);
        $mdp = trim($_POST['password']);

        $user = $controller->getUserByUsername($username);

        if ($user && $user->getMdp() === $mdp) {
            $_SESSION['user'] = [
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'role' => $user->getRole(),
                'numero' => $user->getNumero()
            ];
            $_SESSION['message_signin'] = "";
            header("Location: profile.php");
            exit();
        } else {
            $_SESSION['message_signin'] = "❌ Identifiants invalides!";
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
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
  <link rel="stylesheet" href="../assets/css/l2.css">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <style>
    .alert {
      position: fixed;
      top: 10px;
      left: 50%;
      transform: translateX(-50%);
      padding: 15px;
      background-color: #f44336;
      color: white;
      border-radius: 5px;
      font-size: 16px;
      z-index: 1000;
      display: none;
      width: 80%;
      text-align: center;
    }
    .alert.success { background-color: #4CAF50; }
    .alert.error { background-color: #f44336; }
    .error-message { color: red; font-size: 12px; }
  </style>
</head>
<body>

<div id="alert" class="alert"></div>

<div id="container" class="container">
  <div class="row">
    <!-- SIGN UP -->
    <div class="col align-items-center flex-col sign-up">
      <div class="form-wrapper align-items-center">
        <div class="form sign-up">
          <form id="signupForm" method="POST" action="">
            <div class="input-group">
              <i class="bx bxs-user"></i>
              <input type="text" name="username" id="signupUsername" placeholder="Nom d'utilisateur" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
              <span id="signupUsernameError" class="error-message"></span>
            </div>
            <div class="input-group">
              <i class="bx bx-mail-send"></i>
              <input type="email" name="email" id="signupEmail" placeholder="Email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
              <span id="signupEmailError" class="error-message"></span>
            </div>
            <div class="input-group">
              <i class="bx bxs-lock-alt"></i>
              <input type="password" name="password" id="signupPassword" placeholder="Mot de passe" value="<?php echo isset($_SESSION['generated_password']) ? htmlspecialchars($_SESSION['generated_password']) : ''; ?>">
              <span id="signupPasswordError" class="error-message"></span>
            </div>
            <?php if (isset($_SESSION['generated_password'])): ?>
              <small style="color: green;">💡 Mot de passe généré : <?php echo htmlspecialchars($_SESSION['generated_password']); ?></small>
            <?php endif; ?>
            <button type="submit" name="sign_up">S'inscrire</button>
          </form>
          <p>
            <span>Vous avez déjà un compte ?</span>
            <b onclick="toggle()" class="pointer">Se connecter ici</b>
          </p>
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
              <input type="text" name="username" id="signinUsername" placeholder="Nom d'utilisateur" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
              <span id="signinUsernameError" class="error-message"></span>
            </div>
            <div class="input-group">
              <i class="bx bxs-lock-alt"></i>
              <input type="password" name="password" id="signinPassword" placeholder="Mot de passe">
              <span id="signinPasswordError" class="error-message"></span>
            </div>
            <button type="submit" name="sign_in">Se connecter</button>
          </form>
          <p><a href="forgotpassword.php" style="color: black; text-decoration: none;"><b>Mot de passe oublié ?</b></a></p>
          <p>
            <span>Vous n'avez pas de compte ?</span>
            <b onclick="toggle()" class="pointer">S'inscrire ici</b>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="../assets/js/l2.js"></script>

<script>
  window.addEventListener('load', () => {
    const container = document.getElementById("container");
    container.classList.add('sign-in');
    setTimeout(() => container.classList.add('start-animation'), 100);
  });

  function showAlert(message, type) {
    const alertBox = document.getElementById('alert');
    alertBox.textContent = message;
    alertBox.classList.add(type);
    alertBox.style.display = 'block';
    setTimeout(() => {
      alertBox.style.display = 'none';
    }, 5000);
  }

  function toggle() {
    const container = document.getElementById('container');
    container.classList.toggle('sign-in');
    container.classList.toggle('sign-up');
  }

  document.getElementById('signupForm').addEventListener('submit', function (event) {
    let isValid = true;
    clearErrors();

    // Validation des champs
    const username = document.getElementById('signupUsername').value.trim();
    const email = document.getElementById('signupEmail').value.trim();
    const password = document.getElementById('signupPassword').value.trim();

    if (!username) {
      showError('signupUsername', "Le nom d'utilisateur est requis.");
      isValid = false;
    }
    if (!email) {
      showError('signupEmail', "L'email est requis.");
      isValid = false;
    }
    if (!password) {
      showError('signupPassword', "Le mot de passe est requis.");
      isValid = false;
    }

    if (!isValid) {
      event.preventDefault();
    }
  });

  function clearErrors() {
    document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
  }

  function showError(field, message) {
    document.getElementById(field + 'Error').textContent = message;
  }
</script>
</body>
</html>
