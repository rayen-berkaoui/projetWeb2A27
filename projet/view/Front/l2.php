<?php
session_start();

require_once('../../model/user.php');
require_once('../../controler/userc.php');

$message_signup = "";
$message_signin = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new UserController();

    // SIGN UP
    if (isset($_POST['sign_up'])) {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $mdp = trim($_POST['password']);
        $role = "organisateur";
        $numero = "00000000";

        if (!empty($username) && !empty($email) && !empty($mdp)) {
            $user = new User($username, email: $email , mdp: $mdp , role: $role , numero: $numero );
            $result = $controller->addUser($user);
            $message_signup = $result ? "✅ Utilisateur enregistré avec succès!" : "❌ Erreur lors de l'enregistrement. Veuillez réessayer.";
        } else {
            $message_signup = "❌ Tous les champs doivent être remplis.";
        }
    }

    // SIGN IN
    if (isset($_POST['sign_in'])) {
        $username = trim($_POST['username']);
        $mdp = trim($_POST['password']); // Corrected variable name

        $user = $controller->verifyCredentials($username, $mdp); 

        if ($user) {
            $_SESSION['user'] = [
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'role' => $user->getRole(),
                'numero' => $user->getNumero()
            ];
            header("Location: profile.php");
            exit();
        } else {
            $message_signin = "❌ Identifiants invalides!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Authentification</title>
  <link rel="stylesheet" href="../assets/css/l2.css" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
</head>
<body>
  <div id="container" class="container">
    <div class="row">
      <!-- SIGN UP -->
      <div class="col align-items-center flex-col sign-up">
        <div class="form-wrapper align-items-center">
          <div class="form sign-up">
            <form method="POST" action="">
              <div class="input-group">
                <i class="bx bxs-user"></i>
                <input type="text" name="username" placeholder="Nom d'utilisateur" required />
              </div>
              <div class="input-group">
                <i class="bx bx-mail-send"></i>
                <input type="email" name="email" placeholder="Email" required />
              </div>
              <div class="input-group">
                <i class="bx bxs-lock-alt"></i>
                <input type="password" name="password" placeholder="Mot de passe" required />
              </div>
              <button type="submit" name="sign_up">S'inscrire</button>
            </form>
            <div id="message-signup" class="message"></div>
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
            <form method="POST" action="">
              <div class="input-group">
                <i class="bx bxs-user"></i>
                <input type="text" name="username" placeholder="Nom d'utilisateur" required />
              </div>
              <div class="input-group">
                <i class="bx bxs-lock-alt"></i>
                <input type="password" name="password" placeholder="Mot de passe" required />
              </div>
              <button type="submit" name="sign_in">Se connecter</button>
            </form>
            <div id="message-signin" class="message"></div>
            <p><a href="forgotpassword.php" style="color: black; text-decoration: none;"><b>Mot de passe oublié ?</b></a></p>
            <p>
              <span>Vous n'avez pas de compte ?</span>
              <b onclick="toggle()" class="pointer">S'inscrire ici</b>
            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="row content-row">
      <div class="col align-items-center flex-col">
        <div class="text sign-in">
          <h2>Bienvenue</h2>
        </div>
        <div class="img sign-in"></div>
      </div>

      <div class="col align-items-center flex-col">
        <div class="img sign-up"></div>
        <div class="text sign-up">
          <h2>Rejoignez-nous</h2>
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

      const messageSignup = "<?php echo $message_signup; ?>";
      const messageSignin = "<?php echo $message_signin; ?>";

      if (messageSignup) {
        document.getElementById("message-signup").textContent = messageSignup;
        document.getElementById("message-signup").style.color = 'red';
      }

      if (messageSignin) {
        document.getElementById("message-signin").textContent = messageSignin;
        document.getElementById("message-signin").style.color = 'red';
      }
    });

    function toggle() {
      const container = document.getElementById('container');
      container.classList.toggle('sign-in');
      container.classList.toggle('sign-up');
    }
  </script>
</body>
</html>