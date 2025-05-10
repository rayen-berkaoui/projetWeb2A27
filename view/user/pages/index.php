<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website</title>
    <link href="http://<?= $_SERVER['HTTP_HOST'] ?>/2A27/view/assets/css/style.css" rel="stylesheet">
    <script src="C:\xampp\htdocs\2A27\view\assets\js\script.js" defer></script>
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

            <!-- Check if the user is logged in -->
            <?php if (isset($_SESSION['user']['id'])): ?>
                <!-- Show Profile and Logout links if logged in -->
                <li><a href="/2A27/profile">Profile</a></li>
                <li><a href="/2A27/profile?logout=1">Logout</a></li>
            <?php else: ?>
                <!-- Show Login link if not logged in -->
                <li><a href="/2A27/login">Login</a></li>
            <?php endif; ?>

            <li><a href="/2A27/admin">Admin</a></li>
        </ul>
    </nav>

    <!-- Main content -->
    <div class="main-content">

        <!-- Welcome Section -->
        <section id="welcome">
            <h1>Welcome to Our Website</h1>
            <p>Welcome message goes here.</p>
        </section>

        <!-- About Section -->
        <section id="about">
            <h2>About Us</h2>
            <p>Information about the website or company.</p>
        </section>

        <!-- Articles Section -->
        <section id="articles">
            <h2>Articles</h2>
            <p>Here are some interesting articles.</p>
        </section>

        <!-- Events Section -->
        <section id="events">
            <h2>Upcoming Events</h2>
            <p>Details about upcoming events.</p>
        </section>

        <!-- Forum Section -->
        <section id="forum">
            <h2>Forum</h2>
            <p>Join our forum discussions here.</p>
        </section>

        <!-- Marketing Section -->
        <section id="marketing">
            <h2>Marketing</h2>
            <p>Details about marketing strategies and offers.</p>
        </section>

        <!-- Avis Section -->
        <section id="avis">
            <h2>Avis</h2>
            <p>Customer reviews and opinions.</p>
        </section>

    </div>

</body>
</html>
