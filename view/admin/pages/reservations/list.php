<?php
$active_menu = 'evenements';
include_once 'C:\xampp\htdocs\lezm\view\admin\partials\sidebar.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Réservations</title>
    <style>
        <?php include 'C:\xampp\htdocs\lezm\view\assets\css\dashboard.css'; ?>
    </style>
</head>
<body>
<div class="content-area" style="margin-left: 250px; padding: 20px;">
    <!-- Content area with margin-left to avoid overlap with the sidebar -->
    <h2>Liste des Réservations</h2>
    <a href="/lezm/admin/reservations/create" class="btn btn-primary">Créer une Réservation</a>

    <?php if (!empty($reservations)): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom du Participant</th>
                    <th>Email</th>
                    <th>Date de Réservation</th>
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
                        <td>
                            <a href="/lezm/admin/reservations/edit/<?= $r['id_reservation'] ?>" class="btn btn-primary">Modifier</a>
                            <a href="/lezm/admin/reservations/delete/<?= $r['id_reservation'] ?>" class="btn btn-danger">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucune réservation trouvée.</p>
    <?php endif; ?>
</div>
</body>
</html>
