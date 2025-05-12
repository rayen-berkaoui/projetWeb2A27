<?php
$active_menu = 'produit';

include_once __DIR__ . '/../../../admin/layout.php';
include_once __DIR__ . '/../../../admin/partials/sidebar.php';

require_once __DIR__ . '/../../../../src/controll/routes/config.php';
require_once "produitC.php";
require_once "categorieProduitC.php";
$categories = categorieProduitC::getCategories();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion Produits</title>
    <style>
        .content-area {
            margin-left: 270px;
            padding: 20px;
            flex: 1;
        }
    </style>
    <script>
    function validerFormulaire() {
        const nom = document.getElementById("nom").value.trim();
        const marque = document.getElementById("marque").value.trim();
        const mat = document.getElementById("mat").value.trim();
        const pays = document.getElementById("pays").value.trim();
        const prix = parseFloat(document.getElementById("prix").value);
        const stock = parseInt(document.getElementById("stock").value);
        const cat = document.getElementById("cat").value;

        let erreurs = [];

        if (nom.length < 2) erreurs.push("Le nom doit contenir au moins 2 caractères.");
        if (marque.length < 2) erreurs.push("La marque doit contenir au moins 2 caractères.");
        if (mat.length < 2) erreurs.push("Le matériel doit contenir au moins 2 caractères.");
        if (pays.length < 2) erreurs.push("Le pays doit contenir au moins 2 caractères.");
        if (isNaN(prix) || prix <= 0) erreurs.push("Le prix doit être un nombre positif.");
        if (isNaN(stock) || stock < 0) erreurs.push("Le stock doit être un entier positif ou nul.");
        if (!cat) erreurs.push("Veuillez sélectionner une catégorie.");

        const erreurDiv = document.getElementById("erreurs");
        if (erreurs.length > 0) {
            erreurDiv.innerHTML = erreurs.join("<br>");
            return false;
        }

        erreurDiv.innerHTML = "";
        return true;
    }
    </script>
</head>
<body>
<div class="content-area">
    <div class="container mt-4">
        <h1>Liste des categories</h1>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom de la catégorie</th>
               <th>Description</th>
                <th>photo</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categories as $cat): ?>
            <tr>
                <td><?= htmlspecialchars($cat['id']) ?></td>
                <td><?= htmlspecialchars($cat['nom']) ?></td>
                 <td><?= htmlspecialchars($cat['description']) ?></td>
                  <td><?= htmlspecialchars($cat['photo']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

      </div> 
</div>
</body>
</html>
