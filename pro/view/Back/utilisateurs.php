<?php
require_once('C:\xampp\htdocs\lezm\pro\config.php');
$conn = config::getConnexion();

// Suppression
if (isset($_POST['delete'])) {
    $id = $_POST['id_user'];
    $stmt = $conn->prepare("DELETE FROM utilisateurs WHERE id_user = :id");
    $stmt->execute(['id' => $id]);
    header("Location: http://127.0.0.1/lezm/admin/user");
    exit();
}

// Récupération des utilisateurs avec nom du rôle via jointure
$query = $conn->query("SELECT u.*, r.name AS nom_role FROM utilisateurs u LEFT JOIN role r ON u.role = r.id");

// Vérifie si la requête a bien retourné des résultats
if ($query) {
    $utilisateurs = $query->fetchAll(PDO::FETCH_ASSOC);
} else {
    $utilisateurs = [];  // Si la requête échoue, on assigne un tableau vide
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Liste des Utilisateurs</title>
  <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST'] ?>/lezm/pro/view/assets/css/styleb.css">
  <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST'] ?>/lezm/pro/view/assets/css/stylesu.css">
  <script src="../assets/js/scriptu.js" defer></script>
  <style>
    /* Ajouter un style pour la surbrillance */
    .highlight {
      background-color: yellow;
    }
  </style>
</head>
<body>
  <?php include('sidebar.php'); ?>

  <div class="main-content">
    <h1 class="title">Liste des Utilisateurs</h1>

    <div class="user-actions">
      <div class="left-buttons">
        <a href="user/formulair"><button class="btn-ajouter">➕ Ajouter un Utilisateur</button></a>
        <a href="user/export_pdf" target="_blank"><button class="btn-ajouter btn-export">📄 Exporter en PDF</button></a>
      </div>
      <!-- Champ de recherche avec un bouton -->
      <div class="search-container">
        <input type="text" id="search" placeholder="Rechercher par nom d'utilisateur..." onkeyup="searchUsers()">
        <button onclick="searchUsers()">🔍 Rechercher</button>
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
        <tbody id="userTableBody">
          <?php if (empty($utilisateurs)): ?>
            <tr>
              <td colspan="7">Aucun utilisateur trouvé</td>
            </tr>
          <?php else: ?>
            <?php foreach ($utilisateurs as $row): ?>
              <tr>
                <td><?= $row['id_user']; ?></td>
                <td class="username"><?= htmlspecialchars($row['username']); ?></td>
                <td><?= htmlspecialchars($row['mdp']); ?></td>
                <td><?= $row['nom_role'] ?? $row['role']; ?></td>
                <td><?= htmlspecialchars($row['email']); ?></td>
                <td><?= htmlspecialchars($row['numero']); ?></td>
                <td>
                  <div class="right-buttons">
                    <!-- Separate Delete Form -->
                    <form action="" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                      <input type="hidden" name="id_user" value="<?= $row['id_user']; ?>" />
                      <button type="submit" name="delete" class="btn-supprimer">🗑️ Supprimer</button>
                    </form>
                    <!-- Separate Link for Modifier -->
                    <a href="/lezm/admin/user/modifier.php?id_user=<?= $row['id_user']; ?>">
                      <button class="btn-modifier">✏️ Modifier</button>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    function searchUsers() {
      // Récupère le terme de recherche
      const searchTerm = document.getElementById('search').value.toLowerCase();
      
      // Récupère toutes les lignes du tableau
      const rows = document.querySelectorAll('#userTableBody tr');

      // Parcours toutes les lignes
      rows.forEach(row => {
        let isMatch = false;

        // Vérifie chaque cellule de la ligne pour voir si le terme de recherche est présent
        const cells = row.querySelectorAll('td');
        cells.forEach(cell => {
          if (cell.textContent.toLowerCase().includes(searchTerm)) {
            isMatch = true;
          }
        });

        // Si une correspondance est trouvée, met la ligne en surbrillance
        if (isMatch) {
          row.classList.add('highlight');
        } else {
          row.classList.remove('highlight');
        }
      });
    }
  </script>
</body>
</html>
