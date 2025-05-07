<?php $active_menu = 'evenements'; include_once 'C:\xampp1\htdocs\2A27\view\admin\partials\sidebar.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Événement</title>

    <style>
        :root {
            --planetary-color: #334EAC;
            --venus-color: #BAD6EB;
            --meteor-color: #F7F2EB;
            --galaxy-color: #081F5C;
            --milky-way-color: #FFF9F0;
            --universe-color: #7096D1;
            --sky-color: #D0E3FF;
            --text-color: #081F5C;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--milky-way-color);
            color: var(--text-color);
            min-height: 100vh;
        }

        .content-area {
            margin-left: 270px;
            padding: 30px;
        }

        h2 {
            color: var(--galaxy-color);
            font-size: 30px;
            margin-bottom: 30px;
            border-left: 5px solid var(--planetary-color);
            padding-left: 15px;
        }

        .form-container {
            background-color: var(--sky-color);
            padding: 30px;
            border-radius: 12px;
            max-width: 800px;
            margin: 0 auto;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 25px;
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

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }

        .btn-primary {
            background-color: var(--planetary-color);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary:hover {
            background-color: var(--galaxy-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(51, 78, 172, 0.2);
        }

        @media (max-width: 768px) {
            .content-area {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="content-area">
        <h2>📅 Créer un Événement</h2>

        <div class="form-container">
            <form method="POST" action="/2A27/admin/evenements/create">
                <div class="form-group">
                    <label for="titre">Titre de l'événement</label>
                    <input type="text" id="titre" name="titre" class="form-control" required 
                           placeholder="Entrez le titre de l'événement">
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" required 
                              placeholder="Décrivez l'événement..."></textarea>
                </div>

                <div class="form-group">
                    <label for="date">Date de l'événement</label>
                    <input type="date" id="date_evenement" name="date_evenement" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="lieu">Lieu</label>
                    <input type="text" id="lieu" name="lieu" class="form-control" required 
                           placeholder="Lieu de l'événement">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Créer l'événement</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
