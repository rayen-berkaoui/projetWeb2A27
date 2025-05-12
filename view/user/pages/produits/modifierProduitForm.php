<?php
require_once __DIR__ . '/../../../../src/controll/routes/config.php';
require_once "produitC.php";
require_once "Produit.php";
require_once "categorieProduitC.php";
include_once __DIR__ . '/../../../admin/layout.php';
include_once __DIR__ . '/../../../admin/partials/sidebar.php';

if (!isset($_GET['id'])) {
    die("ID produit non spécifié !");
}

$id = $_GET['id'];
$db = config::getConnexion();
$query = $db->prepare("SELECT * FROM produit WHERE id = :id");
$query->bindValue(":id", $id);
$query->execute();
$prod = $query->fetch(PDO::FETCH_ASSOC);

if (!$prod) {
    die("Produit introuvable !");
}

// Récupération des catégories
$categories = categorieProduitC::getCategories();
?>

<!doctype html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Modifier un produit</title>
  <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="../assets/css/styles.min.css" />
  <style>
    .content-area {
        margin-left: 270px;
        padding: 20px;
        flex: 1;
    }
    .form-container {
        background-color: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }
    .form-label {
        font-weight: 600;
    }
    .form-control {
        border-radius: 8px;
    }
    .btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 16px;
    }
  </style>
</head>

<body>
  <div class="content-area">
    <div class="container-fluid">
      <div class="form-container mt-4">
        <h4 class="mb-4">Modifier un produit</h4>
        <form action="modifierProduit.php" method="post" class="row g-3">
          <input type="hidden" name="id" value="<?= $prod['id'] ?>">

          <div class="col-md-6">
            <label class="form-label">Catégorie</label>
            <select name="cat" class="form-control" required>
              <option value="">-- Sélectionner une catégorie --</option>
              <?php foreach ($categories as $cat): ?>
                  <option value="<?= $cat['nom'] ?>" <?= ($prod['categorie'] == $cat['nom']) ? 'selected' : '' ?>>
                      <?= htmlspecialchars($cat['nom']) ?>
                  </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label">Nom</label>
            <input type="text" class="form-control" name="nom" value="<?= htmlspecialchars($prod['nom']) ?>" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Marque</label>
            <input type="text" class="form-control" name="marque" value="<?= htmlspecialchars($prod['marque']) ?>" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Matériel</label>
            <input type="text" class="form-control" name="mat" value="<?= htmlspecialchars($prod['materiel']) ?>" required>
          </div>

          <div class="col-md-4">
            <label class="form-label">Prix</label>
            <input type="number" step="0.01" class="form-control" name="prix" value="<?= $prod['prix'] ?>" required>
          </div>

          <div class="col-md-4">
            <label class="form-label">Stock</label>
            <input type="number" class="form-control" name="stock" value="<?= $prod['stock'] ?>" required>
          </div>

          <div class="col-md-4">
            <label class="form-label">Pays</label>
            <input type="text" class="form-control" name="pays" value="<?= htmlspecialchars($prod['pays']) ?>" required>
          </div>

          <div class="col-12">
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
