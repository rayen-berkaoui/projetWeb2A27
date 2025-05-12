<?php
// PDO connection
try {
    $pdo = new PDO("mysql:host=localhost;dbname=db_html;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Pagination logic
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);
$articlesPerPage = 5;
$offset = ($page - 1) * $articlesPerPage;

// Get the selected filter type
$selectedType = isset($_GET['type']) ? (int)$_GET['type'] : 0;

// Prepare the WHERE clause based on selected type
$whereClause = "";
if ($selectedType > 0) {
    $whereClause = "WHERE a.type_id = :type_id";
}

// Count total articles with or without the filter
$stmtTotal = $pdo->prepare("SELECT COUNT(*) as total FROM articles a $whereClause");
if ($selectedType > 0) {
    $stmtTotal->bindValue(':type_id', $selectedType, PDO::PARAM_INT);
}
$stmtTotal->execute();
$totalArticles = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalArticles / $articlesPerPage);

// Fetch paginated articles with filter (using type_id and joining with type table)
$stmt = $pdo->prepare("
    SELECT a.id, a.author, a.time_created, a.content, t.nom AS type_name
    FROM articles a
    LEFT JOIN type t ON a.type_id = t.id
    $whereClause
    ORDER BY a.time_created DESC
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':limit', $articlesPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
if ($selectedType > 0) {
    $stmt->bindValue(':type_id', $selectedType, PDO::PARAM_INT);
}
$stmt->execute();
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all types for the filter dropdown
$stmtTypes = $pdo->query("SELECT * FROM type ORDER BY nom");
$types = $stmtTypes->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles</title>
    <link href="http://<?= $_SERVER['HTTP_HOST'] ?>/lezm/view/assets/css/style.css" rel="stylesheet">
    <script src="http://<?= $_SERVER['HTTP_HOST'] ?>/lezm/view/assets/js/script.js" defer></script>
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
            <li><a href="/lezm/avis">Avis</a></li>
            <li><a href="/lezm/login">Login</a></li>
            <li><a href="/lezm/admin">Admin</a></li>
        </ul>
    </nav>

    <!-- Articles Section -->
    <section id="articles">
        <h2>Articles</h2>

        <!-- Filter Dropdown -->
        <form method="GET" action="">
            <label for="type">Filter by Type:</label>
            <select name="type" id="type" onchange="this.form.submit()">
                <option value="0">All Types</option>
                <?php foreach ($types as $type): ?>
                    <option value="<?= $type['id'] ?>" <?= $type['id'] == $selectedType ? 'selected' : '' ?>>
                        <?= htmlspecialchars($type['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <?php if (count($articles) > 0): ?>
            <?php foreach ($articles as $article): ?>
                <div class="article">
                    <p><strong>Type:</strong> <?= htmlspecialchars($article['type_name']) ?></p>
                    <p><strong>Author:</strong> <?= htmlspecialchars($article['author']) ?></p>
                    <p><?= nl2br(htmlspecialchars(substr($article['content'], 0, 200))) ?>...</p>
                    <a href="/lezm/view/user/pages/articles/LireArticle.php?id=<?= $article['id'] ?>">Read More</a>
                    <small>Published on <?= htmlspecialchars($article['time_created']) ?></small>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No articles available.</p>
        <?php endif; ?>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>&type=<?= $selectedType ?>">&laquo; Prev</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>&type=<?= $selectedType ?>" class="<?= $i == $page ? 'current-page' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>&type=<?= $selectedType ?>">Next &raquo;</a>
            <?php endif; ?>
        </div>
    </section>
</body>
</html>
