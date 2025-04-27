<h2>Liste des Posts</h2>
<a href="/2A27/admin/posts/create">Créer un nouveau post</a>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Titre</th>
        <th>Description</th>
        <th>Statut</th>
        <th>Date de création</th>
        <th>Commentaires</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($posts as $post): ?>
        <tr>
            <td><?= htmlspecialchars($post['id']) ?></td>
            <td><?= htmlspecialchars($post['titre']) ?></td>
            <td><?= htmlspecialchars($post['description']) ?></td>
            <td><?= htmlspecialchars($post['statut']) ?></td>
            <td><?= htmlspecialchars($post['dateCreation']) ?></td>
            <td><?= htmlspecialchars($post['commentaire_content']) ?></td>
            <td>
                <a href="/2A27/admin/posts/edit/<?= $post['id'] ?>">Modifier</a> |
                <a href="/2A27/admin/posts/delete/<?= $post['id'] ?>" onclick="return confirm('Supprimer ce post ?')">Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
