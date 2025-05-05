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
        /* Base styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: flex-start; /* Align content a little higher */
            align-items: flex-start; /* Align content to the top */
            height: 100vh;
            padding-top: 50px; /* Give space from the top */
        }

        /* Sidebar styling */
        .sidebar {
            position: fixed;
            width: 250px;
            height: 100%;
            top: 0;
            left: 0;
            background-color: #2c3e50;
            color: white;
            padding-top: 20px;
            padding-left: 10px;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px 15px;
            font-size: 16px;
            border-bottom: 1px solid #34495e;
        }

        .sidebar a:hover {
            background-color: #34495e;
        }

        /* Content area */
        .content-area {
            margin-left: 260px;
            padding: 30px;
            background-color: #ecf0f1;
            width: 70%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        h2 {
            color: #2c3e50;
            text-align: center;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            font-size: 16px;
            margin: 5px;
        }

        /* Button Styles */
        .btn-primary {
            background-color: #27ae60; /* New Green */
        }

        .btn-primary:hover {
            background-color: #2ecc71; /* Hover effect */
        }

        .btn-danger {
            background-color: #e74c3c; /* Red */
        }

        .btn-danger:hover {
            background-color: #c0392b; /* Hover effect */
        }

        /* Table Styling */
        .table-container {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .table th, .table td {
            padding: 12px 15px;
            text-align: left;
        }

        .table th {
            background-color: #3498db;
            color: white;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .table td {
            background-color: #f9f9f9;
        }

        .table tr:nth-child(even) td {
            background-color: #f2f2f2;
        }

        .table td a {
            padding: 8px 16px;
            border-radius: 4px;
            color: white;
            text-decoration: none;
        }

        .table td a:hover {
            opacity: 0.8;
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            .content-area {
                margin-left: 0;
                padding: 15px;
            }

            .table th, .table td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
<div class="content-area">
    <h2>Liste des Événements</h2>
    <a href="/2A27/admin/evenements/create" class="btn btn-primary">Créer un Événement</a>

    <div class="table-container">
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
