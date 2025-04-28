<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: l2.php"); // Rediriger si non connecté
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Profil Utilisateur</title>
  <link rel="stylesheet" href="../assets/css/profile.css" />
</head>
<body>
  <div class="profile-container">
    <h1>Bienvenue, <?= htmlspecialchars($user['username']) ?> !</h1>

    <div class="profile-info">
      <p><strong>Nom d'utilisateur :</strong> <?= htmlspecialchars($user['username']) ?></p>
      <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
      <p><strong>Numéro :</strong> <?= htmlspecialchars($user['numero']) ?></p>
      <p><strong>Rôle :</strong> <?= htmlspecialchars($user['role']) ?></p>
    </div>

    <a href="logout.php" class="logout-btn">Se déconnecter</a>

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

    <form method="POST" action="update_profile.php">
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
