<?php $active_menu = 'commentaires'; include_once 'C:\xampp\htdocs\2A27\view\admin\partials\sidebar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="/2A27/view/assets/js/script.js" defer></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Commentaires</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f5f7fa; color: #333; display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background-color: #2c3e50; color: white; position: fixed; top: 0; left: 0; height: 100%; padding-top: 20px; padding-left: 20px; }
        .content-area { margin-left: 270px; padding: 20px; flex: 1; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #3498db; color: white; }
        .btn { padding: 5px 10px; cursor: pointer; border: none; border-radius: 5px; transition: background 0.3s; }
        .btn-edit { background-color: #3498db; color: white; }
        .btn-edit:hover { background-color: #2980b9; }
        .btn-delete { background-color: #e74c3c; color: white; }
        .btn-delete:hover { background-color: #c0392b; }
    </style>
</head>
<body>
    <div class="content-area">
        <h2>List of Commentaires</h2>
        <table>
            <thead>
                <tr>
                    <th>Content</th>
                    <th>Author</th>
                    <th>Avis</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($commentairesList) && !empty($commentairesList)): ?>
                    <?php foreach ($commentairesList as $commentaire): ?>
                        <tr>
                            <td><?= htmlspecialchars($commentaire['contenu']) ?></td>
                            <td><?= htmlspecialchars($commentaire['auteur']) ?></td>
                            <td><?= htmlspecialchars($commentaire['avis_contenu']) ?></td>
                            <td>
                                <a href="/2A27/admin/commentaires/edit/<?= htmlspecialchars($commentaire['commentaire_id']) ?>" class="btn btn-edit">Edit</a>
                                <a href="/2A27/admin/commentaires/delete/<?= htmlspecialchars($commentaire['commentaire_id']) ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this commentaire?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No commentaires found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
