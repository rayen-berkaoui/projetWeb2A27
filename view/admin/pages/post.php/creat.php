<h2>Créer un nouveau post</h2>
<form method="post" action="">
    <label for="titre">Titre :</label>
    <input type="text" name="titre" id="titre" required><br>

    <label for="description">Description :</label>
    <textarea name="description" id="description" required></textarea><br>

    <label for="statut">Statut :</label>
    <select name="statut" id="statut">
        <option value="actif">Actif</option>
        <option value="inactif">Inactif</option>
    </select><br>

    <button type="submit">Créer</button>
</form>
<a href="/2A27/admin/posts">← Retour à la liste</a>
