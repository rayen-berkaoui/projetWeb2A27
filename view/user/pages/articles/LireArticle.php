<?php
// Get the article ID from the URL
$articleId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($articleId <= 0) {
    die("Invalid article ID.");
}

// PDO connection (for your existing database interactions)
try {
    $pdo = new PDO("mysql:host=localhost;dbname=db_html;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Fetch the article data (from your database)
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

// Fetch all articles to compare content for similarity
$allArticlesStmt = $pdo->prepare("
    SELECT a.id, a.content, t.nom AS type_name
    FROM articles a
    JOIN type t ON a.type_id = t.id
    WHERE a.id != :id
");
$allArticlesStmt->bindValue(':id', $articleId, PDO::PARAM_INT);
$allArticlesStmt->execute();
$allArticles = $allArticlesStmt->fetchAll(PDO::FETCH_ASSOC);

// Function to calculate content similarity using a basic approach (word overlap or cosine similarity)
function calculateContentSimilarity($content1, $content2) {
    // Convert content to lowercase and remove punctuation for better matching
    $content1 = strtolower($content1);
    $content2 = strtolower($content2);

    // Tokenize the content into words (simple word matching)
    $words1 = preg_split('/\s+/', $content1);
    $words2 = preg_split('/\s+/', $content2);

    // Calculate the overlap of words (intersection of both word lists)
    $commonWords = array_intersect($words1, $words2);
    $similarity = count($commonWords) / max(count($words1), count($words2)); // Simple similarity score

    return $similarity;
}

// Calculate similarity for each article
$similarArticles = [];
foreach ($allArticles as $otherArticle) {
    $similarity = calculateContentSimilarity($article['content'], $otherArticle['content']);
    if ($similarity > 0.1) { // Threshold for similarity (you can adjust this)
        $similarArticles[] = [
            'id' => $otherArticle['id'],
            'type_name' => $otherArticle['type_name'],
            'similarity' => $similarity
        ];
    }
}

// Sort the similar articles by similarity (highest first)
usort($similarArticles, function($a, $b) {
    return $b['similarity'] <=> $a['similarity'];
});

// You can refine the recommendations using OpenAI if desired (optional)
$apiUrl = "https://api.openai.com/v1/completions";
$apiKey = "";

// Prepare the data for OpenAI to refine the recommendation based on the current article's content
$openAiPrompt = "Please recommend similar articles based on the following content: " . $article['content'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $apiKey"
]);

$data = [
    "model" => "text-davinci-003", // You can choose the model you want to use
    "prompt" => $openAiPrompt,
    "max_tokens" => 200, // Adjust based on the number of tokens you want
];

$jsonData = json_encode($data);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

$response = curl_exec($ch);
if ($response === false) {
    die("Error fetching recommendations from OpenAI API.");
}

$openAiRecommendations = json_decode($response, true);

curl_close($ch);

?>

<!-- HTML to display the article and recommendations -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article - <?= htmlspecialchars($article['type_name']) ?></title>
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
            <?php if (count($similarArticles) > 0): ?>
                <ul>
                    <?php foreach ($similarArticles as $recommended): ?>
                        <li>
                            <a href="/lezm/view/user/pages/articles/LireArticle.php?id=<?= $recommended['id'] ?>">
                                <?= htmlspecialchars($recommended['type_name']) ?>
                            </a>
                            <p>Similarity Score: <?= number_format($recommended['similarity'], 2) ?></p>
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
