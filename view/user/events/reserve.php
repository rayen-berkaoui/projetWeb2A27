<?php
if (!isset($evenement) || empty($evenement)) {
    echo "L'événement demandé est introuvable.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réserver un événement - <?= htmlspecialchars($evenement['titre']) ?></title>
    <link href="http://<?= $_SERVER['HTTP_HOST'] ?>/2A27/view/assets/css/style.css" rel="stylesheet">
    <script src="/2A27/view/assets/js/script.js" defer></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #FFF9F0; /* Milky Way */
            margin: 0;
            padding: 0;
        }
        nav {
            background: #34495e; /* Galaxy */
            padding: 15px;
        }
        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        nav ul li {
            display: inline-block;
            margin: 0 15px;
        }
        nav ul li a {
            color: #FFF9F0;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        nav ul li a:hover {
            color: #BAD6EB; /* Venus */
        }

        .main-content {
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background: #F7F2EB; /* Meteor */
            border-radius: 12px;
            box-shadow: 0px 8px 20px rgba(0,0,0,0.1);
        }
        #reservation h2 {
            text-align: center;
            color: #334EAC; /* Planetary */
            margin-bottom: 25px;
        }
        .event-details {
            background: #D0E3FF; /* Sky */
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .event-details p {
            margin: 10px 0;
            font-size: 16px;
            color: #081F5C; /* Galaxy */
        }
        hr {
            margin: 30px 0;
            border: none;
            border-top: 1px solid #e0e0e0;
        }
        .reservation-form label {
            font-weight: 600;
            color: #081F5C;
        }
        .reservation-form input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        .btn-primary {
            background: #334EAC; /* Planetary */
            color: #FFF9F0;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
            display: block;
            margin: 0 auto;
        }
        .btn-primary:hover {
            background: #7096D1; /* Universe */
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav>
    <ul>
        <li><a href="/2A27/home">Accueil</a></li>
        <li><a href="/2A27/about">À propos</a></li>
        <li><a href="/2A27/articles">Articles</a></li>
        <li><a href="/2A27/events">Événements</a></li>
        <li><a href="/2A27/forum">Forum</a></li>
        <li><a href="/2A27/marketing">Marketing</a></li>
        <li><a href="/2A27/avis">Avis</a></li>
        <li><a href="/2A27/login">Connexion</a></li>
        <li><a href="/2A27/admin">Admin</a></li>
    </ul>
</nav>

<!-- Main Content -->
<div class="main-content">
    <section id="reservation">
        <h2>Réserver un Spot pour : <?= htmlspecialchars($evenement['titre']) ?></h2>

        <div class="event-details">
            <p><strong>Description :</strong> <?= htmlspecialchars($evenement['description']) ?></p>
            <p><strong>Date :</strong> <?= htmlspecialchars($evenement['date']) ?></p>
            <p><strong>Lieu :</strong> <?= htmlspecialchars($evenement['lieu']) ?></p>
        </div>

        <hr>

        <form action="/2A27/events/reserve/submit" method="POST" class="reservation-form">
            <input type="hidden" name="event_id" value="<?= $evenement['id'] ?>">

            <label for="nom">Votre nom :</label>
            <input type="text" id="nom" name="nom" required>

            <label for="email">Votre email :</label>
            <input type="email" id="email" name="email" required>

            <button type="submit" class="btn-primary">Confirmer la réservation</button>
        </form>
    </section>
</div>

</body>
</html>
