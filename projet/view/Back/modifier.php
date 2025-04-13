<?php
require_once('../../config.php');
$conn = config::getConnexion();

// Si un utilisateur est modifié, récupérer ses informations
if (isset($_GET['id_user'])) {
    $id_user = $_GET['id_user'];
    $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE id_user = :id");
    $stmt->execute(['id' => $id_user]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $role = $_POST['role'];
    $email = $_POST['email'];
    $numero = $_POST['numero'];

    if (isset($_POST['id_user'])) {
        $stmt = $conn->prepare("UPDATE utilisateurs SET username = :username, role = :role, email = :email, numero = :numero WHERE id_user = :id");
        $stmt->execute(['username' => $username, 'role' => $role, 'email' => $email, 'numero' => $numero, 'id' => $_POST['id_user']]);
    } else {
        $stmt = $conn->prepare("INSERT INTO utilisateurs (username, role, status, email, numero) VALUES (:username, :role, :email, :numero)");
        $stmt->execute(['username' => $username, 'role' => $role, 'email' => $email, 'numero' => $numero]);
    }

    header("Location: utilisateurs.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Formulaire d'ajout/édition d'utilisateur</title>
  <link rel="stylesheet" href="../assets/css/styleb.css" />
  <link rel="stylesheet" href="../assets/css/stylem.css" />
  <script src="../assets/js/scriptm.js"></script>
</head>

<body>
  <!-- Barre latérale -->
  <?php include('sidebar.php'); ?>

  <!-- Contenu principal -->
  <div class="main-content">
    <div class="form-container">
      <h2><?php echo isset($user) ? "Modifier l'utilisateur" : "Ajouter un utilisateur"; ?></h2>

      <form method="POST" action="">
        <?php if (isset($user)) { ?>
          <input type="hidden" name="id_user" value="<?php echo $user['id_user']; ?>" />
        <?php } ?>

        <div class="form-group">
          <label for="username">Nom d'utilisateur</label>
          <input type="text" id="username" name="username" value="<?php echo isset($user) ? $user['username'] : ''; ?>" required />
        </div>

        <div class="form-group">
          <label for="role">Rôle</label>
          <select id="role" name="role" required>
            <option value="utilisateur" <?php echo (isset($user) && $user['role'] == 'utilisateur') ? 'selected' : ''; ?>>Utilisateur</option>
            <option value="organisateur" <?php echo (isset($user) && $user['role'] == 'organisateur') ? 'selected' : ''; ?>>Organisateur</option>
          </select>
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" value="<?php echo isset($user) ? $user['email'] : ''; ?>" required />
        </div>

        <div class="form-group">
          <label for="numero">Numéro</label>
          <input type="text" id="numero" name="numero" value="<?php echo isset($user) ? $user['numero'] : ''; ?>" required />
        </div>

        <button type="submit" class="submit-btn"><?php echo isset($user) ? "Modifier l'utilisateur" : "Ajouter l'utilisateur"; ?></button>
      </form>
    </div>
  </div>
</body>
</html>
