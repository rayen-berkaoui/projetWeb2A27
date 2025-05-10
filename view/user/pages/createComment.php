<?php $active_menu = 'commentaires'; include_once 'C:\xampp\htdocs\2A27\view\admin\partials\sidebar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="/2A27/view/assets/js/script.js" defer></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Comment</title>

    <style>
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

        .form-group {
            margin-bottom: 15px;
        }

        .form-input,
        textarea {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-input:focus,
        textarea:focus {
            border-color: #3498db;
            outline: none;
        }

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

    <div class="content-area">
        <h2>Create New Comment</h2>

        <form method="POST" action="/2A27/admin/commentaires/store">
            <div class="form-group">
                <label for="post_id">Post ID</label>
                <input type="number" name="post_id" id="post_id" class="form-input" required placeholder="Enter post ID">
            </div>

            <div class="form-group">
                <label for="author">Author</label>
                <input type="text" name="author" id="author" class="form-input" required placeholder="Enter author name">
            </div>

            <div class="form-group">
                <label for="content">Content</label>
                <textarea name="content" id="content" rows="5" class="form-input" required placeholder="Enter comment content"></textarea>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Create Comment</button>
            </div>
        </form>
    </div>

</body>
</html>