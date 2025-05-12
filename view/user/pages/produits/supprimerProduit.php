<?php


require_once __DIR__ . '/../../../../src/controll/routes/config.php';
require_once "produitC.php";
//require_once "ProduitC.php";
require_once "Produit.php";


produitC::supprimerProduit($_POST['id']);

header("Location: /lezm/admin/produit"); 
exit();

