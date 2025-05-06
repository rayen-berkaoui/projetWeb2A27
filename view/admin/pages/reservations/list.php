<?php
$active_menu = 'evenements';
include_once 'C:\xampp1\htdocs\2A27\view\admin\partials\sidebar.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Réservations</title>
    <style>
        /* Les styles restent inchangés ici */
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
            text-align: center; /* Centrer tout le contenu dans .content-area */
        }

        h2 {
            color: var(--galaxy-color);
            font-size: 30px;
            margin-bottom: 20px;
            border-left: 5px solid var(--planetary-color);
            padding-left: 12px;
            display: inline-block; /* Pour centrer le titre */
        }

        .btn-container {
            margin-top: 20px; /* Espacement au-dessus du bouton */
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
            margin-right: auto; /* Centrer le tableau */
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
    <h2>Liste des Réservations</h2>

    <!-- Bouton "Voir les Statistiques" en haut de la page -->
    <div class="btn-container">
        <a href="/2A27/view/admin/pages/evenements/stat.php" class="btn btn-primary">Voir les Statistiques</a>
    </div>

    <!-- Le bouton "Créer une Réservation" -->
    <div class="btn-container">
        <a href="/2A27/admin/reservations/create" class="btn btn-primary">Créer une Réservation</a>
    </div>

    <!-- Bouton "Aller à Trie" -->
    <div class="btn-container">
        <a href="http://localhost/2A27/view/admin/pages/reservations/trie.php" class="btn btn-primary">Aller à Trie</a>
    </div>

    <?php if (!empty($reservations)): ?>
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom du Participant</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Événement</th>
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
