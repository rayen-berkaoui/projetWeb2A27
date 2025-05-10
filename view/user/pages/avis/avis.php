<?php
// PDO connection
try {
    $pdo = new PDO("mysql:host=localhost;dbname=db_html;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Get the article ID from the URL
$articleId = isset($_GET['article_id']) ? (int)$_GET['article_id'] : 0;

// Fetch comments for the specific avis
$stmt = $pdo->prepare("
    SELECT c.commentaire_id, c.contenu, c.date_creation, a.contenu AS avis_contenu
    FROM commentaires c
    LEFT JOIN avis a ON c.avis_id = a.avis_id
    WHERE c.avis_id = :article_id
    ORDER BY c.date_creation DESC
");
$stmt->bindValue(':article_id', $articleId, PDO::PARAM_INT);
$stmt->execute();
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commentaires</title>
    <link href="http://<?= $_SERVER['HTTP_HOST'] ?>/2A27/view/assets/css/style.css" rel="stylesheet">
    <script src="http://<?= $_SERVER['HTTP_HOST'] ?>/2A27/view/assets/js/script.js" defer></script>
</head>
<body>
    <!-- Navbar -->
    <nav>
        <ul>
            <li><a href="/2A27/home">Welcome</a></li>
            <li><a href="/2A27/about">About</a></li>
            <li><a href="/2A27/articles">Articles</a></li>
            <li><a href="/2A27/events">Events</a></li>
            <li><a href="/2A27/forum">Forum</a></li>
            <li><a href="/2A27/marketing">Marketing</a></li>
            <li><a href="/2A27/avis">Avis</a></li>
            <li><a href="/2A27/login">Login</a></li>
            <li><a href="/2A27/admin">Admin</a></li>
        </ul>
    </nav>

    <!-- Commentaires Section -->
    <section id="comments">
        <h2>Commentaires for Avis</h2>

        <?php if (count($comments) > 0): ?>
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <p><strong>Avis Content:</strong> <?= nl2br(htmlspecialchars($comment['avis_contenu'])) ?></p>
                    <p><strong>Comment:</strong> <?= nl2br(htmlspecialchars($comment['contenu'])) ?></p>
                    <small>Posted on: <?= htmlspecialchars($comment['date_creation']) ?></small>
                    <hr>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No comments available for this avis.</p>
        <?php endif; ?>
    </section>
</body>
</html>
