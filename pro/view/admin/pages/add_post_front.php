<h1>Ajouter un Post (Front Office)</h1>

<form action="index.php?page=add_post_front" method="post">
    <label for="title">Titre :</label><br>
    <input type="text" id="title" name="title" required><br><br>

    <label for="content">Contenu :</label><br>
    <textarea id="content" name="content" rows="5" cols="30" required></textarea><br><br>

    <input type="submit" value="Ajouter">
</form>

<a href="index.php?page=list_post_front">Retour à la liste</a>
