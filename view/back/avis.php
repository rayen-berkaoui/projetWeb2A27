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
$sqlCommentaires = "
    SELECT c.*, a.nom as avis_nom, a.prenom as avis_prenom 
    FROM commentaires c 
    LEFT JOIN avis a ON c.avis_id = a.avis_id 
    ORDER BY c.date_creation DESC";
$stmtCommentaires = $pdo->prepare($sqlCommentaires);
$stmtCommentaires->execute();

// 1) On remplit d'abord le tableau $commentaires
$commentaires = $stmtCommentaires->fetchAll(PDO::FETCH_ASSOC);

// 2) Puis on construit l'arborescence parent/enfant
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
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
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
                    <td><?= htmlspecialchars($a['nom']) ?></td>
                    <td><?= htmlspecialchars($a['prenom']) ?></td>
                    <td><?= htmlspecialchars($a['email']) ?></td>
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
        <p><strong>Nom :</strong> <span id="aff_nom"></span></p>
        <p><strong>Prénom :</strong> <span id="aff_prenom"></span></p>
        <p><strong>Note :</strong> <span id="aff_note"></span></p>
        <p><strong>Contenu :</strong> <span id="aff_contenu"></span></p>
    </div>

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
                    <?= str_repeat(' ', $level*4) ?>
                    <?= htmlspecialchars($c['contenu']) ?>
                    <?= $c['signale'] > 0 ? '<strong style="color:red;">[Signalé]</strong>' : '' ?>
                </td>
                <td><?= $c['date_creation'] ?></td>
                <td>
                    <button onclick="repondreCommentaire(<?= $c['commentaire_id'] ?>)">Répondre</button>
                    <button onclick="signalerCommentaire(<?= $c['commentaire_id'] ?>)">Signaler</button>
                    <button onclick="supprimerCommentaire(<?= $c['commentaire_id'] ?>)">Supprimer</button>
                </td>
            </tr>
        <?php
            renderComments($c['commentaire_id'], $tree, $level+1);
            endforeach;
        }
        renderComments(0, $tree);
        ?>
        </tbody>
    </table>
</div>

<script>
function afficherAvis(id) {
    fetch('../../controller/avisController.php', {
        method: 'POST',
        body: new URLSearchParams({
            action: 'get',
            avis_id: id
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('aff_nom').textContent = data.avis.nom;
            document.getElementById('aff_prenom').textContent = data.avis.prenom;
            document.getElementById('aff_note').textContent = data.avis.note;
            document.getElementById('aff_contenu').textContent = data.avis.contenu;
            document.getElementById('detailsAvis').style.display = 'block';
        } else {
            alert('Erreur : ' + data.message);
        }
    });
}

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
        fetch('../../controller/avisController.php', {
            method: 'POST',
            body: new URLSearchParams({
                action: 'supprimer',
                avis_id: id
            })
        })
        .then(response => response.text())
        .then(message => {
            alert(message);
            location.reload();
        });
    }
}

function repondreCommentaire(id) {
    const nom = prompt('Votre nom :', '');
    const prenom = prompt('Votre prénom :', '');
    const txt = prompt('Votre réponse :');
    
    if (nom && prenom && txt) {
        fetch('../../controller/commentaireController.php', {
            method: 'POST',
            body: new URLSearchParams({
                action: 'repondre',
                commentaire_id: id,
                nom: nom,
                prenom: prenom,
                contenu: txt
            })
        })
        .then(r => r.text())
        .then(msg => { 
            alert(msg); 
            location.reload(); 
        });
    }
}

function signalerCommentaire(id) {
    if (confirm("Voulez-vous signaler ce commentaire ?")) {
        fetch('../../controller/commentaireController.php', {
            method: 'POST',
            body: new URLSearchParams({
                action: 'signaler',
                commentaire_id: id
            })
        })
        .then(res => res.text())
        .then(msg => {
            alert(msg);
            location.reload();
        });
    }
}

function supprimerCommentaire(id) {
    if (confirm("Voulez-vous supprimer ce commentaire ?")) {
        fetch('../../controller/commentaireController.php', {
            method: 'POST',
            body: new URLSearchParams({
                action: 'supprimer',
                commentaire_id: id
            })
        })
        .then(res => res.text())
        .then(msg => {
            alert(msg);
            location.reload();
        });
    }
}
</script>