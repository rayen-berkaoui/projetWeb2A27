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

        /* Styles pour le formulaire de test d'email */
        .test-email-section {
            background-color: var(--sky-color);
            padding: 20px;
            border-radius: 12px;
            margin: 20px auto;
            max-width: 600px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .test-email-section h3 {
            color: var(--galaxy-color);
            margin-bottom: 15px;
            font-size: 20px;
        }

        .email-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-group {
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: var(--text-color);
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid var(--venus-color);
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--planetary-color);
        }

        /* Styles pour la section d'email */
        .email-verification-section {
            background-color: var(--sky-color);
            padding: 30px;
            border-radius: 12px;
            margin: 40px auto 20px;
            max-width: 600px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .email-verification-section h3 {
            color: var(--galaxy-color);
            margin-bottom: 20px;
            font-size: 22px;
            text-align: center;
            position: relative;
            padding-bottom: 10px;
        }

        .email-verification-section h3:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background-color: var(--planetary-color);
        }

        .email-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-color);
            font-weight: 600;
            font-size: 16px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid var(--venus-color);
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: white;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--planetary-color);
            box-shadow: 0 0 0 3px rgba(51, 78, 172, 0.1);
        }

        .btn-primary {
            font-size: 16px;
            padding: 12px 24px;
            background-color: var(--planetary-color);
            border: none;
            border-radius: 8px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary:hover {
            background-color: var(--galaxy-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(51, 78, 172, 0.2);
        }

        /* Styles pour les messages de succès/erreur */
        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
            text-align: center;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
<div class="content-area">
    <h2>Liste des Réservations</h2>

    <!-- Messages d'alerte -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_GET['success']) ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($_GET['error']) ?>
        </div>
    <?php endif; ?>

    <!-- Boutons de navigation -->
    <div class="btn-container">
        <a href="/2A27/view/admin/pages/evenements/stat.php" class="btn btn-primary">Voir les Statistiques</a>
        <a href="/2A27/admin/reservations/create" class="btn btn-primary">Créer une Réservation</a>
        <a href="http://localhost/2A27/view/admin/pages/reservations/trie.php" class="btn btn-primary">Aller à Trie</a>
    </div>

    <!-- Tableau des réservations -->
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
                        <th>Statut</th>
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
                                <?php if ($r['statut'] === 'en_attente'): ?>
                                    <span style="color: #f39c12;">En attente</span>
                                <?php elseif ($r['statut'] === 'confirmee'): ?>
                                    <span style="color: #27ae60;">Confirmée</span>
                                <?php endif; ?>
                            </td>
                            <td class="actions">
                                <?php if ($r['statut'] === 'en_attente'): ?>
                                    <a href="/2A27/admin/reservations/confirm/<?= $r['id_reservation'] ?>" class="btn btn-primary">Confirmer</a>
                                <?php endif; ?>
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

    <!-- Section d'envoi d'email (déplacée sous le tableau) -->
    <div class="email-verification-section">
        <h3>Envoi de confirmation</h3>
        <form method="POST" action="/2A27/admin/test-email" class="email-form">
            <div class="form-group">
                <label for="email">Adresse email du participant</label>
                <input type="email" id="email" name="email" required class="form-control" 
                       placeholder="Entrez l'adresse email pour envoyer la confirmation">
            </div>
            <button type="submit" class="btn btn-primary">Envoyer la confirmation</button>
        </form>
    </div>
</div>
</body>
</html>
