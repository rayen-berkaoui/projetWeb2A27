<?php
$active_menu = 'evenements';
include_once 'C:\xampp1\htdocs\2A27\view\admin\partials\sidebar.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une Réservation</title>

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

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1em;
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
        <h2>📅 Créer une Réservation</h2>

        <div class="form-container">
            <form method="POST" action="/2A27/admin/reservations/create">
                <div class="form-group">
                    <label for="nom_participant">Nom du participant</label>
                    <input type="text" id="nom_participant" name="nom_participant" class="form-control" required 
                           placeholder="Entrez le nom du participant">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required 
                           placeholder="Entrez l'adresse email">
                </div>

                <div class="form-group">
                    <label for="date_reservation">Date de réservation</label>
                    <input type="date" id="date_reservation" name="date_reservation" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="evenement_id">Événement</label>
                    <select id="evenement_id" name="evenement_id" class="form-control" required>
                        <option value="">Sélectionnez un événement</option>
                        <?php foreach ($evenements as $evt): ?>
                            <option value="<?= $evt['id'] ?>"><?= htmlspecialchars($evt['titre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Créer la réservation</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
