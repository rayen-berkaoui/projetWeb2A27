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

// Récupération des avis
$sql = "SELECT * FROM avis ORDER BY avis_id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$avis = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Récupération des commentaires associés aux avis
$sqlCommentaires = "SELECT * FROM commentaires ORDER BY date_creation DESC";
$stmtCommentaires = $pdo->prepare($sqlCommentaires);
$stmtCommentaires->execute();

// 1) On remplit d’abord le tableau $commentaires
$commentaires = $stmtCommentaires->fetchAll(PDO::FETCH_ASSOC);

// 2) Puis on construit l’arborescence parent/enfant
$tree = [];
foreach ($commentaires as $c) {
    // si tu as bien ajouté parent_id à ta table
    $parent = $c['parent_id'] ?? 0;
    $tree[$parent][] = $c;
}

?>

<div class="avis-container">
    <h2>Liste des Avis</h2>
    <table class="avis-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Utilisateur</th>
                <th>Contenu</th>
                <th>Note</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($avis as $a): ?>
                <tr>
                    <td><?= htmlspecialchars($a['avis_id']) ?></td>
                    <td><?= htmlspecialchars($a['user_id']) ?></td>
                    <td><?= htmlspecialchars($a['contenu']) ?></td>
                    <td><?= htmlspecialchars($a['note']) ?>/5</td>
                    <td><?= htmlspecialchars($a['date_creation']) ?></td>
                    <td>
                        <button onclick="afficherAvis(<?= $a['avis_id'] ?>)">Afficher</button>
                        <button onclick="modifierAvis(<?= $a['avis_id'] ?>)">Modifier</button>
                        <button onclick="supprimerAvis(<?= $a['avis_id'] ?>)">Supprimer</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="detailsAvis" style="display:none; margin-top: 20px; border: 1px solid #ccc; padding: 10px;">
    <h3>Détails de l'avis sélectionné :</h3>
    <p><strong>ID Utilisateur :</strong> <span id="aff_user_id"></span></p>
    <p><strong>Note :</strong> <span id="aff_note"></span></p>
    <p><strong>Contenu :</strong> <span id="aff_contenu"></span></p>


</div>

<script>
function modifierAvis(id) {
    const contenu = prompt('Entrez le nouveau contenu de l\'avis :');
    const note = prompt('Entrez la nouvelle note (1 à 5) :');

    if (contenu && note >= 1 && note <= 5) {
        fetch('../../controller/avisController.php', {
            method: 'POST',
            body: new URLSearchParams({
                action: 'modifier',
                avis_id: id,
                contenu: contenu,
                note: note
            })
        })
        .then(response => response.text())
        .then(message => {
            alert(message);
            location.reload();
        });
    } else {
        alert('Contenu ou note invalides.');
    }
}

function supprimerAvis(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet avis ?')) {
        fetch('../../controller/avisController.php?action=supprimer&id=' + id)
        .then(response => response.text())
        .then(message => {
            alert(message);
            location.reload();
        });
    }
}
function redirigerVersFormulaire(userId, contenu, note) {
    const url = `../../view/front/formulaire.php?user_id=${userId}&contenu=${encodeURIComponent(contenu)}&note=${note}`;
    window.location.href = url;
}
</script>
<h2>Commentaires des Clients</h2>
<table class="avis-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Avis lié</th>
            <th>Contenu</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php function renderComments($parent, $tree, $level=0) {
        if (empty($tree[$parent])) return;
        foreach ($tree[$parent] as $c):
    ?>
      <tr style="background:<?= $level ? '#fafafa' : 'transparent' ?>;">
        <td><?= $c['commentaire_id'] ?></td>
        <td><?= $c['avis_id'] ?></td>
        <td>
          <?= str_repeat('&nbsp;', $level*4) ?>
          <?= htmlspecialchars($c['contenu']) ?>
          <?= $c['signaled'] ? '<strong style="color:red;">[Signalé]</strong>' : '' ?>
        </td>
        <td><?= $c['date_creation'] ?></td>
        <td>
          <button onclick="repondreCommentaire(<?= $c['commentaire_id'] ?>)">Répondre</button>
          <button onclick="signalerCommentaire(<?= $c['commentaire_id'] ?>)">Signaler</button>
          <button onclick="supprimerCommentaire(<?= $c['commentaire_id'] ?>)">Supprimer</button>
        </td>
      </tr>
    <?php
        // appels récursifs pour les réponses
        renderComments($c['commentaire_id'], $tree, $level+1);
        endforeach;
    }
    renderComments(0, $tree);
    ?>
  </tbody>
</table>
<script>
function repondreCommentaire(id) {
  const user = prompt('Votre ID (ou laissez 0 pour admin) :', '0');
  const txt  = prompt('Votre réponse :');
  if (txt) {
    fetch('../../controller/commentaireController.php', {
      method: 'POST',
      body: new URLSearchParams({
        action: 'repondre',
        commentaire_id: id,
        user_id: user,
        reponse: txt
      })
    })
    .then(r => r.text()).then(msg => { alert(msg); location.reload(); });
  }
}

function signalerCommentaire(id) {
    if (confirm("Voulez-vous signaler ce commentaire ?")) {
        fetch('../../controller/commentaireController.php?action=signaler&id=' + id)
        .then(res => res.text())
        .then(msg => {
            alert(msg);
            location.reload();
        });
    }
}

function supprimerCommentaire(id) {
    if (confirm("Voulez-vous supprimer ce commentaire ?")) {
        fetch('../../controller/commentaireController.php?action=supprimer&id=' + id)
        .then(res => res.text())
        .then(msg => {
            alert(msg);
            location.reload();
        });
    }
}
</script>

