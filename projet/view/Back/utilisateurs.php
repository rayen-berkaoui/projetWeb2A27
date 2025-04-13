<?php
require_once('../../config.php');
$conn = config::getConnexion();

// Suppression
if (isset($_POST['delete'])) {
    $id = $_POST['id_user'];
    $stmt = $conn->prepare("DELETE FROM utilisateurs WHERE id_user = :id");
    $stmt->execute(['id' => $id]);
    header("Location: utilisateurs.php");
    exit();
}

// Récupération des utilisateurs
$query = $conn->query("SELECT * FROM utilisateurs");
$utilisateurs = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Liste des Utilisateurs</title>

  <link rel="stylesheet" href="../assets/css/styleb.css" />
  <link rel="stylesheet" href="../assets/css/stylesu.css" />
  <script src="../assets/js/scriptu.js" defer></script>
</head>

<body>
  <!-- Barre latérale -->
  <?php include('sidebar.php'); ?>

  <div class="main-content">
    <h1 class="title">Liste des Utilisateurs</h1>

    <!-- Bouton Ajouter un Utilisateur -->
    <div class="user-actions">
      <div class="left-buttons">
        <a href="formulaireaj.php"><button class="btn-ajouter">➕ Ajouter un Utilisateur</button></a>
      </div>
    </div>

    <!-- Tableau des utilisateurs -->
    <div class="table-container">
      <table class="user-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nom d'utilisateur</th>
            <th>Rôle</th>
            <th>Email</th>
            <th>Numéro</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($utilisateurs as $row) { ?>
            <tr>
              <td><?php echo $row['id_user']; ?></td>
              <td><?php echo $row['username']; ?></td>
              <td><?php echo $row['role']; ?></td>
              <td><?php echo $row['email']; ?></td>
              <td><?php echo $row['numero']; ?></td>
              <td>
                <div class="right-buttons">
                  <!-- Bouton Supprimer -->
                  <form action="" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                    <input type="hidden" name="id_user" value="<?php echo $row['id_user']; ?>" />
                    <button type="submit" name="delete" class="btn-supprimer">🗑️ Supprimer</button>
                  </form>

                  <!-- Bouton Modifier -->
                  <a href="modifier.php?id_user=<?php echo $row['id_user']; ?>">
                    <button class="btn-modifier">✏️ Modifier</button>
                  </a>
                </div>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
