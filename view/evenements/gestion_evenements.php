<?php
session_start();
require_once '../../config.php';
require_once '../../controller/ReservationController.php';

$reservationController = new ReservationController();

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $connexion = config::getConnexion();
        
        // Suppression
        if (isset($_POST['delete']) && !empty($_POST['delete'])) {
            $id = (int)$_POST['delete'];
            $stmt = $connexion->prepare("DELETE FROM evenements WHERE id_evenement = ?");
            $stmt->execute([$id]);
            $_SESSION['success'] = "Événement supprimé avec succès.";
            header("Location: gestion_evenements.php");
            exit();
        }
        
        // Ajout
        if (isset($_POST['add'])) {
            if (empty($_POST['titre']) || empty($_POST['date_evenement']) || empty($_POST['lieu'])) {
                throw new Exception("Tous les champs sont obligatoires");
            }
            
            $sql = "INSERT INTO evenements (titre, description, date_evenement, lieu) 
                    VALUES (:titre, :description, :date_evenement, :lieu)";
            $stmt = $connexion->prepare($sql);
            $stmt->execute([
                ':titre' => $_POST['titre'],
                ':description' => $_POST['description'],
                ':date_evenement' => $_POST['date_evenement'],
                ':lieu' => $_POST['lieu']
            ]);
            $_SESSION['success'] = "Événement ajouté avec succès.";
            header("Location: gestion_evenements.php");
            exit();
        }
        
        // Modification
        if (isset($_POST['edit']) && !empty($_POST['edit'])) {
            if (empty($_POST['titre']) || empty($_POST['date_evenement']) || empty($_POST['lieu'])) {
                throw new Exception("Tous les champs sont obligatoires");
            }
            
            $sql = "UPDATE evenements SET 
                    titre = :titre,
                    description = :description,
                    date_evenement = :date_evenement,
                    lieu = :lieu
                    WHERE id_evenement = :id";
            $stmt = $connexion->prepare($sql);
            $stmt->execute([
                ':titre' => $_POST['titre'],
                ':description' => $_POST['description'],
                ':date_evenement' => $_POST['date_evenement'],
                ':lieu' => $_POST['lieu'],
                ':id' => $_POST['edit']
            ]);
            $_SESSION['success'] = "Événement modifié avec succès.";
            header("Location: gestion_evenements.php");
            exit();
        }

        // Suppression d'une réservation
        if (isset($_POST['delete_reservation']) && !empty($_POST['delete_reservation'])) {
            $result = $reservationController->delete($_POST['delete_reservation']);
            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }
            header("Location: gestion_evenements.php?tab=reservations");
            exit();
        }

        // Modification d'une réservation
        if (isset($_POST['edit_reservation'])) {
            if (empty($_POST['nom']) || empty($_POST['email'])) {
                throw new Exception("Le nom et l'email sont obligatoires");
            }
            
            $sql = "UPDATE reservations SET 
                    nom_participant = :nom,
                    email = :email
                    WHERE id_reservation = :id";
            $stmt = $connexion->prepare($sql);
            $stmt->execute([
                ':nom' => $_POST['nom'],
                ':email' => $_POST['email'],
                ':id' => $_POST['edit_reservation']
            ]);
            $_SESSION['success'] = "Réservation modifiée avec succès.";
            header("Location: gestion_evenements.php?tab=reservations");
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Erreur : " . $e->getMessage();
        header("Location: gestion_evenements.php");
        exit();
    }
}

// Récupération des événements
try {
    $connexion = config::getConnexion();
    $stmt = $connexion->query("SELECT * FROM evenements ORDER BY date_evenement DESC");
    $evenements = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $total = count($evenements);
    $a_venir = 0;
    $ce_mois = 0;
    $lieux = [];
    
    foreach ($evenements as $event) {
        if (strtotime($event['date_evenement']) > time()) {
            $a_venir++;
        }
        
        if (date('m/Y', strtotime($event['date_evenement'])) === date('m/Y')) {
            $ce_mois++;
        }
        
        if (!in_array($event['lieu'], $lieux)) {
            $lieux[] = $event['lieu'];
        }
    }
} catch(PDOException $e) {
    $_SESSION['error'] = "Erreur de connexion : " . $e->getMessage();
    $evenements = [];
    $total = $a_venir = $ce_mois = 0;
    $lieux = [];
}

// Récupération des réservations avec les détails des événements
$result = $reservationController->getAllReservations();
if ($result['success']) {
    $reservations = $result['data'];
} else {
    $_SESSION['error'] = $result['message'];
    $reservations = [];
}

// Statistiques des réservations
$statsResult = $reservationController->getStats();
if ($statsResult['success']) {
    $reservationStats = $statsResult['data'];
} else {
    $reservationStats = [
        'total' => 0,
        'aujourd_hui' => 0,
        'cette_semaine' => 0
    ];
}

include '../header.php';
?>

<style>
.dashboard {
    background-color: #f8f9fa;
    min-height: 100vh;
}

.sidebar {
    background: linear-gradient(135deg, #1e88e5 0%, #1565c0 100%);
    color: white;
    min-height: calc(100vh - 60px);
    padding: 2rem 1rem;
    position: fixed;
    width: 250px;
}

.main-content {
    margin-left: 250px;
    padding: 2rem;
}

.stat-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 1.5rem;
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.events-table {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.events-table th {
    background-color: #f8f9fa;
    border: none;
    font-weight: 600;
}

.events-table td {
    border: none;
    padding: 1rem;
    vertical-align: middle;
}

.events-table tbody tr {
    border-bottom: 1px solid #eee;
    transition: background-color 0.2s;
}

.events-table tbody tr:hover {
    background-color: #f8f9fa;
}

.btn-action {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    margin: 0 2px;
}

.add-event-btn {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    width: 60px;
    height: 60px;
    border-radius: 30px;
    background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(46, 204, 113, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    transition: transform 0.2s;
}

.add-event-btn:hover {
    transform: scale(1.1);
    color: white;
}

.modal-content {
    border-radius: 15px;
    border: none;
}

.modal-header {
    border-bottom: none;
    padding: 1.5rem;
}

.modal-footer {
    border-top: none;
    padding: 1.5rem;
}

.form-control {
    border-radius: 10px;
    padding: 0.75rem 1rem;
}

.form-control:focus {
    box-shadow: none;
    border-color: #1e88e5;
}
</style>

<div class="dashboard">
    <div class="sidebar">
        <h4 class="mb-4">Dashboard</h4>
        <div class="nav flex-column">
            <a href="#" class="nav-link text-white active">
                <i class="mdi mdi-calendar me-2"></i>
                Événements
            </a>
            <a href="gestion_reservations.php" class="nav-link text-white-50">
                <i class="mdi mdi-account-group me-2"></i>
                Réservations
            </a>
        </div>
    </div>

    <div class="main-content">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php 
                echo $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php 
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Gestion des événements</h2>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-12 col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                            <i class="mdi mdi-calendar"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="mb-0"><?php echo $total; ?></h3>
                            <p class="text-muted mb-0">Total événements</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <i class="mdi mdi-calendar-clock"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="mb-0"><?php echo $a_venir; ?></h3>
                            <p class="text-muted mb-0">À venir</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                            <i class="mdi mdi-calendar-today"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="mb-0"><?php echo $ce_mois; ?></h3>
                            <p class="text-muted mb-0">Ce mois</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info bg-opacity-10 text-info">
                            <i class="mdi mdi-map-marker"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="mb-0"><?php echo count($lieux); ?></h3>
                            <p class="text-muted mb-0">Lieux</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="events-table">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Date</th>
                        <th>Lieu</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($evenements)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4">Aucun événement trouvé</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($evenements as $event): ?>
                            <tr>
                                <td class="fw-medium"><?php echo htmlspecialchars($event['titre']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($event['date_evenement'])); ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="mdi mdi-map-marker-outline me-1"></i>
                                        <?php echo htmlspecialchars($event['lieu']); ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 300px;">
                                        <?php echo htmlspecialchars($event['description']); ?>
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-action btn-outline-primary" 
                                            onclick='editEvent(<?php echo json_encode($event); ?>)'>
                                        <i class="mdi mdi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-action btn-outline-danger" 
                                            onclick="deleteEvent(<?php echo $event['id_evenement']; ?>)">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <a href="#" class="add-event-btn" data-bs-toggle="modal" data-bs-target="#addEventModal">
            <i class="mdi mdi-plus"></i>
        </a>
    </div>
</div>

<!-- Modals -->
<div class="modal fade" id="addEventModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvel Événement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="gestion_evenements.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Titre</label>
                        <input type="text" class="form-control" name="titre" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" name="date_evenement" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lieu</label>
                        <input type="text" class="form-control" name="lieu" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" name="add" value="1" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editEventModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier l'Événement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="gestion_evenements.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="edit" id="edit_id">
                    <div class="mb-3">
                        <label class="form-label">Titre</label>
                        <input type="text" class="form-control" name="titre" id="edit_titre" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" name="date_evenement" id="edit_date" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lieu</label>
                        <input type="text" class="form-control" name="lieu" id="edit_lieu" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="edit_description" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteEventModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Êtes-vous sûr de vouloir supprimer cet événement ? Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <form action="gestion_evenements.php" method="POST">
                    <input type="hidden" name="delete" id="delete_id">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function editEvent(event) {
    document.getElementById('edit_id').value = event.id_evenement;
    document.getElementById('edit_titre').value = event.titre;
    document.getElementById('edit_date').value = event.date_evenement.split(' ')[0];
    document.getElementById('edit_lieu').value = event.lieu;
    document.getElementById('edit_description').value = event.description;
    new bootstrap.Modal(document.getElementById('editEventModal')).show();
}

function deleteEvent(id) {
    document.getElementById('delete_id').value = id;
    new bootstrap.Modal(document.getElementById('deleteEventModal')).show();
}
</script>

<?php include '../footer.php'; ?> 