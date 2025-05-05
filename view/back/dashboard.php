<?php
session_start();

// Inclure les stats des commentaires
$filePath = __DIR__ . '/../../controller/commentStatsController.php';

if (file_exists($filePath)) {
    require_once($filePath);
} else {
    echo "Fichier commentStatsController.php introuvable.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>GreenMind Back‑Office</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styleb.css">
    <link rel="stylesheet" href="../assets/css/avis.css">
    <script src="../assets/js/scriptb.js" defer></script>
</head>
<body>
    <div class="container">
        <?php include 'sidebar.php'; ?>
        <main id="main-content" class="content">
            <h2>Bienvenue dans votre dashboard 🌿</h2>
           
            <?php include 'avis.php'; ?>

            <!-- C'EST ICI que tu ajoutes ton tableau, PAS après </body> -->
            <h2>Commentaires des Clients avec Statistiques</h2>

            <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>Commentaire</th>
                    <th>Likes</th>
                    <th>Dislikes</th>
                    <th>% Likes</th>
                    <th>% Dislikes</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($commentStats as $row): 
                $total = $row['likes'] + $row['dislikes'];
                if ($total > 0) {
                    $likePercentage = round(($row['likes'] / $total) * 100, 2);
                    $dislikePercentage = round(($row['dislikes'] / $total) * 100, 2);
                } else {
                    $likePercentage = $dislikePercentage = 0;
                }
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['contenu']) ?></td>
                    <td><?= $row['likes'] ?></td>
                    <td><?= $row['dislikes'] ?></td>
                    <td><?= $likePercentage ?>%</td>
                    <td><?= $dislikePercentage ?>%</td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            </table>
            <!-- FIN DU TABLEAU -->
        </main>
    </div>
</body>
</html>
