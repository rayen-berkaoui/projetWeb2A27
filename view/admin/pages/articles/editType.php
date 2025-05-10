<?php $active_menu = 'articles'; include_once 'C:\xampp\htdocs\2A27\view\admin\partials\sidebar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Type</title>

    <style>
        /* Global styles */
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

        .form-input {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-input:focus {
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

        /* Responsive Layout */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .content-area {
                margin-left: 220px; /* Adjusted for smaller screens */
            }
        }
    </style>

    <!-- JavaScript to prevent typing numbers or special characters -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const typeNameInput = document.getElementById('nom');
            
            typeNameInput.addEventListener('keypress', function (event) {
                const char = String.fromCharCode(event.which);
                const regex = /^[A-Za-z\s]+$/; // Only allows letters and spaces
                if (!regex.test(char)) {
                    event.preventDefault(); // Prevent the input if it doesn't match the regex
                }
            });
        });
    </script>
</head>
<body>
    <!-- Sidebar (included) -->

    <!-- Content Area -->
    <div class="content-area">
        <h2>Edit Type</h2>

        <form method="POST" action="/2A27/admin/articles/updateType/<?= $type['id'] ?>">
            <div class="form-group">
                <label for="nom">Type Name</label>
                <input type="text" name="nom" id="nom" class="form-input" value="<?= htmlspecialchars($type['nom']) ?>" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Update Type</button>
            </div>
        </form>
    </div>
</body>
</html>
