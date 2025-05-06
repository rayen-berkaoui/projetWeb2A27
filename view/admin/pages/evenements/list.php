<?php
$active_menu = 'evenements';
include_once 'C:\xampp1\htdocs\2A27\view\admin\partials\sidebar.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Événements</title>
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
            display: inline-block;
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
    <h2>Liste des Événements</h2>
    <div class="btn-container">
        <a href="/2A27/admin/evenements/create" class="btn btn-primary">Créer un Événement</a>
    </div>

    <div class="table-wrapper">
        <?php if (!empty($evenements)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Lieu</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($evenements as $event): ?>
                        <tr>
                            <td><?= htmlspecialchars($event['id']) ?></td>
                            <td><?= htmlspecialchars($event['titre']) ?></td>
                            <td><?= htmlspecialchars(substr($event['description'], 0, 50)) ?>...</td>
                            <td><?= htmlspecialchars($event['date']) ?></td>
                            <td><?= htmlspecialchars($event['lieu']) ?></td>
                            <td class="actions">
                                <a href="/2A27/admin/evenements/edit/<?= $event['id'] ?>" class="btn btn-primary">Modifier</a>
                                <a href="/2A27/admin/evenements/delete/<?= $event['id'] ?>" class="btn btn-danger">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun événement trouvé.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
