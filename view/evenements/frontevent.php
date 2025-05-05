<?php
session_start();
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../controller/ReservationController.php';

$reservationController = new ReservationController();

// Traitement de la réservation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserver'])) {
    $result = $reservationController->create([
        'nom' => $_POST['nom'],
        'email' => $_POST['email'],
        'id_evenement' => $_POST['id_evenement']
    ]);
    
    if ($result['success']) {
        $_SESSION['success'] = $result['message'];
    } else {
        $_SESSION['error'] = $result['message'];
    }
    
    // Redirection vers la même page
    $redirect_url = 'frontevent.php#events';
    if (!headers_sent()) {
        header("Location: " . $redirect_url);
        exit();
    } else {
        echo '<script>window.location.href="' . $redirect_url . '";</script>';
        exit();
    }
}

// Récupération des événements
try {
    $connexion = config::getConnexion();
    $stmt = $connexion->query("SELECT * FROM evenements ORDER BY date_evenement ASC");
    $evenements = $stmt->fetchAll();

    // Récupérer le nombre de réservations par événement
    $stmt = $connexion->query("
        SELECT id_evenement, COUNT(*) as nb_reservations 
        FROM reservations 
        GROUP BY id_evenement
    ");
    $reservations = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
} catch(PDOException $e) {
    $error = "Erreur lors de la récupération des événements : " . $e->getMessage();
    $evenements = [];
    $reservations = [];
}

// Grouper les événements par catégorie
$prochains_evenements = [];
$evenements_populaires = [];
$evenements_gratuits = [];
$aujourdhui = strtotime('today');

foreach ($evenements as $event) {
    $date_event = strtotime($event['date_evenement']);
    
    // Prochains événements : tous les événements à venir, triés par date
    if ($date_event >= $aujourdhui) {
        $prochains_evenements[] = $event;
    }
    
    // Événements populaires : les 5 plus récents
    if (count($evenements_populaires) < 5) {
        $evenements_populaires[] = $event;
    }
    
    // Événements gratuits : les 5 prochains événements
    if ($date_event >= $aujourdhui && count($evenements_gratuits) < 5) {
        $evenements_gratuits[] = $event;
    }
}

// Limiter à 5 prochains événements
$prochains_evenements = array_slice($prochains_evenements, 0, 5);

include '../header.php';
?>

<!-- Affichage des messages de succès/erreur -->
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

<!-- ===============================================-->
<!--    Main Content-->
<!-- ===============================================-->
<main class="main" id="top">
    <div class="bg-white">
        <div class="content">
            <div class="container" data-bs-target="#navbar-top" data-bs-spy="scroll" tabindex="0">
                <!-- Hero Section -->
                <section class="mb-9 mb-lg-10 mb-xxl-11 text-center text-lg-start mt-9" id="home">
                    <div>
                        <div class="row g-4 g-lg-6 g-xl-7 mb-6 mb-lg-7 mb-xl-10 align-items-center">
                            <div class="col-12 col-lg-6">
                                <img class="img-fluid" src="../../assets/img/Hero/planting.png" alt="Événements" />
                            </div>
                            <div class="col-12 col-lg-6">
                                <h1 class="fs-5 fs-md-3 fs-xxl-2 text-black fw-light mb-4">Explorez nos <span class="fw-bold">Événements</span> <br class="d-sm-none d-md-block d-xxl-none" />& <span class="text-gradient fw-bold">Activités</span></h1>
                                <p class="fs-8 fs-md-11 fs-xxl-7 text-primary mb-5 mb-lg-6 mb-xl-7 fw-light">
                                    Découvrez notre sélection d'événements autour des plantes et du jardinage.
                                    Ateliers, conférences, et rencontres pour tous les passionnés de nature.
                                </p>
                                <a href="#events" class="btn btn-gradient fs-8 mt-1 d-inline-flex align-items-center">
                                    <span class="me-2">Voir tous les événements</span>
                                    <span class="uil uil-arrow-right"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Prochains événements -->
                <section class="mb-9 mb-lg-10 mb-xxl-11" id="events">
                    <h3 class="mb-x1 fs-8 fs-md-7 fs-xxl-6 text-success fw-bold pt-6">Nos Événements</h3>
                    <h1 class="fs-5 fs-md-3 fs-xxl-2 text-secondary text-capitalize fw-light mb-13 mb-lg-7">
                        Prochains <span class="fw-bold">Rendez-vous</span>
                    </h1>
                    <div class="mb-4 mb-lg-0">
                        <div class="swiper-theme-container position-relative">
                            <div class="swiper-container theme-slider" data-swiper='{"spaceBetween":32,"loop":true,"loopedSlides":5,"breakpoints":{"0":{"slidesPerView":1},"768":{"slidesPerView":2},"1024":{"slidesPerView":3}}}'>
                                <div class="swiper-wrapper">
                                    <?php if (empty($prochains_evenements)): ?>
                                        <div class="text-center w-100 py-5">
                                            <p class="text-muted">Aucun événement à venir pour le moment.</p>
                                        </div>
                                    <?php else: ?>
                                        <?php foreach ($prochains_evenements as $event): ?>
                                            <div class="product-card swiper-slide">
                                                <div class="product-card-top position-relative" style="background: linear-gradient(rgba(46, 204, 113, 0.1), rgba(46, 204, 113, 0.2));">
                                                    <div class="add-section bg-white rounded p-3 shadow-sm">
                                                        <div class="d-flex align-items-center mb-2">
                                                            <span class="uil uil-calendar-alt text-success me-2"></span>
                                                            <span class="fs-10 fs-md-9">
                                                                <?php echo date('d M Y', strtotime($event['date_evenement'])); ?>
                                                            </span>
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <span class="uil uil-location-point text-success me-2"></span>
                                                            <span class="fs-10 fs-md-9 fw-bold">
                                                                <?php echo htmlspecialchars($event['lieu']); ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column p-4 product-card-body bg-white shadow-sm rounded-bottom">
                                                    <h3 class="text-success fw-bold mb-3 text-center fs-8 fs-md-7">
                                                        <?php echo htmlspecialchars($event['titre']); ?>
                                                    </h3>
                                                    <p class="text-dark fs-10 fs-md-9 mb-3 line-clamp-3">
                                                        <?php echo nl2br(htmlspecialchars($event['description'])); ?>
                                                    </p>
                                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                                        <span class="text-muted fs-10">
                                                            <?php 
                                                            $nb_reservations = isset($reservations[$event['id_evenement']]) ? $reservations[$event['id_evenement']] : 0;
                                                            echo $nb_reservations . " réservation(s)";
                                                            ?>
                                                        </span>
                                                        <button class="btn btn-sm btn-success" 
                                                                onclick="reserverEvent(<?php echo htmlspecialchars(json_encode($event)); ?>)">
                                                            Réserver
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if (!empty($prochains_evenements)): ?>
                                <div class="slider-nav">
                                    <button class="btn prev-button" data-slider="slider-1">
                                        <span class="uil uil-angle-left-b"></span>
                                    </button>
                                    <button class="btn next-button" data-slider="slider-1">
                                        <span class="uil uil-angle-right-b"></span>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>

                <!-- Événements populaires -->
                <section class="mb-9 mb-lg-10 mb-xxl-11" id="popular">
                    <h1 class="fs-5 fs-md-3 fs-xxl-2 text-secondary text-capitalize text-md-end fw-light mb-13 mb-lg-7">
                        Événements <span class="fw-bold">Populaires</span>
                    </h1>
                    <div class="mb-4 mb-lg-0">
                        <div class="swiper-theme-container position-relative">
                            <div class="swiper-container theme-slider" data-swiper='{"spaceBetween":32,"loop":true,"loopedSlides":5,"breakpoints":{"0":{"slidesPerView":1},"768":{"slidesPerView":2},"1024":{"slidesPerView":3}}}'>
                                <div class="swiper-wrapper">
                                    <?php if (empty($evenements_populaires)): ?>
                                        <div class="text-center w-100 py-5">
                                            <p class="text-muted">Aucun événement populaire pour le moment.</p>
                                        </div>
                                    <?php else: ?>
                                        <?php foreach ($evenements_populaires as $event): ?>
                                            <div class="product-card swiper-slide">
                                                <div class="product-card-top position-relative" style="background: linear-gradient(rgba(46, 204, 113, 0.1), rgba(46, 204, 113, 0.2));">
                                                    <div class="add-section bg-white rounded p-3 shadow-sm">
                                                        <div class="d-flex align-items-center mb-2">
                                                            <span class="uil uil-calendar-alt text-success me-2"></span>
                                                            <span class="fs-10 fs-md-9">
                                                                <?php echo date('d M Y', strtotime($event['date_evenement'])); ?>
                                                            </span>
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <span class="uil uil-location-point text-success me-2"></span>
                                                            <span class="fs-10 fs-md-9 fw-bold">
                                                                <?php echo htmlspecialchars($event['lieu']); ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column p-4 product-card-body bg-white shadow-sm rounded-bottom">
                                                    <h3 class="text-success fw-bold mb-3 text-center fs-8 fs-md-7">
                                                        <?php echo htmlspecialchars($event['titre']); ?>
                                                    </h3>
                                                    <p class="text-dark fs-10 fs-md-9 mb-3 line-clamp-3">
                                                        <?php echo nl2br(htmlspecialchars($event['description'])); ?>
                                                    </p>
                                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                                        <span class="text-muted fs-10">
                                                            <?php 
                                                            $nb_reservations = isset($reservations[$event['id_evenement']]) ? $reservations[$event['id_evenement']] : 0;
                                                            echo $nb_reservations . " réservation(s)";
                                                            ?>
                                                        </span>
                                                        <button class="btn btn-sm btn-success" 
                                                                onclick="reserverEvent(<?php echo htmlspecialchars(json_encode($event)); ?>)">
                                                            Réserver
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if (!empty($evenements_populaires)): ?>
                                <div class="slider-nav">
                                    <button class="btn prev-button" data-slider="slider-2">
                                        <span class="uil uil-angle-left-b"></span>
                                    </button>
                                    <button class="btn next-button" data-slider="slider-2">
                                        <span class="uil uil-angle-right-b"></span>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>

                <!-- Événements gratuits -->
                <section class="mb-9 mb-lg-10 mb-xxl-11" id="slider-3">
                    <h1 class="fs-5 fs-md-3 fs-xxl-2 text-secondary text-capitalize fw-light mb-13 mb-lg-7">
                        Événements <span class="fw-bold">Gratuits</span>
                    </h1>
                    <div class="mb-4 mb-lg-0">
                        <div class="swiper-theme-container position-relative">
                            <div class="swiper-container theme-slider" data-swiper='{"spaceBetween":32,"loop":true,"loopedSlides":5,"breakpoints":{"0":{"slidesPerView":1},"768":{"slidesPerView":2},"1024":{"slidesPerView":3}}}'>
                                <div class="swiper-wrapper">
                                    <?php foreach ($evenements_gratuits as $event): ?>
                                        <div class="product-card swiper-slide">
                                            <div class="product-card-top" style="background-image: url('../../assets/img/products/products/7.png')">
                                                <div class="add-section">
                                                    <a class="fs-10 fs-md-9 d-flex flex-column flex-xl-row align-items-center" href="#!">
                                                        <span class="uil uil-calendar-alt me-1 align-middle"></span>
                                                        <?php echo date('d M Y', strtotime($event['date_evenement'])); ?>
                                                    </a>
                                                    <a class="fs-10 fs-md-9 d-flex flex-column flex-xl-row align-items-center text-success fw-bold" href="#!">
                                                        <span class="uil uil-location-point me-1 align-middle"></span>
                                                        <?php echo htmlspecialchars($event['lieu']); ?>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column gap-x1 p-x1 pb-5 product-card-body">
                                                <h3 class="text-success fw-semi-bold text-center line-clamp-1 fs-8 fs-md-11 fs-xxl-7">
                                                    <?php echo htmlspecialchars($event['titre']); ?>
                                                </h3>
                                                <p class="text-dark fs-10 fs-md-9 fs-xl-8 text-capitalize lh-xl mb-0 line-clamp-3">
                                                    <?php echo nl2br(htmlspecialchars($event['description'])); ?>
                                                </p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="slider-nav">
                                <button class="btn prev-button" data-slider="slider-3">
                                    <span class="uil uil-angle-left-b"></span>
                                </button>
                                <button class="btn next-button" data-slider="slider-3">
                                    <span class="uil uil-angle-right-b"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Newsletter Section -->
                <section class="mb-9 mb-lg-10 mb-xxl-11 text-center text-lg-start mt-1" id="support">
                    <div class="row g-4 g-lg-6 g-xl-7 pt-6">
                        <div class="d-none d-lg-block col-lg-6 text-center">
                            <img class="img-fluid" src="../../assets/img/illustrations/women_watering.png" alt="" />
                        </div>
                        <div class="col-12 col-lg-6 d-flex flex-column justify-content-center mt-0">
                            <p class="fs-8 fs-md-7 fs-xxl-6 text-success fw-bold my-0 text-capitalize">ne manquez rien</p>
                            <h1 class="fs-5 fs-lg-4 fs-xl-3 text-secondary text-capitalize fw-light mt-3 mb-4">
                                Inscrivez-vous à notre<br /><span class="fw-bold">Newsletter</span>
                            </h1>
                            <p class="text-success fs-8 fs-lg-7 text-capitalize beginner-guide my-4">
                                Recevez en avant-première <span class="fw-bold">nos événements</span><br class="d-none d-xxl-block" />et actualités
                            </p>
                            <form method="POST" onsubmit="return false;">
                                <div class="input-group position-relative">
                                    <input class="form-control email-input" type="email" name="email" placeholder="votre email" />
                                    <button class="btn shadow-none submit-button position-absolute end-0" type="submit">
                                        <span class="uil uil-arrow-right"></span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <button class="btn scroll-to-top" data-scroll-top="data-scroll-top">
            <span class="uil uil-angle-up text-white"></span>
        </button>
    </div>
</main>

<!-- Modal Réservation -->
<div class="modal fade" id="reservationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Réserver un événement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="frontevent.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id_evenement" id="reservation_event_id">
                    <div class="mb-3">
                        <h4 id="reservation_titre" class="text-success"></h4>
                        <p id="reservation_date_lieu" class="text-muted"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nom complet</label>
                        <input type="text" class="form-control" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" name="reserver" class="btn btn-success">Confirmer la réservation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function reserverEvent(event) {
    document.getElementById('reservation_event_id').value = event.id_evenement;
    document.getElementById('reservation_titre').textContent = event.titre;
    document.getElementById('reservation_date_lieu').textContent = 
        new Date(event.date_evenement).toLocaleDateString() + ' - ' + event.lieu;
    new bootstrap.Modal(document.getElementById('reservationModal')).show();
}
</script>

<?php include '../footer.php'; ?> 