<?php
$active_menu = 'evenements';
include_once 'C:\xampp\htdocs\lezm\view\admin\partials\sidebar.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Événements</title>
    <style>
        /* Include the dashboard CSS */
        <?php include 'C:\xampp\htdocs\lezm\view\assets\css\dashboard.css'; ?>
        
        /* Sidebar styling */
        .sidebar {
            position: fixed;
            width: 250px; /* Sidebar width */
            height: 100%;
            top: 0;
            left: 0;
            background-color: #333;
            color: white;
            padding-top: 20px;
            padding-left: 10px;
        }

        /* Content area */
        .content-area {
            margin-left: 260px; /* Adjust to ensure content doesn't overlap with sidebar */
            padding: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .btn {
            padding: 8px 16px;
            margin: 5px;
            text-decoration: none;
            border-radius: 4px;
            color: white;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-danger {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
<div class="content-area">
    <h2>Liste des Événements</h2>
    <a href="/lezm/admin/evenements/create" class="btn btn-primary">Créer un Événement</a>

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
                        <td>
                            <a href="/lezm/admin/evenements/edit/<?= $event['id'] ?>" class="btn btn-primary">Modifier</a>
                            <a href="/lezm/admin/evenements/delete/<?= $event['id'] ?>" class="btn btn-danger">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucun événement trouvé.</p>
    <?php endif; ?>
</div>
</body>
</html>
