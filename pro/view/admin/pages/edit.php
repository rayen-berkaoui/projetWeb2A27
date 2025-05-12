<?php $active_menu = 'posts'; include_once 'C:\xampp\htdocs\lezm\view\admin\partials\sidebar.php'; ?>

<div class="form-wrapper">
    <h1 class="page-title">✏️ Modifier le Post</h1>

    <form method="POST" action="/lezm/admin/posts/update/<?= $post['id'] ?>" class="form-content">
        <!-- Titre -->
        <div class="form-group">
            <label for="titre">Titre</label>
            <input type="text" name="titre" id="titre" value="<?= htmlspecialchars($post['titre']) ?>" class="form-input" required>
        </div>

        <!-- Description -->
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-input" rows="6" required><?= htmlspecialchars($post['description']) ?></textarea>
        </div>

        <!-- Statut -->
        <div class="form-group">
            <label for="statut">Statut</label>
            <select name="statut" id="statut" class="form-input" required>
                <option value="">-- Choisir un statut --</option>
                <option value="actif" <?= $post['statut'] === 'actif' ? 'selected' : '' ?>>Actif</option>
                <option value="inactif" <?= $post['statut'] === 'inactif' ? 'selected' : '' ?>>Inactif</option>
            </select>
        </div>

        <!-- Bouton de mise à jour -->
        <div class="form-group">
            <button type="submit" class="btn btn-primary">✅ Mettre à jour</button>
        </div>
    </form>
</div>

<style>
    * {
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        background-color: #f5f7fa;
        margin: 0;
        padding: 0;
    }

    .form-wrapper {
        background-color: #d9fbe5; /* Vert clair */
        padding: 80px;
        border-radius: 18px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        width: calc(100% - 280px);  /* Largeur totale - sidebar */
        margin: 60px auto 60px 280px; /* haut auto bas gauche */
        min-height: 85vh;
    }

    .page-title {
        font-size: 42px;
        margin-bottom: 40px;
        text-align: center;
        color: #2e7d32;
    }

    .form-group {
        margin-bottom: 30px;
    }

    .form-input, textarea, select {
        width: 100%;
        padding: 18px;
        font-size: 1.2rem;
        border: 1px solid #aaa;
        border-radius: 10px;
        background-color: #fff;
    }

    .form-input:focus, textarea:focus, select:focus {
        border-color: #2e7d32;
        outline: none;
        background-color: #f4fff6;
    }

    .btn-primary {
        background-color: #2e7d32;
        color: white;
        padding: 16px 32px;
        font-size: 1.2rem;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #1b5e20;
    }

    @media screen and (max-width: 768px) {
        .form-wrapper {
            margin: 20px;
            padding: 40px;
            width: 100%;
        }

        .page-title {
            font-size: 30px;
        }
    }
</style>
