<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'bd_avis';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

// Récupération de l'avis à modifier
$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID manquant.");
}

$sql = "SELECT * FROM avis WHERE avis_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$avis = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$avis) {
    die("Avis introuvable.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier l'Avis</title>
    <link rel="stylesheet" href="../assets/css/styleb.css">
</head>
<body>
    <div class="container">
        <?php include 'sidebar.php'; ?>
        <main class="content">
            <h2>Modifier l'Avis</h2>
            <form method="POST" action="../../controller/modifierAvisController.php">
                <input type="hidden" name="avis_id" value="<?= $avis['avis_id'] ?>">

                <label for="nom">Nom :</label>
                <input type="text" name="nom" id="nom" value="<?= htmlspecialchars($avis['nom']) ?>" required><br>

                <label for="prenom">Prénom :</label>
                <input type="text" name="prenom" id="prenom" value="<?= htmlspecialchars($avis['prenom']) ?>" required><br>

                <label for="contenu">Commentaire :</label><br>
                <textarea name="contenu" id="contenu" rows="4" required><?= htmlspecialchars($avis['contenu']) ?></textarea><br>

                <label for="note">Note :</label>
                <input type="number" name="note" id="note" min="1" max="5" value="<?= $avis['note'] ?>" required><br>

                <label for="type">Type :</label>
                <select name="type" id="type" required>
                    <option value="produit" <?= $avis['type'] == 'produit' ? 'selected' : '' ?>>Produit</option>
                    <option value="service" <?= $avis['type'] == 'service' ? 'selected' : '' ?>>Service</option>
                    <option value="site" <?= $avis['type'] == 'site' ? 'selected' : '' ?>>Site</option>
                </select><br><br>

                <button type="submit">Sauvegarder</button>
            </form>
        </main>
    </div>
</body>
</html>
