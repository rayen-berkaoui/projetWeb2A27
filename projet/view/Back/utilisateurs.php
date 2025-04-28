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

// Récupération des utilisateurs avec nom du rôle via jointure
$query = $conn->query("
    SELECT u.*, r.name AS nom_role
    FROM utilisateurs u
    LEFT JOIN role r ON u.role = r.id
");
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
  <?php include('sidebar.php'); ?>

  <div class="main-content">
    <h1 class="title">Liste des Utilisateurs</h1>

    <div class="user-actions">
  <div class="left-buttons">
    <a href="formulaireaj.php"><button class="btn-ajouter">➕ Ajouter un Utilisateur</button></a>
    <!-- Export Button -->
    <a href="export_pdf.php" target="_blank"><button class="btn-ajouter btn-export">📄 Exporter en PDF</button></a>
  </div>
</div>


    <div class="table-container">
      <table class="user-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nom d'utilisateur</th>
            <th>Mot de passe</th>
            <th>Rôle</th>
            <th>Email</th>
            <th>Numéro</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($utilisateurs as $row): ?>
            <tr>
              <td><?= $row['id_user']; ?></td>
              <td><?= htmlspecialchars($row['username']); ?></td>
              <td><?= htmlspecialchars($row['mdp']); ?></td>
              <td><?= $row['nom_role'] ?? $row['role']; ?></td>
              <td><?= htmlspecialchars($row['email']); ?></td>
              <td><?= htmlspecialchars($row['numero']); ?></td>
              <td>
                <div class="right-buttons">
                  <form action="" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                    <input type="hidden" name="id_user" value="<?= $row['id_user']; ?>" />
                    <button type="submit" name="delete" class="btn-supprimer">🗑️ Supprimer</button>
                  </form>
                  <a href="modifier.php?id_user=<?= $row['id_user']; ?>">
                    <button class="btn-modifier">✏️ Modifier</button>
                  </a>
                </div>
              </td>
            </tr>
            
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
