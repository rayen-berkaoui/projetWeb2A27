<?php
$db = new mysqli('localhost', 'root', '', 'db_html');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Fetch articles from the database
$query = "SELECT * FROM articles ORDER BY time_created DESC";
$result = $db->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles</title>
    <link href="http://<?= $_SERVER['HTTP_HOST'] ?>/lezm/view/assets/css/style.css" rel="stylesheet">
    <script src="C:\xampp\htdocs\lezm\view\assets\js/script.js" defer></script>
</head>
<body>
    <!-- Navbar -->
    <nav>
        <ul>
            <li><a href="/lezm/home">Welcome</a></li>
            <li><a href="/lezm/about">About</a></li>
            <li><a href="/lezm/articles">Articles</a></li>
            <li><a href="/lezm/events">Events</a></li>
            <li><a href="/lezm/forum">Forum</a></li>*ù
            <li><a href="/lezm/marketing">Marketing</a></li>
            <li><a href="/lezm/avis">Avis</a></li>
            <li><a href="/lezm/login">Login</a></li>
            <li><a href="/lezm/admin">Admin</a></li>
        </ul>
    </nav>

    <!-- Articles Section -->
    <section id="articles">
        <h2>Articles</h2>
        <?php
        if ($result->num_rows > 0) {
            // Loop through each article and display it
            while ($article = $result->fetch_assoc()) {
                echo "
                <div class='article'>
                    <h3>{$article['type']}</h3>
                    <p><strong>Author:</strong> {$article['author']}</p>
                    <p>{$article['content']}</p>
                    <small>Published on {$article['time_created']}</small>
                </div>
                ";
            }
        } else {
            echo "<p>No articles available.</p>";
        }
        ?>
    </section>
</body>
</html>
