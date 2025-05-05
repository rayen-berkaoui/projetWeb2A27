<?php
session_start();
require_once '../../config.php';
require_once '../../controller/ReservationController.php';

$reservationController = new ReservationController();

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Suppression d'une réservation
        if (isset($_POST['delete_reservation']) && !empty($_POST['delete_reservation'])) {
            $result = $reservationController->delete($_POST['delete_reservation']);
            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }
            header("Location: gestion_reservations.php");
            exit();
        }

        // Modification d'une réservation
        if (isset($_POST['edit_reservation'])) {
            if (empty($_POST['nom']) || empty($_POST['email'])) {
                throw new Exception("Le nom et l'email sont obligatoires");
            }
            
            $connexion = config::getConnexion();
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
            header("Location: gestion_reservations.php");
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Erreur : " . $e->getMessage();
        header("Location: gestion_reservations.php");
        exit();
    }
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
        'cette_semaine' => 0,
        'par_evenement' => []
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

.reservations-table {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.reservations-table th {
    background-color: #f8f9fa;
    border: none;
    font-weight: 600;
}

.reservations-table td {
    border: none;
    padding: 1rem;
    vertical-align: middle;
}

.reservations-table tbody tr {
    border-bottom: 1px solid #eee;
    transition: background-color 0.2s;
}

.reservations-table tbody tr:hover {
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
            <a href="gestion_evenements.php" class="nav-link text-white-50">
                <i class="mdi mdi-calendar me-2"></i>
                Événements
            </a>
            <a href="#" class="nav-link text-white active">
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
            <h2 class="mb-0">Gestion des réservations</h2>
        </div>

        <!-- Statistiques des réservations -->
        <div class="row g-4 mb-4">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                            <i class="mdi mdi-account-group"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="mb-0"><?php echo $reservationStats['total']; ?></h3>
                            <p class="text-muted mb-0">Total réservations</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <i class="mdi mdi-calendar-today"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="mb-0"><?php echo $reservationStats['aujourd_hui']; ?></h3>
                            <p class="text-muted mb-0">Réservations aujourd'hui</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                            <i class="mdi mdi-calendar-week"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="mb-0"><?php echo $reservationStats['cette_semaine']; ?></h3>
                            <p class="text-muted mb-0">Réservations cette semaine</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des réservations -->
        <div class="reservations-table">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Participant</th>
                        <th>Email</th>
                        <th>Événement</th>
                        <th>Date de l'événement</th>
                        <th>Lieu</th>
                        <th>Date de réservation</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($reservations)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4">Aucune réservation trouvée</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($reservations as $reservation): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($reservation['nom_participant']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['email']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['titre_evenement']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($reservation['date_evenement'])); ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="mdi mdi-map-marker-outline me-1"></i>
                                        <?php echo htmlspecialchars($reservation['lieu']); ?>
                                    </div>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($reservation['date_reservation'])); ?></td>
                                <td>
                                    <button type="button" class="btn btn-action btn-outline-primary" 
                                            onclick='editReservation(<?php echo json_encode($reservation); ?>)'>
                                        <i class="mdi mdi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-action btn-outline-danger" 
                                            onclick="deleteReservation(<?php echo $reservation['id_reservation']; ?>)">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Modification Réservation -->
<div class="modal fade" id="editReservationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier la réservation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="gestion_reservations.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="edit_reservation" id="edit_reservation_id">
                    <div class="mb-3">
                        <label class="form-label">Nom du participant</label>
                        <input type="text" class="form-control" name="nom" id="edit_reservation_nom" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="edit_reservation_email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Événement</label>
                        <input type="text" class="form-control" id="edit_reservation_evenement" disabled>
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

<!-- Modal Suppression Réservation -->
<div class="modal fade" id="deleteReservationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Êtes-vous sûr de vouloir supprimer cette réservation ? Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <form action="gestion_reservations.php" method="POST">
                    <input type="hidden" name="delete_reservation" id="delete_reservation_id">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function editReservation(reservation) {
    document.getElementById('edit_reservation_id').value = reservation.id_reservation;
    document.getElementById('edit_reservation_nom').value = reservation.nom_participant;
    document.getElementById('edit_reservation_email').value = reservation.email;
    document.getElementById('edit_reservation_evenement').value = reservation.titre_evenement;
    new bootstrap.Modal(document.getElementById('editReservationModal')).show();
}

function deleteReservation(id) {
    document.getElementById('delete_reservation_id').value = id;
    new bootstrap.Modal(document.getElementById('deleteReservationModal')).show();
}
</script>

<?php include '../footer.php'; ?> 