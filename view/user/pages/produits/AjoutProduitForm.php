<?php
$active_menu = 'produit';

include_once __DIR__ . '/../../../admin/layout.php';
include_once __DIR__ . '/../../../admin/partials/sidebar.php';

require_once __DIR__ . '/../../../../src/controll/routes/config.php';
require_once "produitC.php";
require_once "categorieProduitC.php";
require_once __DIR__ . '/../../../../fpdf/fpdf.php';

// Traitement du formulaire
$erreursFormulaire = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $cat = $_POST['cat'];
    $marque = trim($_POST['marque']);
    $materiel = trim($_POST['mat']);
    $prix = floatval($_POST['prix']);
    $stock = intval($_POST['stock']);
    $pays = trim($_POST['pays']);

    $erreurs = [];

    if (strlen($nom) < 2) $erreurs[] = "Le nom doit contenir au moins 2 caractères.";
    if (strlen($marque) < 2) $erreurs[] = "La marque doit contenir au moins 2 caractères.";
    if (strlen($materiel) < 2) $erreurs[] = "Le matériel doit contenir au moins 2 caractères.";
    if (strlen($pays) < 2) $erreurs[] = "Le pays doit contenir au moins 2 caractères.";
    if ($prix <= 0) $erreurs[] = "Le prix doit être un nombre positif.";
    if ($stock < 0) $erreurs[] = "Le stock doit être un entier positif ou nul.";
    if (empty($cat)) $erreurs[] = "Veuillez sélectionner une catégorie.";

    if (empty($erreurs)) {
        produitC::ajouterProduit($nom, $cat, $marque, $materiel, $prix, $stock, $pays);
        header("Location: ".$_SERVER['PHP_SELF']); // Recharge propre
        exit;
    } else {
        $erreursFormulaire = implode("<br>", $erreurs);
    }
}

// Téléchargement PDF
if (isset($_GET['telecharger_pdf'])) {
    ob_clean();
    $produits = produitC::getProduits();

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Liste des Produits', 0, 1, 'C');
    $pdf->Ln(5);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(20, 10, 'ID', 1);
    $pdf->Cell(30, 10, 'Nom', 1);
    $pdf->Cell(30, 10, 'Marque', 1);
    $pdf->Cell(30, 10, 'Materiel', 1);
    $pdf->Cell(20, 10, 'Prix', 1);
    $pdf->Cell(20, 10, 'Stock', 1);
    $pdf->Cell(30, 10, 'Pays', 1);
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    foreach ($produits as $p) {
        $pdf->Cell(20, 10, $p['id'], 1);
        $pdf->Cell(30, 10, $p['nom'], 1);
        $pdf->Cell(30, 10, $p['marque'], 1);
        $pdf->Cell(30, 10, $p['materiel'], 1);
        $pdf->Cell(20, 10, $p['prix'], 1);
        $pdf->Cell(20, 10, $p['stock'], 1);
        $pdf->Cell(30, 10, $p['pays'], 1);
        $pdf->Ln();
    }

    $pdf->Output('D', 'liste_produits.pdf');
    exit;
}
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
        <h1>Liste des Produits</h1>

        <?php if (!empty($erreursFormulaire)): ?>
            <div id="erreurs" class="mt-3 text-danger fw-semibold"><?= $erreursFormulaire ?></div>
        <?php else: ?>
            <div id="erreurs" class="mt-3 text-danger fw-semibold"></div>
        <?php endif; ?>

        <form id="produitForm" method="post" class="row g-3 mt-4" onsubmit="return validerFormulaire();" novalidate>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Nom</label>
                <input type="text" class="form-control" name="nom" id="nom" required minlength="2">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Catégorie</label>
                <select class="form-select" name="cat" id="cat" required>
                    <option selected hidden value="">Choisir une catégorie</option>
                    <?php
                    $categs = categorieProduitC::getCategories();
                    foreach ($categs as $cat) {
                        echo "<option value='{$cat['id']}'>{$cat['nom']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Marque</label>
                <input type="text" class="form-control" name="marque" id="marque" required minlength="2">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Matériel</label>
                <input type="text" class="form-control" name="mat" id="mat" required minlength="2">
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Prix</label>
                <input type="number" step="0.01" class="form-control" name="prix" id="prix" required min="0.01">
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Stock</label>
                <input type="number" class="form-control" name="stock" id="stock" required min="0">
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Pays</label>
                <input type="text" class="form-control" name="pays" id="pays" required minlength="2">
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
