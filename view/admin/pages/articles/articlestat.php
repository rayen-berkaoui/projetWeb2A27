<?php
include_once 'C:\xampp\htdocs\2A27\view\admin\partials\sidebar.php';
include_once 'C:\xampp\htdocs\2A27\src\controll\routes\admin\ArticleController.php';
include_once 'C:\xampp\htdocs\2A27\src\controll\process\db.php';

$controller = new ArticleController($db);
$stats = $controller->stats();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Article Statistics</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f5f7fa;
            color: #333;
            display: flex;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .content-area {
            margin-left: 270px;
            padding: 20px;
            flex: 1;
        }

        .card {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        h2 {
            margin-bottom: 20px;
        }

        .stat-item {
            margin: 10px 0;
            font-size: 1.1rem;
        }

        .btn-back {
            margin-top: 20px;
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn-back:hover {
            background-color: #2980b9;
        }

        canvas {
            max-width: 100%;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="content-area">
    <h2>Article Statistics</h2>

    <div class="card">
        <div class="stat-item"><strong>Total Articles:</strong> <?= $stats['total_articles'] ?></div>
        <div class="stat-item"><strong>Most Used Type:</strong> <?= $stats['most_used_type'] ?></div>
        <div class="stat-item"><strong>Most Recent Article:</strong> <?= $stats['latest_article']['author'] ?> (<?= $stats['latest_article']['time_created'] ?>)</div>
    </div>

    <!-- Pie chart -->
    <canvas id="articleTypesChart"></canvas>

    <a href="/2A27/view/admin/pages/articles/list.php" class="btn-back">← Back to Article List</a>
</div>

<script>
    // Data for the pie chart
    const articleTypes = <?= json_encode($stats['types_distribution']); ?>;

    const labels = articleTypes.map(type => type.nom);
    const data = articleTypes.map(type => type.count);

    // Create the pie chart
    const ctx = document.getElementById('articleTypesChart').getContext('2d');
    const articleTypesChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                label: 'Article Types Distribution',
                data: data,
                backgroundColor: ['#FF5733', '#33FF57', '#3357FF', '#FF33A6', '#57FF33', '#33A6FF'], // You can adjust the colors
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw + ' articles';
                        }
                    }
                }
            }
        }
    });
</script>

</body>
</html>
