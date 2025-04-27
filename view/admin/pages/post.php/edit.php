<h2>Modifier le post</h2>
<form method="post" action="">
    <label for="titre">Titre :</label>
    <input type="text" name="titre" id="titre" value="<?= htmlspecialchars($post['titre']) ?>" required><br>

    <label for="description">Description :</label>
    <textarea name="description" id="description" required><?= htmlspecialchars($post['description']) ?></textarea><br>

    <label for="statut">Statut :</label>
    <select name="statut" id="statut">
        <option value="actif" <?= $post['statut'] === 'actif' ? 'selected' : '' ?>>Actif</option>
        <option value="inactif" <?= $post['statut'] === 'inactif' ? 'selected' : '' ?>>Inactif</option>
    </select><br>

    <button type="submit">Mettre à jour</button>
</form>
<a href="/2A27/admin/posts">← Retour à la liste</a>
