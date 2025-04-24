<?php
// PDO connection
try {
    $pdo = new PDO("mysql:host=localhost;dbname=db_html;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Get the article ID from the URL
$articleId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($articleId <= 0) {
    die("Invalid article ID.");
}

// Fetch the article data
$stmt = $pdo->prepare("
    SELECT a.id, a.type_id, a.author, a.time_created, a.content, t.nom AS type_name
    FROM articles a
    JOIN type t ON a.type_id = t.id
    WHERE a.id = :id
");
$stmt->bindValue(':id', $articleId, PDO::PARAM_INT);
$stmt->execute();
$article = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if article exists
if (!$article) {
    die("Article not found.");
}

// Fetch recommended articles based on the type of the current article
$stmtRecommendations = $pdo->prepare("
    SELECT a.id, a.type_id, a.author, a.time_created, a.content, t.nom AS type_name
    FROM articles a
    JOIN type t ON a.type_id = t.id
    WHERE a.type_id = :type_id AND a.id != :article_id
    ORDER BY a.time_created DESC
    LIMIT 5
");
$stmtRecommendations->bindValue(':type_id', $article['type_id'], PDO::PARAM_INT);  // Corrected to use type_id
$stmtRecommendations->bindValue(':article_id', $articleId, PDO::PARAM_INT);
$stmtRecommendations->execute();
$recommendedArticles = $stmtRecommendations->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article - <?= htmlspecialchars($article['type_name']) ?></title>
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

    <!-- Article Section -->
    <section id="article">
        <h2><?= htmlspecialchars($article['type_name']) ?></h2>

        <div class="article-details">
            <h3><?= htmlspecialchars($article['type_name']) ?> - <?= htmlspecialchars($article['author']) ?></h3>
            <p><strong>Published on:</strong> <?= htmlspecialchars($article['time_created']) ?></p>
            <div class="article-content">
                <p><?= nl2br(htmlspecialchars($article['content'])) ?></p>
            </div>
        </div>

        <!-- Recommendations Section -->
        <section id="recommended-articles">
            <h3>Recommended Articles</h3>
            <?php if (count($recommendedArticles) > 0): ?>
                <ul>
                    <?php foreach ($recommendedArticles as $recommended): ?>
                        <li>
                            <a href="/2A27/view/user/pages/articles/LireArticle.php?id=<?= $recommended['id'] ?>">
                                <?= htmlspecialchars($recommended['type_name']) ?> - <?= htmlspecialchars($recommended['author']) ?>
                            </a>
                            <p><?= nl2br(htmlspecialchars(substr($recommended['content'], 0, 100))) ?>...</p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No recommendations available at the moment.</p>
            <?php endif; ?>
        </section>

        <!-- Add a "Back to Articles" link -->
        <a href="articles.php" class="back-to-articles">Back to Articles</a>
    </section>
</body>
</html>
