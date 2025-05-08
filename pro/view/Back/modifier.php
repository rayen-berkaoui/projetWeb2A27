<?php
require_once('../../config.php');
$conn = config::getConnexion();

// Récupérer tous les rôles
try {
    $stmtRoles = $conn->query("SELECT id, name FROM role");
    $roles = $stmtRoles->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur lors du chargement des rôles : " . $e->getMessage();
    $roles = [];
}

// Si modification : récupérer l'utilisateur
if (isset($_GET['id_user'])) {
    $id_user = $_GET['id_user'];
    $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE id_user = :id");
    $stmt->execute(['id' => $id_user]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Traitement formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $role = (int)$_POST['role'];
    $email = $_POST['email'];
    $numero = $_POST['numero'];

    if (isset($_POST['id_user'])) {
        // UPDATE
        $stmt = $conn->prepare("UPDATE utilisateurs SET username = :username, role = :role, email = :email, numero = :numero WHERE id_user = :id");
        $stmt->execute([
            'username' => $username,
            'role' => $role,
            'email' => $email,
            'numero' => $numero,
            'id' => $_POST['id_user']
        ]);
    } else {
        // INSERT
        $stmt = $conn->prepare("INSERT INTO utilisateurs (username, role, email, numero) VALUES (:username, :role, :email, :numero)");
        $stmt->execute([
            'username' => $username,
            'role' => $role,
            'email' => $email,
            'numero' => $numero
        ]);
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
  <script>
  document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");
    
    form.addEventListener("submit", function (e) {
      const username = document.getElementById("username").value.trim();
      const email = document.getElementById("email").value.trim();
      const numero = document.getElementById("numero").value.trim();
      const role = document.getElementById("role").value;

      if (!username || !email || !numero || !role) {
        alert("Veuillez remplir tous les champs.");
        e.preventDefault();
        return;
      }

      const emailRegex = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
      if (!emailRegex.test(email)) {
        alert("Adresse email invalide.");
        e.preventDefault();
        return;
      }

      if (!/^\d{8}$/.test(numero)) {
        alert("Le numéro doit contenir exactement 8 chiffres.");
        e.preventDefault();
        return;
      }
    });
  });
</script>

</head>

<body>
  <?php include('sidebar.php'); ?>

  <div class="main-content">
    <div class="form-container">
      <h2><?php echo isset($user) ? "Modifier l'utilisateur" : "Ajouter un utilisateur"; ?></h2>

      <form method="POST" action="">
        <?php if (isset($user)) { ?>
          <input type="hidden" name="id_user" value="<?php echo $user['id_user']; ?>" />
        <?php } ?>

        <div class="form-group">
          <label for="username">Nom d'utilisateur</label>
          <input type="text" id="username" name="username" value="<?php echo isset($user) ? htmlspecialchars($user['username']) : ''; ?>" required />
        </div>

        <div class="form-group">
          <label for="role">Rôle</label>
          <select id="role" name="role" required>
            <option value="">-- Sélectionner un rôle --</option>
            <?php foreach ($roles as $r): ?>
              <option value="<?= $r['id'] ?>" <?= (isset($user) && $user['role'] == $r['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($r['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" value="<?php echo isset($user) ? htmlspecialchars($user['email']) : ''; ?>" required />
        </div>

        <div class="form-group">
          <label for="numero">Numéro</label>
          <input type="text" id="numero" name="numero" value="<?php echo isset($user) ? htmlspecialchars($user['numero']) : ''; ?>" required />
        </div>

        <button type="submit" class="submit-btn">
          <?php echo isset($user) ? "Modifier l'utilisateur" : "Ajouter l'utilisateur"; ?>
        </button>
      </form>
    </div>
  </div>
</body>
</html>
