<?php
// Connexion à la base de données avec PDO
try {
    $db = new PDO('mysql:host=localhost;dbname=db_html', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    exit();
}

// Récupérer les événements depuis la base de données
$query = 'SELECT * FROM evenement';
$stmt = $db->prepare($query);
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer le nombre de réservations pour chaque événement
$eventReservationCounts = [];
foreach ($events as $event) {
    $eventId = $event['id'];
    $reservationQuery = 'SELECT COUNT(*) FROM reservations WHERE id_evenement = :event_id';
    $reservationStmt = $db->prepare($reservationQuery);
    $reservationStmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
    $reservationStmt->execute();
    $reservationCount = $reservationStmt->fetchColumn();
    $eventReservationCounts[$eventId] = $reservationCount;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nos Événements</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #FFF9F0;
            margin: 0;
            padding: 0;
        }
        .events-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }
        .top-bar {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 20px;
        }
        .btn-home {
            background-color: #334EAC;
            color: #FFF9F0;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.3s ease;
        }
        .btn-home:hover {
            background-color: #081F5C;
        }
        h2 {
            text-align: center;
            color: #334EAC;
            font-size: 36px;
            margin-bottom: 40px;
        }
        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }
        .event-card {
            background: #BAD6EB;
            border-radius: 15px;
            box-shadow: 0px 8px 20px rgba(0,0,0,0.1);
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: 0.3s ease;
            color: #081F5C;
        }
        .event-card:nth-child(2n) {
            background: #D0E3FF;
        }
        .event-card:nth-child(3n) {
            background: #7096D1;
            color: #FFF9F0;
        }
        .event-card:hover {
            transform: translateY(-8px);
            box-shadow: 0px 12px 24px rgba(0,0,0,0.15);
        }
        .event-title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .event-desc {
            font-size: 14px;
            margin-bottom: 15px;
        }
        .event-info {
            font-size: 13px;
            margin-bottom: 10px;
        }
        .badge {
            background-color: #334EAC;
            color: #FFF9F0;
            border-radius: 50px;
            padding: 6px 12px;
            font-size: 12px;
            display: inline-block;
            margin-bottom: 15px;
        }
        .btn-start {
            background: #081F5C;
            color: #FFF9F0;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            transition: background 0.3s ease;
            margin-top: 15px;
        }
        .btn-start:hover {
            background: #334EAC;
        }
        .no-events {
            text-align: center;
            font-size: 20px;
            color: #d63031;
        }
    </style>
</head>
<body>

<div class="events-container">
    <!-- Boutons haut de page -->
    <div class="top-bar">
        <a href="/2A27/home" class="btn-home">🏠 Accueil</a>
        <a href="/2A27/view/admin/pages/evenements/recherche.php" class="btn-home">🔍 Recherche</a>
    </div>

    <h2>Nos Événements</h2>

    <div class="events-grid">
        <?php if (!empty($events)): ?>
            <?php foreach ($events as $event): ?>
                <div class="event-card">
                    <div>
                        <div class="badge">Réservations: <?= $eventReservationCounts[$event['id']] ?></div>
                        <div class="event-title"><?= htmlspecialchars($event['titre']) ?></div>
                        <div class="event-info"><strong>Date:</strong> <?= htmlspecialchars($event['date']) ?></div>
                        <div class="event-info"><strong>Lieu:</strong> <?= htmlspecialchars($event['lieu']) ?></div>
                        <div class="event-desc"><?= htmlspecialchars($event['description']) ?></div>
                    </div>
                    <a href="/2A27/events/reserve/<?= $event['id'] ?>" class="btn-start">Réserver Maintenant</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-events">Aucun événement à venir pour le moment.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
