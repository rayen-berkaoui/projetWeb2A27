<?php
// Assume $evenement is already set before this view is loaded.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reserve Event - <?= htmlspecialchars($evenement['titre']) ?></title>
    <link href="http://<?= $_SERVER['HTTP_HOST'] ?>/lezm/view/assets/css/style.css" rel="stylesheet">
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
        <li><a href="/lezm/avis">Avis</a></li>
        <li><a href="/lezm/login">Login</a></li>
        <li><a href="/lezm/admin">Admin</a></li>
    </ul>
</nav>

<!-- Main Content -->
<div class="main-content">
    <section id="reservation">
        <h2>Reserve a Spot for: <?= htmlspecialchars($evenement['titre']) ?></h2>

        <div class="event-details">
            <p><strong>Description:</strong> <?= htmlspecialchars($evenement['description']) ?></p>
            <p><strong>Date:</strong> <?= htmlspecialchars($evenement['date']) ?></p>
            <p><strong>Lieu:</strong> <?= htmlspecialchars($evenement['lieu']) ?></p>
        </div>

        <hr>

        <form action="/lezm/events/reserve/submit" method="POST" class="reservation-form">
            <input type="hidden" name="event_id" value="<?= $evenement['id'] ?>">

            <label for="nom">Your Name:</label><br>
            <input type="text" id="nom" name="nom" required><br><br>

            <label for="email">Your Email:</label><br>
            <input type="email" id="email" name="email" required><br><br>

            <button type="submit" class="btn btn-primary">Confirm Reservation</button>
        </form>
    </section>
</div>

</body>
</html>
