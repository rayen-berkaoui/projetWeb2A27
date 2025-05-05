<?php
$active_menu = 'evenements';
include_once 'C:\xampp1\htdocs\2A27\view\admin\partials\sidebar.php';
?>

<div style="margin-left: 270px; padding: 20px;">
    <h1>✏️ Modifier l'Événement</h1>

    <form action="/2A27/admin/evenements/update/<?= $evenement['id'] ?>" method="POST">

        <!-- Titre input field -->
        <div class="form-group">
            <label for="titre">Titre</label>
            <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($evenement['titre']) ?>" class="form-input" required>
        </div>

        <!-- Description textarea -->
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="5" class="form-input" required><?= htmlspecialchars($evenement['description']) ?></textarea>
        </div>

        <!-- Date input field -->
        <div class="form-group">
            <label for="date">Date</label>
            <input type="date" id="date" name="date" value="<?= htmlspecialchars($evenement['date']) ?>" class="form-input" required>
        </div>

        <!-- Lieu input field -->
        <div class="form-group">
            <label for="lieu">Lieu</label>
            <input type="text" id="lieu" name="lieu" value="<?= htmlspecialchars($evenement['lieu']) ?>" class="form-input" required>
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
