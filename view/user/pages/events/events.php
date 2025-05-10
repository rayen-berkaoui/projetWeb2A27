<?php
// Include the EvenementController
require_once 'C:\xampp\htdocs\2A27\src\controll\routes\admin\EvenementController.php';

// Connect to the database
$db = new PDO('mysql:host=localhost;dbname=db_html', 'root', ''); 

// Instantiate the controller
$evenementController = new EvenementController($db);

// Fetch events
$events = $evenementController->indexEvenements();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Events</title>
    <link href="http://<?= $_SERVER['HTTP_HOST'] ?>/2A27/view/assets/css/style.css" rel="stylesheet">
    <script src="/2A27/view/assets/js/script.js" defer></script>
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

<!-- Main Content -->
<div class="main-content">
    <section id="events">
        <h2>Upcoming Events</h2>

        <?php if (!empty($events)): ?>
            <div class="events-list">
                <?php foreach ($events as $event): ?>
                    <div class="event-item">
                        <h3><?= htmlspecialchars($event['titre']) ?></h3>
                        <p><strong>Date:</strong> <?= htmlspecialchars($event['date']) ?></p>
                        <p><strong>Lieu:</strong> <?= htmlspecialchars($event['lieu']) ?></p>
                        <p><?= htmlspecialchars($event['description']) ?></p>
                        <a href="/2A27/events/reserve/<?= $event['id'] ?>" class="btn btn-primary">Reserve Now</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No upcoming events available at the moment.</p>
        <?php endif; ?>
    </section>
</div>

</body>
</html>
