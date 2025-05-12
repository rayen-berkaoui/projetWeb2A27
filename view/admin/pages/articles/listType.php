<?php
$active_menu = 'articles';
include_once 'C:\xampp\htdocs\lezm\view\admin\partials\sidebar.php';

// Fetch all types from the database
$query = "SELECT * FROM type";
$stmt = $this->db->prepare($query);
$stmt->execute();
$types = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="/lezm/view/assets/js/script.js" defer></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Article Types</title>

    <!-- Inline CSS -->
    <style>
        /* Base styles */
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
            margin-left: 270px;
            padding: 20px;
            flex: 1;
        }

        h2 {
            margin-bottom: 20px;
        }

        /* Type list styles */
        .type-list {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .type-item {
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .type-item:last-child {
            border-bottom: none;
        }

        /* Button styles */
        .btn {
            padding: 5px 15px;
            font-size: 1rem;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
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

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .content-area {
                margin-left: 220px;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar included above -->

    <div class="content-area">
        <h2>Article Types</h2>

        <div class="type-list">
            <?php if (count($types) > 0): ?>
                <?php foreach ($types as $type): ?>
                    <div class="type-item">
                        <span><?= htmlspecialchars($type['nom']) ?></span>
                        
                        <!-- Edit Button -->
                        <a href="/lezm/admin/articles/editType/<?= $type['id'] ?>" class="btn btn-primary">Edit</a>
                        
                        <!-- Delete Button with confirmation -->
                        <form action="/lezm/admin/articles/deleteType/<?= $type['id'] ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this type?');" style="display: inline;">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No types found.</p>
            <?php endif; ?>
        </div>

        <a href="/lezm/admin/articles/createType" class="btn btn-primary">Create New Type</a>
    </div>

</body>
</html>
