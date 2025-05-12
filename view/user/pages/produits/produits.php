<?php
require_once "ProduitC.php";
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
// Vérifier si une requête AJAX pour générer le QR Code est envoyée
if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == 'generateQRCode') {
    // Générer le QR Code ici
    $productId = $_GET['id'];
    
    // Inclure la bibliothèque Endroid QR Code
    require_once __DIR__ . '/vendor/autoload.php'; // Assurez-vous que le chemin est correct

  

    // Créer un QR Code avec l'ID du produit (ou d'autres informations)
    $qrCode = new QrCode('http://votre-site/produit/' . $productId);  // Remplacez par l'URL correcte
    $writer = new PngWriter();
    $result = $writer->write($qrCode);

    // Retourner l'image du QR Code en base64
    echo base64_encode($result->getString());
    exit;
}
$prods = ProduitC::getProduits();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website</title>
    <link href="http://<?= $_SERVER['HTTP_HOST'] ?>/lezm/view/assets/css/style.css" rel="stylesheet">
    <script src="C:\xampp\htdocs\lezm\view\assets\js\script.js" defer></script>
</head>
<body>

    <!-- Navbar -->
    <nav>
        <ul>
            <li><a href="/lezm/home">Welcome</a></li>
            <li><a href="/lezm/about">About</a></li>
            <li><a href="/lezm/articles">Articles</a></li>
            <li><a href="/lezm/events">Events</a></li>
            <li><a href="/lezm/forum">Forum</a></li>
            <li><a href="/lezm/marketing">Marketing</a></li>
            <li><a href="/lezm/produits">Produits</a></li>
            <li><a href="/lezm/avis">Avis</a></li>
            <li><a href="/lezm/login">Login</a></li>
            <li><a href="/lezm/admin">Admin</a></li>            
        </ul>
    </nav>

    <!-- Main content -->
    <style>
        .container {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
        }

        .bubble {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            width: 250px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .bubble:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .bubble img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }

        .bubble .nom {
            margin-top: 10px;
            font-size: 1.1em;
            font-weight: bold;
        }

        .bubble button {
            margin-top: 10px;
            padding: 8px 16px;
            border: none;
            background-color: #2a9d8f;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .bubble button:hover {
            background-color: #21867a;
        }

        h1 {
            text-align: center;
            margin-top: 30px;
            font-size: 2em;
        }
    </style>


<main>
<h1>Nos Produits</h1>
    <div class="container">
        <?php foreach ($prods as $prod): ?>
            <form action="/lezm/detailProduit" method="post">
                <article class="bubble">
                    <img src="<?= htmlspecialchars($prod['cimg']) ?>" alt="<?= htmlspecialchars($prod['nom']) ?>" />
                    <p class="nom"><?= htmlspecialchars($prod['nom']) ?></p>
                    <p><?= htmlspecialchars($prod['prix']) ?> Dt</p>
                    
                    <!-- Bouton Détails -->
                    <button type="submit" name="id" value="<?= htmlspecialchars($prod['id']) ?>">Détails</button>

                    <!-- Bouton Générer QR Code -->
                    <button type="button" onclick="generateQRCode(<?= $prod['id'] ?>)">Générer QR Code</button>

                    <!-- Zone où le QR Code sera affiché -->
                    <div id="qrCode_<?= $prod['id'] ?>" class="qr-code-container" style="display: none;">
                        <img src="" id="qrImage_<?= $prod['id'] ?>" alt="QR Code" />
                    </div>
                </article>
            </form>
        <?php endforeach; ?>
    </div>

    <script>
function generateQRCode(productId) {
    console.log('Générer QR Code pour le produit ID:', productId);
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '/lezm/produits?id=' + productId + '&action=generateQRCode', true);
    xhr.onload = function() {
        if (xhr.status == 200) {
            console.log('QR Code généré avec succès');
            qrImage.src = 'data:image/png;base64,' + xhr.responseText;
            qrCodeContainer.style.display = 'block'; // Afficher l'image QR Code
        } else {
            console.log('Erreur lors de la génération du QR Code:', xhr.status, xhr.statusText);
        }
    };
    xhr.onerror = function() {
        console.log('Erreur réseau');
    };
    xhr.send();
}
</script>
    </main>


    </div>

</body>
</html>
