<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'db_html';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Initialisation
$resultats = [];
$where = [];
$params = [];

// Construction dynamique de la requête
if (!empty($_GET['titre'])) {
    $where[] = "titre LIKE :titre";
    $params['titre'] = '%' . $_GET['titre'] . '%';
}
if (!empty($_GET['date_debut'])) {
    $where[] = "date >= :date_debut";
    $params['date_debut'] = $_GET['date_debut'];
}
if (!empty($_GET['date_fin'])) {
    $where[] = "date <= :date_fin";
    $params['date_fin'] = $_GET['date_fin'];
}
if (!empty($_GET['lieu'])) {
    $where[] = "lieu LIKE :lieu";
    $params['lieu'] = '%' . $_GET['lieu'] . '%';
}

// Construction de la requête SQL
$sql = "SELECT * FROM evenement";
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de recherche : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche d'événements</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #F7F2EB; /* Meteor */
            margin: 0;
            padding: 0;
            color: #081F5C; /* Galaxy */
        }

        .container {
            width: 80%;
            margin: 30px auto;
            background-color: #FFF9F0; /* Milky Way */
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
        }

        h2 {
            text-align: center;
            color: #334EAC; /* Planetary */
            font-size: 24px;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 30px;
            background-color: #BAD6EB; /* Venus */
            padding: 20px;
            border-radius: 10px;
            flex-wrap: wrap; /* Permet aux éléments de se replier sur de petites résolutions */
        }

        label {
            font-size: 14px;
            color: #081F5C; /* Galaxy */
            margin-bottom: 8px;
        }

        input[type="text"], input[type="date"] {
            padding: 14px;
            border: 2px solid #7096D1; /* Universe */
            border-radius: 10px;
            font-size: 16px;
            background-color: #FFF9F0; /* Milky Way */
            color: #081F5C;
            transition: border 0.3s ease, background-color 0.3s ease;
            width: 30%; /* Taille des zones de texte */
            min-width: 180px;
        }

        input[type="text"]:focus, input[type="date"]:focus {
            border-color: #334EAC; /* Planetary */
            background-color: #D0E3FF; /* Sky */
            outline: none;
        }

        button {
            background-color: #334EAC; /* Planetary */
            color: white;
            border: none;
            padding: 14px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 8px;
            transition: background-color 0.3s ease;
            width: 100%; /* Le bouton prend toute la largeur */
        }

        button:hover {
            background-color: #7096D1; /* Universe */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        table th, table td {
            padding: 12px 20px;
            text-align: center;
            border: 1px solid #7096D1; /* Universe */
        }

        table th {
            background-color: #081F5C; /* Galaxy */
            color: white;
            font-size: 16px;
        }

        table tr:nth-child(even) {
            background-color: #D0E3FF; /* Sky */
        }

        table tr:hover {
            background-color: #BAD6EB; /* Venus */
        }

        .info-box {
            background-color: #D0E3FF; /* Sky */
            border-left: 4px solid #334EAC;
            padding: 15px;
            margin-bottom: 20px;
            font-size: 16px;
        }

        .reserve-btn {
            background-color: #334EAC; /* Planetary */
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
        }

        .reserve-btn:hover {
            background-color: #081F5C; /* Galaxy */
        }

        .no-results {
            text-align: center;
            font-size: 18px;
            color: #081F5C; /* Galaxy */
            padding: 20px;
            border: 1px solid #7096D1; /* Universe */
            border-radius: 8px;
            background-color: #D0E3FF; /* Sky */
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Recherche d'événements</h2>

    <!-- Boîte d'information -->
    <div class="info-box">
        Entrez un ou plusieurs critères de recherche pour trouver des événements spécifiques.
    </div>

    <!-- Formulaire de recherche -->
    <form method="get">
        <div>
            <label for="titre">Nom de l'événement:</label>
            <input type="text" name="titre" id="titre" value="<?= htmlspecialchars($_GET['titre'] ?? '') ?>" placeholder="Entrez le nom de l'événement">
        </div>

        <div>
            <label for="date_debut">Date début:</label>
            <input type="date" name="date_debut" id="date_debut" value="<?= htmlspecialchars($_GET['date_debut'] ?? '') ?>">
        </div>

        <div>
            <label for="lieu">Lieu:</label>
            <input type="text" name="lieu" id="lieu" value="<?= htmlspecialchars($_GET['lieu'] ?? '') ?>" placeholder="Lieu de l'événement">
        </div>

        <button type="submit">Rechercher</button>
    </form>

    <!-- Affichage des résultats -->
    <?php if (count($resultats) > 0): ?>
        <h2>Résultats</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Date</th>
                <th>Lieu</th>
                <th>Réserver</th>
            </tr>
            <?php foreach ($resultats as $event): ?>
                <tr>
                    <td><?= $event['id'] ?></td>
                    <td><?= htmlspecialchars($event['titre']) ?></td>
                    <td><?= htmlspecialchars($event['date']) ?></td>
                    <td><?= htmlspecialchars($event['lieu'] ?? '') ?></td>
                    <td>
                        <a class="reserve-btn" href="/2A27/events/reserve/<?= $event['id'] ?>">Réserver</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p class="no-results">Aucun événement trouvé pour ces critères.</p>
    <?php endif; ?>
</div>

</body>
</html>
