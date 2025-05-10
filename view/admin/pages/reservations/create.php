<?php $active_menu = 'evenements'; include_once 'C:\xampp\htdocs\2A27\view\admin\partials\sidebar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="/2A27/view/assets/js/script.js" defer></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Reservation</title>
    <style>
        /* Same consistent style */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f5f7fa; color: #333; display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background-color: #2c3e50; color: white; position: fixed; top: 0; left: 0; height: 100%; padding-top: 20px; padding-left: 20px; }
        .content-area { margin-left: 270px; padding: 20px; flex: 1; }
        .form-group { margin-bottom: 15px; }
        .form-input, textarea, select { width: 100%; padding: 10px; font-size: 1rem; border: 1px solid #ccc; border-radius: 5px; }
        .form-input:focus, textarea:focus, select:focus { border-color: #3498db; outline: none; }
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
    <div class="content-area">
        <h2>Create New Reservation</h2>
        <form method="POST" action="/2A27/admin/reservations/create">
            <div class="form-group">
                <label for="nom_participant">Participant Name</label>
                <input type="text" name="nom_participant" id="nom_participant" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="email">Participant Email</label>
                <input type="email" name="email" id="email" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="date_reservation">Reservation Date</label>
                <input type="date" name="date_reservation" id="date_reservation" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="evenement_id">Event</label>
                <select name="evenement_id" id="evenement_id" class="form-input" required>
                    <option value="">-- Choisir un événement --</option>
                    <?php foreach ($evenements as $event): ?>
                        <option value="<?= $event['id'] ?>"><?= htmlspecialchars($event['titre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Create Reservation</button>
            </div>
        </form>
    </div>
</body>
</html>
