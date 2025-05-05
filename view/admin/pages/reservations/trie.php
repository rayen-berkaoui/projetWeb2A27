<?php
// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=db_html', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Champs autorisés pour le tri
$allowedSortFields = ['id_reservation', 'nom_participant', 'email', 'date_reservation', 'evenement_nom'];

// Récupérer les paramètres de tri
$sort = isset($_GET['sort']) && in_array($_GET['sort'], $allowedSortFields) ? $_GET['sort'] : 'id_reservation';
$order = isset($_GET['order']) && $_GET['order'] == 'desc' ? 'DESC' : 'ASC';

// Adapter le champ de tri (colonne de jointure si besoin)
$sortColumn = $sort === 'evenement_nom' ? 'e.titre' : "r.$sort";

// Requête SQL avec jointure correcte
$query = "SELECT r.*, e.titre AS evenement_nom 
        FROM reservations r 
        LEFT JOIN evenement e ON r.id_evenement = e.id  -- Correction ici
        ORDER BY $sortColumn $order";

$stmt = $pdo->prepare($query);
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Réservations - Tri</title>
    <style>
        :root {
            --planetary-color: #334EAC;
            --venus-color: #BAD6EB;
            --meteor-color: #F7F2EB;
            --galaxy-color: #081F5C;
            --milky-way-color: #FFF9F0;
            --universe-color: #7096D1;
            --sky-color: #D0E3FF;
            --text-color: #081F5C;
            --btn-primary-color: #334EAC;
            --btn-danger-color: #E74C3C;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--milky-way-color);
            color: var(--text-color);
            margin: 0;
            padding: 0;
        }

        .content-area {
            margin-left: 250px;
            padding: 30px;
            text-align: center;
        }

        h2 {
            color: var(--galaxy-color);
            font-size: 30px;
            margin-bottom: 20px;
            border-left: 5px solid var(--planetary-color);
            padding-left: 12px;
        }

        .btn-container {
            margin-top: 20px;
        }

        .btn {
            padding: 8px 18px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            display: inline-block;
        }

        .btn-primary {
            background-color: var(--btn-primary-color);
            color: #fff;
        }

        .btn-primary:hover {
            background-color: var(--galaxy-color);
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: var(--btn-danger-color);
            color: #fff;
        }

        .btn-danger:hover {
            background-color: #C0392B;
            transform: translateY(-2px);
        }

        .table-wrapper {
            margin-top: 25px;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
            background-color: var(--sky-color);
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        table th {
            background-color: var(--planetary-color);
            color: var(--milky-way-color);
            padding: 10px;
            text-align: left;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        table td {
            padding: 10px;
            background-color: var(--venus-color);
            border-bottom: 1px solid var(--sky-color);
            transition: background-color 0.3s ease;
        }

        table tr:nth-child(even) td {
            background-color: var(--meteor-color);
        }

        table tr:hover td {
            background-color: var(--universe-color);
            color: #fff;
        }

        .actions {
            display: flex;
            gap: 6px;
        }

        p {
            color: var(--planetary-color);
            font-size: 17px;
        }
    </style>
</head>
<body>
<div class="content-area">
    <h2>Liste des Réservations - Tri</h2>

    <!-- Bouton pour créer une nouvelle réservation -->
    <div class="btn-container">
        <a href="/2A27/admin/reservations/create" class="btn btn-primary">Créer une Réservation</a>
    </div>

    <!-- Table des réservations -->
    <?php if (!empty($reservations)): ?>
        <div class="table-wrapper">
            <table class="table">
                <thead>
                <tr>
                    <th><a href="?sort=id_reservation&order=<?= $order == 'asc' ? 'desc' : 'asc' ?>">ID</a></th>
                    <th><a href="?sort=nom_participant&order=<?= $order == 'asc' ? 'desc' : 'asc' ?>">Nom du Participant</a></th>
                    <th><a href="?sort=email&order=<?= $order == 'asc' ? 'desc' : 'asc' ?>">Email</a></th>
                    <th><a href="?sort=date_reservation&order=<?= $order == 'asc' ? 'desc' : 'asc' ?>">Date</a></th>
                    <th><a href="?sort=evenement_nom&order=<?= $order == 'asc' ? 'desc' : 'asc' ?>">Événement</a></th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($reservations as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['id_reservation']) ?></td>
                        <td><?= htmlspecialchars($r['nom_participant']) ?></td>
                        <td><?= htmlspecialchars($r['email']) ?></td>
                        <td><?= htmlspecialchars($r['date_reservation']) ?></td>
                        <td><?= htmlspecialchars($r['evenement_nom']) ?></td>
                        <td class="actions">
                            <a href="/2A27/admin/reservations/edit/<?= $r['id_reservation'] ?>" class="btn btn-primary">Modifier</a>
                            <a href="/2A27/admin/reservations/delete/<?= $r['id_reservation'] ?>" class="btn btn-danger">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>Aucune réservation trouvée.</p>
    <?php endif; ?>
</div>
</body>
</html>
