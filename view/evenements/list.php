<?php
require_once __DIR__ . '/../../config.php';

try {
    $connexion = config::getConnexion();
    
    // Récupération des événements
    $sql = "SELECT * FROM evenements ORDER BY date_evenement DESC";
    $stmt = $connexion->query($sql);
    $evenements = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Erreur lors de la récupération des événements : " . $e->getMessage();
    $evenements = [];
}

// Inclusion du header après le traitement
require_once __DIR__ . '/../header.php';
?>

<!-- Hero Section -->
<section class="py-6 py-lg-8 bg-success-subtle position-relative">
    <div class="container">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php 
                    if ($_GET['success'] == 1) {
                        echo "L'événement a été créé avec succès !";
                    } elseif ($_GET['success'] == 2) {
                        echo "L'événement a été modifié avec succès !";
                    }
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <div class="row align-items-center">
            <div class="col-lg-6 text-center text-lg-start">
                <h4 class="text-success fw-bold mb-3">Nos Événements</h4>
                <h1 class="fs-3 fs-lg-2 mb-3">Découvrez nos événements <span class="fw-bold text-success">écologiques</span></h1>
                <p class="lead text-primary mb-4">Participez à nos ateliers, conférences et activités pour un mode de vie plus vert et durable.</p>
                <a href="create.php" class="btn btn-gradient fs-8 d-inline-flex align-items-center">
                    <span class="me-2">Créer un événement</span>
                    <span class="uil uil-plus-circle fs-6"></span>
                </a>
            </div>
            <div class="col-lg-6 text-center mt-4 mt-lg-0">
                <img src="/assets/img/Hero/planting.png" alt="Events" class="img-fluid w-75">
            </div>
        </div>
    </div>
    <div class="position-absolute bottom-0 start-0 w-100 overflow-hidden">
        <svg class="w-100" style="color: var(--bs-white);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="currentColor" d="M0,288L48,272C96,256,192,224,288,197.3C384,171,480,149,576,165.3C672,181,768,235,864,250.7C960,267,1056,245,1152,224C1248,203,1344,181,1392,170.7L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>
</section>

<!-- Events Section -->
<section class="py-6">
    <div class="container">
        <!-- Filters -->
        <div class="row mb-5">
            <div class="col-md-6">
                <div class="d-flex align-items-center">
                    <span class="uil uil-filter fs-5 text-success me-2"></span>
                    <select class="form-select w-auto">
                        <option>Tous les événements</option>
                        <option>À venir</option>
                        <option>Passés</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="position-relative">
                    <input type="search" class="form-control" placeholder="Rechercher un événement...">
                    <span class="position-absolute end-0 top-50 translate-middle-y me-2 text-primary">
                        <span class="uil uil-search"></span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Events Grid -->
        <div class="row g-4">
            <?php if (empty($evenements)): ?>
                <div class="col-12 text-center py-5">
                    <img src="/assets/img/icons/empty.png" alt="Aucun événement" class="mb-3" style="width: 120px;">
                    <h3 class="text-secondary">Aucun événement pour le moment</h3>
                    <p class="text-primary">Soyez le premier à créer un événement !</p>
                </div>
            <?php else: ?>
                <?php foreach ($evenements as $evenement): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm hover-shadow-lg transition-all">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                                        <i class="uil uil-calendar-alt me-1"></i>
                                        <?= date('d/m/Y', strtotime($evenement['date_evenement'])) ?>
                                    </span>
                                    <div class="dropdown">
                                        <button class="btn btn-link text-secondary p-0" data-bs-toggle="dropdown">
                                            <i class="uil uil-ellipsis-h fs-5"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="edit.php?id=<?= $evenement['id_evenement'] ?>">
                                                    <i class="uil uil-edit me-2"></i> Modifier
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="delete.php?id=<?= $evenement['id_evenement'] ?>" 
                                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?');">
                                                    <i class="uil uil-trash-alt me-2"></i> Supprimer
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <h4 class="card-title text-success mb-3"><?= htmlspecialchars($evenement['titre']) ?></h4>
                                <p class="card-text text-primary mb-4"><?= htmlspecialchars($evenement['description']) ?></p>
                                
                                <div class="d-flex align-items-center text-secondary">
                                    <span class="uil uil-location-point fs-5 me-2"></span>
                                    <span><?= htmlspecialchars($evenement['lieu']) ?></span>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-0 pt-0 pb-4 px-4">
                                <div class="d-grid">
                                    <button class="btn btn-outline-success">
                                        <i class="uil uil-user-plus me-2"></i> S'inscrire
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../footer.php'; ?> 