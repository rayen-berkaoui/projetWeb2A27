<?php
require_once "ProduitC.php";

// Sécurisation de l'entrée utilisateur
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$prod = ProduitC::getProduitsFromId($id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Détail Produit</title>
    <link href="http://<?= $_SERVER['HTTP_HOST'] ?>/lezm/view/assets/css/style.css" rel="stylesheet" />
    <script src="/lezm/view/assets/js/script.js" defer></script>
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

    <!-- Main Content -->
    <main class="main-content">
        <section>
            <h1 style="text-align: center;">Détails du Produit</h1>

            <?php if ($prod): ?>
                <div class="container">
                    <article class="bubble" style="text-align: center;">
                        <figure>
                            <img src="<?= htmlspecialchars($prod->getPhoto()) ?>" alt="<?= htmlspecialchars($prod->getNom()) ?>" width="400" height="400">
                            <figcaption><?= htmlspecialchars($prod->getNom()) ?></figcaption>
                        </figure>
                        <p><strong>Prix:</strong> <?= htmlspecialchars($prod->getPrix()) ?> Dt</p>
                        <p><strong>Marque:</strong> <?= htmlspecialchars($prod->getMarque()) ?></p>
                        <p><strong>Matériel:</strong> <?= htmlspecialchars($prod->getMateriel()) ?></p>
                        <p><strong>Pays d'origine:</strong> <?= htmlspecialchars($prod->getPays()) ?></p>
                    </article>
                </div>
            <?php else: ?>
                <p style="text-align: center; color: red;">Produit introuvable.</p>
            <?php endif; ?>
        </section>
    </main>

</body>
</html>
