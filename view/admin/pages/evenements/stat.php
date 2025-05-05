<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'db_html'; // Remplace par le nom réel de ta base
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Récupération des données pour les graphiques
$sql = "SELECT evenement.titre, COUNT(reservations.id_reservation) AS nb_reservations
        FROM evenement
        LEFT JOIN reservations ON evenement.id = reservations.id_evenement
        GROUP BY evenement.id";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des données : " . $e->getMessage());
}

// Transformation des données pour le graphique
$titres = [];
$nb_reservations = [];
foreach ($resultats as $row) {
    $titres[] = $row['titre'];
    $nb_reservations[] = (int) $row['nb_reservations'];
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques des réservations par événement</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #ecf0f1;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            width: 90%;
            margin: 50px auto;
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            color: #2980b9;
            font-size: 28px;
            margin-bottom: 20px;
        }
        canvas {
            display: block;
            margin: 0 auto;
        }
        .info-box {
            background-color: #f0f8ff;
            border-left: 4px solid #2980b9;
            padding: 20px;
            margin-bottom: 20px;
            font-size: 18px;
            color: #34495e;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Statistiques des réservations par événement</h2>

       
        
        <!-- Graphique -->
        <canvas id="chart"></canvas>

        <script>
            // Données pour le graphique
            var ctx = document.getElementById('chart').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($titres); ?>, // Titres des événements
                    datasets: [{
                        label: 'Nombre de réservations',
                        data: <?php echo json_encode($nb_reservations); ?>, // Nombre de réservations
                        backgroundColor: [
                            'rgba(41, 128, 185, 0.6)', // Bleu
                            'rgba(39, 174, 96, 0.6)', // Vert
                            'rgba(231, 76, 60, 0.6)', // Rouge
                            'rgba(241, 196, 15, 0.6)', // Jaune
                            'rgba(142, 68, 173, 0.6)', // Violet
                            'rgba(26, 188, 156, 0.6)', // Turquoise
                            'rgba(52, 152, 219, 0.6)'  // Bleu clair
                        ], // Couleurs des barres
                        borderColor: [
                            'rgba(41, 128, 185, 1)', 
                            'rgba(39, 174, 96, 1)', 
                            'rgba(231, 76, 60, 1)', 
                            'rgba(241, 196, 15, 1)', 
                            'rgba(142, 68, 173, 1)', 
                            'rgba(26, 188, 156, 1)', 
                            'rgba(52, 152, 219, 1)'
                        ], // Bordures des barres
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Nombre de réservations'
                            },
                            ticks: {
                                stepSize: 1
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Événements'
                            },
                            ticks: {
                                autoSkip: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false // Masquer la légende si pas nécessaire
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.raw + ' réservations';
                                }
                            }
                        }
                    }
                }
            });
        </script>

    </div>

</body>
</html>
