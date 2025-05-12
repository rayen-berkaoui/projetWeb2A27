<?php
$active_menu = 'evenements';
include_once 'C:\xampp\htdocs\lezm\view\admin\partials\sidebar.php';
?>

<div style="margin-left: 270px; padding: 20px;">
    <h1>✏️ Modifier la Réservation</h1>

    <!-- Start of form -->
    <form method="POST" action="/lezm/admin/reservations/update/<?= $reservation['id_reservation'] ?>">
        
        <!-- Nom du Participant input field -->
        <div class="form-group">
            <label for="nom_participant">Nom du Participant</label>
            <input type="text" name="nom_participant" id="nom_participant" value="<?= htmlspecialchars($reservation['nom_participant']) ?>" class="form-input" required>
        </div>

        <!-- Email input field -->
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($reservation['email']) ?>" class="form-input" required>
        </div>

        <!-- Date de Réservation input field -->
        <div class="form-group">
            <label for="date_reservation">Date de Réservation</label>
            <input type="date" name="date_reservation" id="date_reservation" value="<?= htmlspecialchars($reservation['date_reservation']) ?>" class="form-input" required>
        </div>

        <!-- Événement dropdown -->
        <div class="form-group">
            <label for="id_evenement">Événement</label>
            <select name="evenement_id" id="id_evenement" class="form-input" required>
                <?php foreach ($evenements as $e): ?>
                    <option value="<?= $e['id'] ?>" <?= $e['id'] == $reservation['id_evenement'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($e['titre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Submit button -->
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        </div>
    </form>
</div>

<!-- Full CSS Styling -->
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
    }

    h1 {
        font-size: 1.8rem;
        margin-bottom: 20px;
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
        resize: vertical;
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

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
</style>
