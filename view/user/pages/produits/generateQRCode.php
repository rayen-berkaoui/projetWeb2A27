<?php
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
// Vérifiez que l'ID est bien passé dans l'URL
if (isset($_GET['id'])) {
    $productId = $_GET['id'];  // Récupérer l'ID du produit

    // Inclure la bibliothèque QR Code (Assurez-vous qu'elle est installée)
    require_once "vendor/autoload.php"; // Remplacez par le bon chemin vers votre autoloader si nécessaire



    // Créer le QR Code avec l'ID du produit
    $qrCode = new QrCode($productId);  // Utilisez ici ce que vous voulez afficher dans le QR Code
    $writer = new PngWriter();
    $result = $writer->write($qrCode);

    // Retourner l'image QR Code en tant que PNG
    header('Content-Type: ' . $result->getMimeType());
    echo $result->getString();  // Retourner l'image QR code sous forme de chaîne PNG
    exit;
} else {
    echo "Erreur : ID du produit non fourni.";
}
