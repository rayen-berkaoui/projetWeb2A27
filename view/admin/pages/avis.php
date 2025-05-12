<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Inclure les stats des commentaires
$filePath = __DIR__ . '/../../controll/process/database.php';
if (file_exists($filePath)) {
    require_once($filePath);
} else {
    error_log("Fichier commentStatsController.php introuvable.");
    echo "Fichier commentStatsController.php introuvable.";
    exit;
}

// Connexion à la base de données
require_once(__DIR__ . '/../../config/database.php');

// Inclure le contrôleur des commentaires
require_once '../../controller/commentaireController.php';

// Déterminer l'ordre de tri
$orderBy = "avis_id DESC"; // Par défaut
if (isset($_GET['tri'])) {
    switch ($_GET['tri']) {
        case 'note_desc':
            $orderBy = "note DESC";
            break;
        case 'note_asc':
            $orderBy = "note ASC";
            break;
        case 'date_desc':
            $orderBy = "date_creation DESC";
            break;
        case 'date_asc':
            $orderBy = "date_creation ASC";
            break;
        case 'type':
            $orderBy = "type_avis ASC";
            break;
    }
}

// Récupérer les avis, commentaires et statistiques des notes
try {
    $pdo = Database::getInstance()->getConnection();
    
    // Récupération des avis avec le nombre de commentaires
    $stmt = $pdo->prepare("
        SELECT a.*, 
               COUNT(DISTINCT c.commentaire_id) as nb_commentaires
        FROM avis a
        LEFT JOIN commentaires c ON a.avis_id = c.avis_id
        GROUP BY a.avis_id
        ORDER BY $orderBy
    ");
    $stmt->execute();
    $avis = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    error_log("Nombre d'avis récupérés : " . count($avis));

    // Récupération des commentaires
    $stmt = $pdo->prepare("
        SELECT c.*, a.nom as auteur_avis
        FROM commentaires c
        INNER JOIN avis a ON c.avis_id = a.avis_id
        ORDER BY c.date_creation DESC
    ");
    $stmt->execute();
    $commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupération des statistiques des notes
    $stmt = $pdo->prepare("
        SELECT note, COUNT(*) as count
        FROM avis
        GROUP BY note
        ORDER BY note
    ");
    $stmt->execute();
    $noteStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Préparer les données pour le graphique
    $labels = ['1', '2', '3', '4', '5'];
    $data = [0, 0, 0, 0, 0];
    if (!empty($noteStats)) {
        foreach ($noteStats as $stat) {
            if ($stat['note'] >= 1 && $stat['note'] <= 5) {
                $data[$stat['note'] - 1] = $stat['count'];
            }
        }
    }
} catch(PDOException $e) {
    error_log("Erreur SQL dans dashboard.php : " . $e->getMessage());
    echo "<div class='notification error'>Erreur de base de données : " . htmlspecialchars($e->getMessage()) . "</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>GreenMind Back-Office</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styleb.css">
    <link rel="stylesheet" href="../assets/css/avis.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script src="../assets/js/scriptb.js" defer></script>
</head>
<body>
<div class="container">
    <?php include 'sidebar.php'; ?>
    <main id="main-content" class="content">
        <h2>Gestion des Avis</h2>

        <!-- Boîte de recherche -->
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Rechercher...">
            <select id="searchType">
                <option value="nom">Par nom</option>
                <option value="email">Par email</option>
                <option value="avis_id">Par ID</option>
            </select>
            <button onclick="searchAvis()">Rechercher</button>
        </div>

        <!-- Options de tri -->
        <div class="sort-container">
            <div class="sort-box">
                <label>Trier par :</label>
                <button onclick="sortAvis('date_desc')" class="sort-btn">Plus récent</button>
                <button onclick="sortAvis('date_asc')" class="sort-btn">Plus ancien</button>
                <button onclick="sortAvis('note_desc')" class="sort-btn">Note ⬇</button>
                <button onclick="sortAvis('note_asc')" class="sort-btn">Note ⬆</button>
            </div>
        </div>

        <!-- Tableau principal des avis -->
        <div class="table-container">
            <table class="avis-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Avis</th>
                        <th>Note</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Commentaires</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="avisTableBody">
                <?php if (empty($avis)): ?>
                    <tr><td colspan="10">Aucun avis trouvé.</td></tr>
                <?php else: ?>
                    <?php foreach ($avis as $a): ?>
                        <tr data-avis-id="<?= $a['avis_id'] ?>">
                            <td><?= $a['avis_id'] ?></td>
                            <td><?= htmlspecialchars($a['nom']) ?></td>
                            <td><?= htmlspecialchars($a['prenom']) ?></td>
                            <td><?= htmlspecialchars($a['email']) ?></td>
                            <td><?= htmlspecialchars($a['contenu']) ?></td>
                            <td><?= $a['note'] ?>/5</td>
                            <td><?= $a['date_creation'] ?></td>
                            <td>
                                <span class="status-badge <?= $a['is_visible'] ? 'visible' : 'hidden' ?>">
                                    <?= $a['is_visible'] ? 'Visible' : 'Masqué' ?>
                                </span>
                            </td>
                            <td><?= $a['nb_commentaires'] ?></td>
                            <td class="actions">
                                <button class="btn-visibility" onclick="toggleAvisVisibility(<?= $a['avis_id'] ?>)">
                                    <i class="fas <?= $a['is_visible'] ? 'fa-eye-slash' : 'fa-eye' ?>"></i>
                                    <?= $a['is_visible'] ? 'Masquer' : 'Afficher' ?>
                                </button>
                                <button onclick="modifierAvis(<?= $a['avis_id'] ?>)" class="btn-edit">
                                    <i class="fas fa-edit"></i> Modifier
                                </button>
                                <button onclick="supprimerAvis(<?= $a['avis_id'] ?>)" class="btn-delete">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                                <button onclick="signalerAvis(<?= $a['avis_id'] ?>)" class="btn-report">
                                    <i class="fas fa-flag"></i> Signaler
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Section des commentaires -->
        <div class="comments-section">
            <h3>Gestion des Commentaires</h3>
            <div class="table-container">
                <table class="comments-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Avis ID</th>
                            <th>Auteur</th>
                            <th>Commentaire</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="commentsTableBody">
                        <?php if (empty($commentaires)): ?>
                            <tr><td colspan="7">Aucun commentaire trouvé.</td></tr>
                        <?php else: ?>
                            <?php foreach ($commentaires as $commentaire): ?>
                                <tr data-comment-id="<?= $commentaire['commentaire_id'] ?>">
                                    <td><?= $commentaire['commentaire_id'] ?></td>
                                    <td><?= $commentaire['avis_id'] ?></td>
                                    <td><?= htmlspecialchars($commentaire['auteur_avis']) ?></td>
                                    <td>
                                        <?= htmlspecialchars($commentaire['contenu']) ?>
                                        <?= $commentaire['signale'] > 0 ? '<strong style="color:red;">[Signalé]</strong>' : '' ?>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($commentaire['date_creation'])) ?></td>
                                    <td>
                                        <span class="status-badge <?= $commentaire['is_visible'] ? 'visible' : 'hidden' ?>">
                                            <?= $commentaire['is_visible'] ? 'Visible' : 'Masqué' ?>
                                        </span>
                                    </td>
                                    <td class="actions">
                                        <button class="btn-visibility" onclick="toggleCommentVisibility(<?= $commentaire['commentaire_id'] ?>, <?= $commentaire['is_visible'] ?>)">
                                            <i class="fas <?= $commentaire['is_visible'] ? 'fa-eye-slash' : 'fa-eye' ?>"></i>
                                            <?= $commentaire['is_visible'] ? 'Masquer' : 'Afficher' ?>
                                        </button>
                                        <button class="btn-delete" onclick="supprimerCommentaire(<?= $commentaire['commentaire_id'] ?>)">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section des statistiques des notes -->
        <div class="stats-section">
            <h3>Statistiques des Notes</h3>
            <div class="stats-container">
                <canvas id="noteChart" width="400" height="200" data-stats='<?php echo json_encode($data); ?>'></canvas>
                <table class="stats-table">
                    <thead>
                        <tr>
                            <th>Note</th>
                            <th>Nombre d'avis</th>
                            <th>Pourcentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $totalAvis = array_sum(array_column($noteStats, 'count'));
                        foreach ($noteStats as $stat):
                            $percentage = $totalAvis > 0 ? round(($stat['count'] / $totalAvis) * 100, 1) : 0;
                        ?>
                            <tr>
                                <td><?= $stat['note'] ?>/5</td>
                                <td><?= $stat['count'] ?></td>
                                <td><?= $percentage ?>%</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Style CSS pour le dashboard -->
<style>
/* Style général du tableau */
.table-container {
    margin: 20px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.avis-table, .comments-table, .stats-table {
    width: 100%;
    border-collapse: collapse;
}

.avis-table th, .comments-table th, .stats-table th {
    background-color: #333;
    color: white;
    padding: 15px;
    text-align: left;
}

.avis-table td, .comments-table td, .stats-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #eee;
}

/* Style des boutons d'action */
.actions {
    display: flex;
    gap: 5px;
    justify-content: flex-start;
}

.actions button {
    padding: 6px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.9em;
    transition: all 0.3s ease;
}

/* Style spécifique pour chaque type de bouton */
.btn-edit {
    background-color: #28a745;
    color: white;
}

.btn-edit:hover {
    background-color: #218838;
}

.btn-delete {
    background-color: #dc3545;
    color: white;
}

.btn-delete:hover {
    background-color: #c82333;
}

.btn-report {
    background-color: #ffc107;
    color: #000;
}

.btn-report:hover {
    background-color: #e0a800;
}

.btn-visibility {
    background-color: #007bff;
    color: white;
}

.btn-visibility:hover {
    background-color: #0056b3;
}

/* Style des badges de statut */
.status-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.9em;
}

.status-badge.visible {
    background-color: #28a745;
    color: white;
}

.status-badge.hidden {
    background-color: #dc3545;
    color: white;
}

/* Style de la boîte de recherche et de tri */
.search-box, .sort-box {
    background-color: #fff;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    margin: 20px;
}

.search-box input,
.search-box select,
.sort-box select {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-right: 10px;
}

.search-box button {
    background-color: #333;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.search-box button:hover {
    background-color: #444;
}

/* Style pour les lignes du tableau en alternance */
.avis-table tr:nth-child(even), .comments-table tr:nth-child(even), .stats-table tr:nth-child(even) {
    background-color: #f8f9fa;
}

.avis-table tr:hover, .comments-table tr:hover, .stats-table tr:hover {
    background-color: #f2f2f2;
}

/* Style des notifications */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1000;
    padding: 10px 20px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    font-size: 1em;
    color: white;
}

.notification.success {
    background-color: #28a745;
}

.notification.error {
    background-color: #dc3545;
}

/* Style pour la section des statistiques */
.stats-section {
    margin: 20px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    padding: 20px;
}

.stats-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    align-items: flex-start;
}

#noteChart {
    max-width: 500px;
    flex: 1;
}

.stats-table {
    flex: 1;
    min-width: 300px;
}
</style>

<!-- Script JavaScript pour la gestion des actions -->
<script>
// Fonction pour afficher les notifications
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}

// Gestion de la suppression des commentaires
function supprimerCommentaire(commentId) {
    if (confirm('Voulez-vous vraiment supprimer ce commentaire ?')) {
        $.ajax({
            url: '../../controller/commentaireController.php',
            method: 'POST',
            data: {
                action: 'supprimer',
                commentaire_id: commentId
            },
            success: function(response) {
                if (response === 'OK') {
                    showNotification('Commentaire supprimé avec succès', 'success');
                    refreshComments();
                } else {
                    showNotification('Erreur lors de la suppression', 'error');
                }
            },
            error: function(xhr) {
                showNotification('Erreur réseau : ' + (xhr.responseText || 'Erreur serveur'), 'error');
            }
        });
    }
}

// Gestion de la visibilité des commentaires
function toggleCommentVisibility(commentId, currentState) {
    $.ajax({
        url: '../../controller/commentaireController.php',
        method: 'POST',
        data: {
            action: 'toggle_visibility',
            commentaire_id: commentId
        },
        success: function(response) {
            try {
                const result = JSON.parse(response);
                if (result.success) {
                    const row = document.querySelector(`tr[data-comment-id="${commentId}"]`);
                    const statusBadge = row.querySelector('.status-badge');
                    const button = row.querySelector('.btn-visibility');
                    
                    if (result.newState) {
                        statusBadge.textContent = 'Visible';
                        statusBadge.classList.remove('hidden');
                        statusBadge.classList.add('visible');
                        button.innerHTML = '<i class="fas fa-eye-slash"></i> Masquer';
                    } else {
                        statusBadge.textContent = 'Masqué';
                        statusBadge.classList.remove('visible');
                        statusBadge.classList.add('hidden');
                        button.innerHTML = '<i class="fas fa-eye"></i> Afficher';
                    }

                    showNotification(result.message, 'success');
                } else {
                    showNotification(result.message || 'Erreur lors de la modification', 'error');
                }
            } catch (e) {
                console.error('Erreur:', e);
                showNotification('Erreur lors de la modification du statut', 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', error);
            showNotification('Erreur de connexion au serveur', 'error');
        }
    });
}

// Gestion de la visibilité des avis
function toggleAvisVisibility(avisId) {
    $.ajax({
        url: '../../controller/avisController.php',
        method: 'POST',
        data: {
            action: 'toggle_visibility',
            avis_id: avisId
        },
        success: function(response) {
            try {
                const result = JSON.parse(response);
                if (result.success) {
                    const row = document.querySelector(`tr[data-avis-id="${avisId}"]`);
                    if (!row) {
                        console.error('Row not found for avis:', avisId);
                        return;
                    }
                    const statusBadge = row.querySelector('.status-badge');
                    const button = row.querySelector('.btn-visibility');
                    
                    if (result.newState) {
                        statusBadge.textContent = 'Visible';
                        statusBadge.classList.remove('hidden');
                        statusBadge.classList.add('visible');
                        button.innerHTML = '<i class="fas fa-eye-slash"></i> Masquer';
                    } else {
                        statusBadge.textContent = 'Masqué';
                        statusBadge.classList.remove('visible');
                        statusBadge.classList.add('hidden');
                        button.innerHTML = '<i class="fas fa-eye"></i> Afficher';
                    }

                    showNotification(result.message, 'success');
                } else {
                    showNotification(result.message || 'Erreur lors de la modification', 'error');
                }
            } catch (e) {
                console.error('Parse error:', e, 'Response:', response);
                showNotification('Erreur lors du traitement de la réponse', 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', error);
            showNotification('Erreur de connexion au serveur', 'error');
        }
    });
}

// Gestion de la modification des avis
function modifierAvis(avisId) {
    window.location.href = `modifier_avis.php?id=${avisId}`;
}

// Gestion de la suppression des avis
function supprimerAvis(avisId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cet avis ?')) {
        return;
    }

    $.ajax({
        url: '../../controller/avisController.php',
        method: 'POST',
        data: {
            action: 'supprimer',
            avis_id: avisId
        },
        success: function(response) {
            if (response === 'OK') {
                showNotification('Avis supprimé avec succès', 'success');
                refreshAvis();
            } else {
                showNotification('Erreur lors de la suppression', 'error');
            }
        },
        error: function(xhr) {
            showNotification('Erreur réseau : ' + (xhr.responseText || 'Erreur serveur'), 'error');
        }
    });
}

// Gestion du signalement des avis
function signalerAvis(avisId) {
    if (!confirm('Voulez-vous signaler cet avis ?')) {
        return;
    }

    $.ajax({
        url: '../../controller/avisController.php',
        method: 'POST',
        data: {
            action: 'signaler',
            avis_id: avisId
        },
        success: function(response) {
            if (response === 'OK') {
                showNotification('Avis signalé avec succès', 'success');
                refreshAvis();
            } else {
                showNotification('Erreur lors du signalement', 'error');
            }
        },
        error: function(xhr) {
            showNotification('Erreur réseau : ' + (xhr.responseText || 'Erreur serveur'), 'error');
        }
    });
}

// Recherche des avis
function searchAvis() {
    const searchValue = document.getElementById('searchInput').value.toLowerCase();
    const searchType = document.getElementById('searchType').value;
    const rows = document.getElementById('avisTableBody').getElementsByTagName('tr');

    for (let row of rows) {
        const cells = row.getElementsByTagName('td');
        let found = false;
        
        switch(searchType) {
            case 'nom':
                found = cells[1].textContent.toLowerCase().includes(searchValue);
                break;
            case 'email':
                found = cells[3].textContent.toLowerCase().includes(searchValue);
                break;
            case 'avis_id':
                found = cells[0].textContent.includes(searchValue);
                break;
        }
        
        row.style.display = found ? '' : 'none';
    }
}

// Tri des avis
function sortAvis(criteria) {
    document.querySelectorAll('.sort-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');

    $.ajax({
        url: '../../controller/avisController.php',
        method: 'GET',
        data: {
            action: 'sort',
            criteria: criteria
        },
        success: function(response) {
            $('#avisTableBody').html(response);
        },
        error: function() {
            showNotification('Erreur lors du tri', 'error');
        }
    });
}

// Rafraîchir les avis dynamiquement
function refreshAvis() {
    $.ajax({
        url: '../../controller/avisController.php',
        method: 'GET',
        data: { action: 'refresh' },
        success: function(response) {
            try {
                const result = JSON.parse(response);
                if (result.success) {
                    $.ajax({
                        url: '../../controller/avisController.php',
                        method: 'GET',
                        data: { action: 'sort', criteria: '<?php echo isset($_GET['tri']) ? $_GET['tri'] : 'date_desc'; ?>' },
                        success: function(html) {
                            $('#avisTableBody').html(html);
                            showNotification('Avis mis à jour', 'success');
                        },
                        error: function() {
                            showNotification('Erreur lors du rafraîchissement des avis', 'error');
                        }
                    });
                }
            } catch(e) {
                console.error('Erreur lors du rafraîchissement:', e);
                showNotification('Erreur lors du rafraîchissement', 'error');
            }
        },
        error: function() {
            showNotification('Erreur lors du rafraîchissement', 'error');
        }
    });
}

// Rafraîchir les commentaires dynamiquement
function refreshComments() {
    $.ajax({
        url: '../../controller/commentaireController.php',
        method: 'GET',
        data: { action: 'refresh' },
        success: function(response) {
            try {
                const result = JSON.parse(response);
                if (result.success) {
                    $('#commentsTableBody').html(result.data);
                    showNotification('Commentaires mis à jour', 'success');
                } else {
                    showNotification('Erreur lors du rafraîchissement des commentaires', 'error');
                }
            } catch(e) {
                console.error('Erreur lors du rafraîchissement des commentaires:', e);
                showNotification('Erreur lors du rafraîchissement', 'error');
            }
        },
        error: function() {
            showNotification('Erreur lors du rafraîchissement des commentaires', 'error');
        }
    });
}

// Rafraîchir toutes les 30 secondes
setInterval(() => {
    refreshAvis();
    refreshComments();
}, 30000);
</script>
</body>
</html>