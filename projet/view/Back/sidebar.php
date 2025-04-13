<?php
// Démarrer la session pour pouvoir vérifier si l'utilisateur est connecté (exemple)
session_start();


?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>sidebar</title>
  <link rel="stylesheet" href="../assets/css/styleb.css" />
  <script src="../assets/js/scriptb.js" defer></script>

</head>
<body>
  <div class="container">
    <aside class="sidebar">
      <div class="logo">
        <img src="../assets/images/logo1.png" alt="Logo" />
      </div>

      <nav>
        <a href="sidebar.php" class="nav-link">
          <i class="fas fa-th-large"></i> 📊 Dashboard
        </a>

        <div class="section-title">Les Gestions</div>
        <a href="utilisateurs.php" class="nav-link">
          <i class="fas fa-users"></i> 🔐 Login
        </a>
        <a href="commandes.php" class="nav-link">
          <i class="fas fa-box"></i> 📣 Marketing
        </a>
        <a href="forumns.php" class="nav-link">
          <i class="fas fa-user-plus"></i> 💬 Forums
        </a>
        <a href="avis.php" class="nav-link">
          <i class="fas fa-cogs"></i> ⭐ Avis
        </a>
        <a href="produit.php" class="nav-link">
          <i class="fas fa-cogs"></i> 📦 Produit
        </a>
        <a href="evenement.php" class="nav-link">
          <i class="fas fa-bell"></i> 📅 Événements
        </a>
        
        <div class="section-title">Authentification</div>
        <a href="deconnexion.php" class="nav-link">
          <i class="fas fa-sign-out-alt"></i> Déconnexion
        </a>
      </nav>
    </aside>

    

 
  
</body>
</html>
