<?php
session_start();

// Déconnexion
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: /2A27/login');
    exit();
}

// Si non connecté, redirige vers login
if (!isset($_SESSION['user']['id'])) {
    header('Location: /2A27/login');
    exit();
}

// Connexion BDD
$host = 'localhost';
$db = 'db_html';
$userDB = 'root';
$passDB = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $userDB, $passDB);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$user = &$_SESSION['user'];

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $newUsername = trim($_POST['username']);
    $newEmail    = trim($_POST['email']);
    $newNumero   = trim($_POST['numero']);

    if ($newUsername === '' || $newEmail === '' || $newNumero === '') {
        $_SESSION['update_error'] = "Tous les champs sont obligatoires.";
    } else {
        try {
            $stmt = $pdo->prepare(
                "UPDATE utilisateurs 
                 SET username = ?, email = ?, numero = ? 
                 WHERE id_user = ?"
            );
            $stmt->execute([
                $newUsername,
                $newEmail,
                $newNumero,
                $user['id']
            ]);

            // Met à jour la session
            $user['username'] = $newUsername;
            $user['email']    = $newEmail;
            $user['numero']   = $newNumero;

            $_SESSION['update_success'] = true;
            header('Location: /2A27/profile'); // Redirige vers l'URL propre
            exit();
        } catch (PDOException $e) {
            $_SESSION['update_error'] = "Erreur lors de la mise à jour : " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Profil Utilisateur</title>
  <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST'] ?>/2A27/pro/view/assets/css/profile.css">
  <style>
  .home-btn {
    position: absolute;
    top: 20px;
    right: 20px;
    background-color: #4CAF50;
    color: white;
    padding: 10px 16px;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
  }

  .home-btn:hover {
    background-color: #45a049;
  }
</style>

</head>
<body>
    <a href="/2A27/home" class="home-btn">🏠 Accueil</a>
  <div class="profile-container">
    <h1>Bienvenue, <?= htmlspecialchars($user['username']) ?> !</h1>

    <div class="profile-info">
      <p><strong>Nom d'utilisateur :</strong> <?= htmlspecialchars($user['username']) ?></p>
      <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
      <p><strong>Numéro :</strong> <?= htmlspecialchars($user['numero']) ?></p>
      <p><strong>Rôle :</strong> <?= htmlspecialchars($user['role']) ?></p>
    </div>

    <a href="?logout=1" class="logout-btn">Se déconnecter</a>
    <hr>

    <h2>Modifier mes informations</h2>

    <?php if (isset($_SESSION['update_success'])): ?>
      <p style="color: green;">✅ Informations mises à jour avec succès !</p>
      <?php unset($_SESSION['update_success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['update_error'])): ?>
      <p style="color: red;">❌ <?= htmlspecialchars($_SESSION['update_error']) ?></p>
      <?php unset($_SESSION['update_error']); ?>
    <?php endif; ?>

    <!-- ✅ Fix here: use clean route in action -->
    <form method="POST" action="/2A27/profile">

      <label>Nom d'utilisateur :</label><br>
      <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required><br><br>

      <label>Email :</label><br>
      <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br><br>

      <label>Numéro :</label><br>
      <input type="text" name="numero" value="<?= htmlspecialchars($user['numero']) ?>" required><br><br>

      <input type="submit" name="update_profile" value="Mettre à jour">
    </form>
  </div>
</body>
</html>
