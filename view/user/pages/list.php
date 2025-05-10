<?php $active_menu = 'posts'; include_once 'C:\xampp\htdocs\2A27\view\admin\partials\sidebar.php'; ?>

<div class="form-wrapper">
    <h1 class="page-title">✏️ Modifier le Post</h1>

    <form method="POST" action="/2A27/admin/posts/update/<?= $post['id'] ?>" class="form-content">
        <!-- Titre -->
        <div class="form-group">
            <label for="titre">Titre</label>
            <input type="text" name="titre" id="titre" value="<?= htmlspecialchars($post['titre']) ?>" class="form-input" required>
        </div>

        <!-- Description -->
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-input" rows="6" required><?= htmlspecialchars($post['description']) ?></textarea>
        </div>

        <!-- Statut -->
        <div class="form-group">
            <label for="statut">Statut</label>
            <select name="statut" id="statut" class="form-input" required>
                <option value="">-- Choisir un statut --</option>
                <option value="actif" <?= $post['statut'] === 'actif' ? 'selected' : '' ?>>Actif</option>
                <option value="inactif" <?= $post['statut'] === 'inactif' ? 'selected' : '' ?>>Inactif</option>
            </select>
        </div>

        <!-- Bouton de mise à jour -->
        <div class="form-group">
            <button type="submit" class="btn btn-primary">✅ Mettre à jour</button>
        </div>
    </form>
</div>

<style>
    * {
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        background-color: #f5f7fa;
        margin: 0;
        padding: 0;
    }

    .form-wrapper {
        background-color: #d9fbe5; /* Vert clair */
        padding: 80px;
        border-radius: 18px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        width: calc(100% - 280px);  /* Largeur totale - sidebar */
        margin: 60px auto 60px 280px; /* haut auto bas gauche */
        min-height: 85vh;
    }

    .page-title {
        font-size: 42px;
        margin-bottom: 40px;
        text-align: center;
        color: #2e7d32;
    }

    .form-group {
        margin-bottom: 30px;
    }

    .form-input, textarea, select {
        width: 100%;
        padding: 18px;
        font-size: 1.2rem;
        border: 1px solid #aaa;
        border-radius: 10px;
        background-color: #fff;
    }

    .form-input:focus, textarea:focus, select:focus {
        border-color: #2e7d32;
        outline: none;
        background-color: #f4fff6;
    }

    .btn-primary {
        background-color: #2e7d32;
        color: white;
        padding: 16px 32px;
        font-size: 1.2rem;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #1b5e20;
    }

    @media screen and (max-width: 768px) {
        .form-wrapper {
            margin: 20px;
            padding: 40px;
            width: 100%;
        }

        .page-title {
            font-size: 30px;
        }
    }
</style>
Ilyes
<?php
$active_menu = 'posts';
include_once 'C:\xampp\htdocs\2A27\view\admin\partials\sidebar.php';

// Vérification du paramètre de tri dans l'URL
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'asc';  // Tri par défaut par ordre croissant

// Connexion à la base de données et récupération des posts
try {
    $pdo = new PDO('mysql:host=localhost;dbname=db_html', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL de tri
    $order = $sort == 'desc' ? 'DESC' : 'ASC';  // Condition pour déterminer l'ordre
    $sql = "SELECT * FROM post ORDER BY id $order"; // Utilisation du bon nom de table 'post'
    $stmt = $pdo->query($sql);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Statistiques des posts actifs et inactifs
    $sql_stats = "SELECT statut, COUNT(*) AS count FROM post GROUP BY statut";
    $stmt_stats = $pdo->query($sql_stats);
    $stats = $stmt_stats->fetchAll(PDO::FETCH_ASSOC);

    $active_count = 0;
    $inactive_count = 0;

    foreach ($stats as $stat) {
        if ($stat['statut'] === 'actif') {
            $active_count = $stat['count'];
        } elseif ($stat['statut'] === 'inactif') {
            $inactive_count = $stat['count'];
        }
    }
} catch (PDOException $e) {
    echo 'Erreur : ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Posts</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(to right, #e0f7fa, #f1f8e9);
            color: #333;
            display: flex;
            min-height: 100vh;
            font-size: 18px;
        }

        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            padding: 30px 20px;
        }

        .content-area {
            margin-left: 270px;
            padding: 40px 30px;
            flex: 1;
            background-color: rgba(255, 255, 255, 0.8);
            border-top-left-radius: 20px;
            border-bottom-left-radius: 20px;
            box-shadow: -2px 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            font-size: 32px;
            margin-bottom: 30px;
            color: #2c3e50;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            font-size: 20px;
        }

        .table th, .table td {
            padding: 20px 25px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .table th {
            background-color: #388e3c;
            color: white;
            font-weight: bold;
        }

        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table tr:hover {
            background-color: #e0f2f1;
            transition: 0.3s;
        }

        .btn {
            padding: 10px 20px;
            font-size: 1.1rem;
            cursor: pointer;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.3s ease;
            display: inline-block;
        }

        .btn-primary {
            background-color: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2e86c1;
        }

        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        #statChart {
            width: 100%;
            max-width: 800px;
            margin: 40px auto;
            display: none; /* Cacher le graphique au départ */
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .content-area {
                margin-left: 220px;
                padding: 20px;
            }

            h2 {
                font-size: 26px;
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .btn {
                padding: 8px 16px;
                font-size: 1rem;
            }

            .table th, .table td {
                padding: 15px 18px;
            }
        }
    </style>
</head>
<body>

    <!-- Zone de contenu -->
    <div class="content-area">
        <h2>
            Tous les Posts
            <a href="/2A27/src/controll/process/export_posts_pdf.php" class="btn btn-primary" target="_blank">📄 Exporter en PDF</a>
            <!-- Bouton de tri -->
            <?php
                // Changer le texte du bouton en fonction du sens du tri
                if ($sort == 'asc') {
                    echo '<a href="?sort=desc" class="btn btn-primary">🔽 Trier par ID (Décroissant)</a>';
                } else {
                    echo '<a href="?sort=asc" class="btn btn-primary">🔼 Trier par ID (Croissant)</a>';
                }
            ?>
            <!-- Bouton pour afficher les statistiques -->
            <button id="showStatsBtn" class="btn btn-primary">📊 Afficher les Statistiques</button>
        </h2>

        <!-- Affichage des statistiques -->
        <div id="statChart">
            <canvas id="myChart"></canvas>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Statut</th>
                    <th>Créé le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td><?= htmlspecialchars($post['id']) ?></td>
                        <td><?= htmlspecialchars($post['titre']) ?></td>
                        <td><?= htmlspecialchars(substr($post['description'], 0, 50)) ?>...</td>
                        <td><?= htmlspecialchars($post['statut']) ?></td>
                        <td><?= htmlspecialchars($post['dateCreation']) ?></td>
                        <td>
                            <a href="/2A27/admin/posts/edit/<?= $post['id'] ?>" class="btn btn-primary">✏️ Modifier</a>
                            <a href="/2A27/admin/posts/delete/<?= $post['id'] ?>" class="btn btn-danger" onclick="return confirm('Confirmer la suppression ?')">🗑️ Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Graphique circulaire des statistiques avec Chart.js
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'pie', // Type du graphique circulaire
            data: {
                labels: ['Actifs', 'Inactifs'],
                datasets: [{
                    label: 'Statistiques des Posts',
                    data: [<?= $active_count ?>, <?= $inactive_count ?>],
                    backgroundColor: ['#2ecc71', '#e74c3c'], // Couleurs différentes pour chaque catégorie
                    borderColor: ['#27ae60', '#c0392b'],
                    borderWidth: 2,
                    hoverOffset: 4, // Ajout d'un léger effet au survol
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 16,  // Taille de la police pour plus de lisibilité
                                weight: 'bold',
                            },
                        },
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' Posts';
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',  // Fond du tooltip plus visible
                        bodyFont: {
                            size: 14,
                        },
                    }
                }
            }
        });

        // Afficher les statistiques au clic sur le bouton
        document.getElementById('showStatsBtn').addEventListener('click', function() {
            var statChart = document.getElementById('statChart');
            statChart.style.display = (statChart.style.display === 'none' || statChart.style.display === '') ? 'block' : 'none';
        });
    </script>

</body>
</html>