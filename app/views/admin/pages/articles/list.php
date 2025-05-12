<?php $active_menu = 'articles'; include_once 'C:\xampp\htdocs\lezm\view\admin\partials\sidebar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles List</title>

    <!-- Inline CSS -->
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: #333;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Layout */
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            padding-top: 20px;
            padding-left: 20px;
        }

        .content-area {
            margin-left: 270px; /* Adjusted to avoid overlap with sidebar */
            padding: 20px;
            flex: 1;
        }

        /* Table Styles */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th, .table td {
            padding: 10px;
            text-align: left;
        }

        .table th {
            background-color: #34495e;
            color: white;
        }

        .table tr:nth-child(even) {
            background-color: #f5f7fa;
        }

        /* Button Styles */
        .btn {
            padding: 10px 20px;
            font-size: 1rem;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .btn-primary {
            background-color: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .content-area {
                margin-left: 220px; /* Adjusted for smaller screen */
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar (included) -->
    <?php include_once 'C:\xampp\htdocs\lezm\view\admin\partials\sidebar.php'; ?>

    <!-- Content Area -->
    <div class="content-area">
        <h2>All Articles</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Author</th>
                    <th>Content</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $article): ?>
                    <tr>
                        <!-- Displaying the ID -->
                        <td><?= htmlspecialchars($article['id']) ?></td>
                        <td><?= htmlspecialchars($article['type']) ?></td>
                        <td><?= htmlspecialchars($article['author']) ?></td>
                        <!-- Displaying content or a snippet -->
                        <td><?= htmlspecialchars(substr($article['content'], 0, 50)) ?>...</td>
                        <td><?= htmlspecialchars($article['time_created']) ?></td>
                        <td class="actions">
                            <a href="/lezm/admin/articles/edit/<?= $article['id'] ?>" class="btn btn-primary">Edit</a> |
                            <a href="/lezm/admin/articles/delete/<?= $article['id'] ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
