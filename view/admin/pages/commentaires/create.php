<?php $active_menu = 'commentaires'; include_once 'C:\xampp\htdocs\lezm\view\admin\partials\sidebar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="/lezm/view/assets/js/script.js" defer></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Commentaire</title>
    <style>
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
    </style>
</head>
<body>
    <div class="content-area">
        <h2>Create New Commentaire</h2>
        <form method="POST" action="/lezm/admin/commentaires/create">
            <div class="form-group">
                <label for="contenu">Content</label>
                <textarea name="contenu" id="contenu" class="form-input" required></textarea>
            </div>

            <div class="form-group">
                <label for="auteur">Author</label>
                <input type="text" name="auteur" id="auteur" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" name="date" id="date" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="avis_id">Avis</label>
                <select name="avis_id" id="avis_id" class="form-input" required>
                    <?php foreach ($avisList as $avis): ?>
                        <option value="<?= htmlspecialchars($avis['id_avis']) ?>"><?= htmlspecialchars($avis['titre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Create Commentaire</button>
            </div>
        </form>
    </div>
</body>
</html>
