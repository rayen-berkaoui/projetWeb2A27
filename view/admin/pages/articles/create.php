<?php $active_menu = 'articles'; include_once 'C:\xampp\htdocs\2A27\view\admin\partials\sidebar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
<script src="/2A27/view/assets/js/script.js" defer></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Article</title>

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

        /* Sidebar Styles */
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

        /* Content Area Styles */
        .content-area {
            margin-left: 270px; /* Adjusted to avoid overlap with sidebar */
            padding: 20px;
            flex: 1;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 15px;
        }

        .form-input, textarea {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-input:focus, textarea:focus {
            border-color: #3498db;
            outline: none;
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

        /* Page Content Styles */
        .page-content {
            padding: 20px;
            flex: 1;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .content-area {
                margin-left: 220px; /* Adjusted for smaller screens */
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar (included) -->
    <?php include_once 'C:\xampp\htdocs\2A27\view\admin\partials\sidebar.php'; ?>

    <!-- Content Area -->
    <div class="content-area">
        <h2>Create New Article</h2>

        <form method="POST" action="/2A27/admin/articles/create">
            <div class="form-group">
                <label for="type">Type</label>
                <input type="text" name="type" id="type" class="form-input" required placeholder="Enter article type">
            </div>

            <div class="form-group">
                <label for="author">Author</label>
                <input type="text" name="author" id="author" class="form-input" required placeholder="Enter author name">
            </div>

            <div class="form-group">
                <label for="content">Content</label>
                <textarea name="content" id="content" required placeholder="Enter article content"></textarea>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Create Article</button>
            </div>
        </form>
    </div>

</body>
</html>
