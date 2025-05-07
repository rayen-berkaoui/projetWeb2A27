<?php 
$active_menu = 'evenements';
include_once __DIR__ . '/../../layout.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="/2A27/view/assets/js/script.js" defer></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Evenement</title>
    <style>
        /* Same styles as your article form */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f5f7fa; color: #333; display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background-color: #2c3e50; color: white; position: fixed; top: 0; left: 0; height: 100%; padding-top: 20px; padding-left: 20px; }
        .content-area { margin-left: 270px; padding: 20px; flex: 1; }
        .form-group { margin-bottom: 15px; }
        .form-input, textarea { width: 100%; padding: 10px; font-size: 1rem; border: 1px solid #ccc; border-radius: 5px; }
        .form-input:focus, textarea:focus { border-color: #3498db; outline: none; }
        .btn { padding: 10px 20px; font-size: 1rem; cursor: pointer; border: none; border-radius: 5px; transition: background 0.3s; }
        .btn-primary { background-color: #3498db; color: white; }
        .btn-primary:hover { background-color: #2980b9; }
        @media (max-width: 768px) {
            .sidebar { width: 200px; }
            .content-area { margin-left: 220px; }
        }
    </style>
</head>
<body>
    <div class="content" style="margin-left: 260px; padding: 20px;">
        <h1>➕ Créer un événement</h1>

        <form method="POST" action="/2A27/admin/evenements/create" class="event-form">
            <div class="form-group">
                <label for="titre">Titre de l'événement</label>
                <input type="text" id="titre" name="titre" required class="form-control">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" required class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label for="date_evenement">Date de l'événement</label>
                <input type="date" id="date_evenement" name="date_evenement" required class="form-control">
            </div>

            <div class="form-group">
                <label for="lieu">Lieu</label>
                <input type="text" id="lieu" name="lieu" required class="form-control">
            </div>

            <div class="form-group">
                <label for="admin_email">Email de l'administrateur</label>
                <input type="email" id="admin_email" name="admin_email" required class="form-control" 
                       placeholder="Entrez l'email qui recevra la confirmation">
            </div>

            <button type="submit" class="btn btn-primary">Créer l'événement</button>
        </form>
    </div>

    <style>
        .event-form {
            max-width: 600px;
            margin: 20px 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-control {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        textarea.form-control {
            height: 120px;
        }

        .btn-primary {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #45a049;
        }
    </style>
</body>
</html>
