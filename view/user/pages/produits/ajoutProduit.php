<?php

require_once "../../../config.php";
require_once "../../../Controller/produitC.php";
require_once "../../../Model/Produit.php";

$prod = new Produit(0, $_POST['cat'], $_POST['nom'], $_POST['marque'], $_POST['mat'], $_POST['prix'], $_POST['stock'], $_POST['pays'], "");
produitC::ajouterProduit($prod);

header("Location: /lezm/admin/produit"); 
exit();