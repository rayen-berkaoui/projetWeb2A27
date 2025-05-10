<?php
require_once __DIR__ . '/../../../src/controll/process/db.php';

// Récupérer tous les posts
try {
    $posts = $db->query('SELECT id, titre, description FROM post')->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Erreur lors de la récupération des posts : ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - Forums Environnement</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">
</head>
<body>
    <h1>Bienvenue sur notre site de Forums 🌱</h1>

    <section>
        <h2>Derniers Posts :</h2>

        <?php if (count($posts) > 0): ?>
            <ul>
                <?php foreach ($posts as $post): ?>
                    <li>
                        <h3><?php echo htmlspecialchars($post['titre']); ?></h3>
                        <p><?php echo nl2br(htmlspecialchars($post['description'])); ?></p>
                        <a href="<?php echo base_url('post/view.php?id=' . $post['id']); ?>">Voir plus</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucun post disponible pour l'instant.</p>
        <?php endif; ?>
    </section>
</body>
</html>
