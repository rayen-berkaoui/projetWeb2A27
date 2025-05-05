<?php
include('../../config.php');
$conn = config::getConnexion();

// Récupération des rôles pour le select
$roles = $conn->query("SELECT id, name FROM role")->fetchAll(PDO::FETCH_ASSOC);

// Traitement du formulaire d'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username'], $_POST['email'], $_POST['numero'], $_POST['mdp'], $_POST['role'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $numero = $_POST['numero'];
        $mdp = $_POST['mdp']; // Plus de hash ici
        $role = (int) $_POST['role'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "L'email est invalide.";
        } elseif (!preg_match('/^\d{8}$/', $numero)) {
            echo "Le numéro doit être composé de 8 chiffres.";
        } else {
            try {
                $stmt = $conn->prepare("INSERT INTO utilisateurs (username, email, numero, mdp, role) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$username, $email, $numero, $mdp, $role]);
                header("Location: ajout_succes.php?ajout=success");
                exit();
            } catch (PDOException $e) {
                echo "Erreur : " . $e->getMessage();
            }
        }
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter un utilisateur</title>
  <link rel="stylesheet" href="../../assets/css/styleb.css">
  <link rel="stylesheet" href="../../assets/css/styless.css">
  <style>
    body {
      background: #f4f6f8;
      font-family: 'Segoe UI', sans-serif;
    }

    .main-content {
      max-width: 600px;
      margin: 50px auto;
      background: white;
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    h1 {
      text-align: center;
      margin-bottom: 25px;
      color: #2c3e50;
    }

    .form-ajout label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
      color: #555;
    }

    .form-ajout input,
    .form-ajout select {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 6px;
      transition: border-color 0.3s ease;
    }

    .form-ajout input:focus,
    .form-ajout select:focus {
      border-color: #3498db;
      outline: none;
    }

    .form-ajout button {
      background-color: #3498db;
      color: white;
      border: none;
      padding: 12px;
      width: 100%;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
    }

    .form-ajout button:hover {
      background-color: #2980b9;
    }
  </style>
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const form = document.querySelector(".form-ajout");

      form.addEventListener("submit", function (e) {
        const username = document.getElementById("username").value.trim();
        const email = document.getElementById("email").value.trim();
        const numero = document.getElementById("numero").value.trim();
        const mdp = document.getElementById("mdp").value.trim();
        const role = document.getElementById("role").value;

        if (!username || !email || !numero || !mdp || !role) {
          alert("Veuillez remplir tous les champs.");
          e.preventDefault();
          return;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
          alert("Veuillez entrer une adresse email valide.");
          e.preventDefault();
          return;
        }

        if (!/^\d{8}$/.test(numero)) {
          alert("Le numéro doit contenir exactement 8 chiffres.");
          e.preventDefault();
          return;
        }

        if (mdp.length < 6) {
          alert("Le mot de passe doit contenir au moins 6 caractères.");
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
    <h1>Ajouter un Utilisateur</h1>
    <form action="" method="POST" class="form-ajout">
      <label>Nom d'utilisateur :</label>
      <input type="text" name="username" id="username" required />

      <label>Email :</label>
      <input type="email" name="email" id="email" required />

      <label>Numéro :</label>
      <input type="text" name="numero" id="numero" maxlength="8" required />

      <label>Mot de passe :</label>
      <input type="password" name="mdp" id="mdp" required />

      <label>Rôle :</label>
      <select name="role" id="role" required>
        <option value="">-- Sélectionner un rôle --</option>
        <?php foreach ($roles as $r): ?>
          <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['name']) ?></option>
        <?php endforeach; ?>
      </select>

      <button type="submit">Ajouter</button>
    </form>
  </div>
</body>
</html>
