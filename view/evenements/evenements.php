<?php include '../header.php'; 
require_once __DIR__ . '/../../config.php';

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $connexion = config::getConnexion();
    
    // Suppression
    if (isset($_POST['delete'])) {
        try {
            $id = (int)$_POST['delete'];
            $stmt = $connexion->prepare("DELETE FROM evenements WHERE id_evenement = ?");
            $stmt->execute([$id]);
            $success = "Événement supprimé avec succès.";
        } catch(PDOException $e) {
            $error = "Erreur lors de la suppression : " . $e->getMessage();
        }
    }
    
    // Ajout
    if (isset($_POST['add'])) {
        try {
            $sql = "INSERT INTO evenements (titre, description, date_evenement, lieu) 
                    VALUES (:titre, :description, :date_evenement, :lieu)";
            $stmt = $connexion->prepare($sql);
            $stmt->execute([
                ':titre' => $_POST['titre'],
                ':description' => $_POST['description'],
                ':date_evenement' => $_POST['date_evenement'],
                ':lieu' => $_POST['lieu']
            ]);
            $success = "Événement ajouté avec succès.";
        } catch(PDOException $e) {
            $error = "Erreur lors de l'ajout : " . $e->getMessage();
        }
    }
    
    // Modification
    if (isset($_POST['edit'])) {
        try {
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
            $success = "Événement modifié avec succès.";
        } catch(PDOException $e) {
            $error = "Erreur lors de la modification : " . $e->getMessage();
        }
    }
}

// Récupération des événements
try {
    $connexion = config::getConnexion();
    $stmt = $connexion->query("SELECT * FROM evenements ORDER BY date_evenement DESC");
    $evenements = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Erreur lors de la récupération des événements : " . $e->getMessage();
    $evenements = [];
}
?>

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
                                <img class="img-fluid w-50 w-lg-100" src="../../assets/img/Hero/planting.png" alt="Événements" />
                            </div>
                            <div class="col-12 col-lg-6">
                                <h1 class="fs-5 fs-md-3 fs-xxl-2 text-black fw-light mb-4">Découvrez nos <span class="fw-bold">Événements</span> <br class="d-sm-none d-md-block d-xxl-none" />& <span class="text-gradient fw-bold">Activités</span></h1>
                                <p class="fs-8 fs-md-11 fs-xxl-7 text-primary mb-5 mb-lg-6 mb-xl-7 fw-light">
                                    Participez à nos événements passionnants et enrichissez votre expérience dans le monde des plantes. 
                                    Des ateliers, des conférences et des rencontres pour tous les amoureux de la nature.
                                </p>
                                <button class="btn btn-gradient fs-8 mt-1 d-inline-flex">
                                    <span>explorer les événements</span>
                                    <span class="uil uil-arrow-right"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Counter Section -->
                <section class="mb-9 mb-lg-10 mb-xxl-11">
                    <div class="row g-4">
                        <div class="col-12 col-lg-4 text-center">
                            <img class="mb-3" src="../../assets/img/icons/Counter_1.png" alt="" />
                            <h1 class="text-secondary fs-4 fs-lg-3 fs-xl-2 counter-delivared" data-countup='{"endValue":150,"suffix":"+"}' style="font-variant-numeric: lining-nums proportional-nums;">0</h1>
                            <p class="text-success fs-7 fs-xl-6 fw-bold mb-0">
                                Événements<br class="d-none d-xl-block d-xxl-none" />Organisés
                            </p>
                        </div>
                        <div class="col-12 col-lg-4 text-center">
                            <img class="mb-3" src="../../assets/img/icons/Counter_2.png" alt="" />
                            <h1 class="text-secondary fs-4 fs-lg-3 fs-xl-2" data-countup='{"endValue":2000,"suffix":"+"}' style="font-variant-numeric: lining-nums proportional-nums;">0</h1>
                            <p class="text-success fs-7 fs-xl-6 fw-bold mb-0">
                                Participants<br class="d-none d-xl-block d-xxl-none" />Satisfaits
                            </p>
                        </div>
                        <div class="col-12 col-lg-4 text-center">
                            <img class="mb-3" src="../../assets/img/icons/Counter_3.png" alt="" />
                            <h1 class="text-secondary fs-4 fs-lg-3 fs-xl-2" data-countup='{"endValue":50,"suffix":"+"}' style="font-variant-numeric: lining-nums proportional-nums;">0</h1>
                            <p class="text-success fs-7 fs-xl-6 fw-bold mb-0">
                                Experts<br class="d-none d-xl-block d-xxl-none" />Intervenants
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Events Section -->
                <section class="mb-9 mb-lg-10 mb-xxl-11" id="events">
                    <h4 class="mb-x1 fs-8 fs-md-7 fs-xxl-6 text-success fw-bold pt-6">Événements à venir</h4>
                    <h1 class="fs-5 fs-md-3 fs-xxl-2 text-secondary text-capitalize fw-light mb-13 mb-lg-7">
                        Nos <span class="fw-bold">Prochains</span> Rendez-vous
                    </h1>
                    <?php if (empty($evenements)): ?>
                        <div class="text-center py-6">
                            <img class="mb-4" src="../../assets/img/icons/Counter_2.png" alt="" />
                            <h1 class="text-secondary fs-4 fs-lg-3 fs-xl-2">Aucun événement pour le moment</h1>
                            <p class="text-success fs-7 fs-xl-6 fw-bold mb-0">
                                Revenez bientôt pour découvrir<br class="d-none d-xl-block d-xxl-none" />nos prochains événements
                            </p>
                        </div>
                    <?php else: ?>
                        <div class="mb-4 mb-lg-0">
                            <div class="swiper-theme-container position-relative">
                                <div class="swiper-container theme-slider" data-swiper='{"spaceBetween":32,"loop":true,"loopedSlides":5,"breakpoints":{"0":{"slidesPerView":1},"768":{"slidesPerView":2},"1024":{"slidesPerView":3}}}'>
                                    <div class="swiper-wrapper">
                                        <?php foreach ($evenements as $event): ?>
                                            <div class="product-card swiper-slide">
                                                <div class="product-card-top" style="background-image: url('../../assets/img/products/products/1.png')">
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
                                    <button class="btn prev-button" data-slider="slider-1">
                                        <span class="uil uil-angle-left-b"></span>
                                    </button>
                                    <button class="btn next-button" data-slider="slider-1">
                                        <span class="uil uil-angle-right-b"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </section>

                <!-- Newsletter Section -->
                <section class="mb-9 mb-lg-10 mb-xxl-11 text-center text-lg-start mt-1" id="support">
                    <div class="row g-4 g-lg-6 g-xl-7 pt-6">
                        <div class="d-none d-lg-block col-lg-6 text-center">
                            <img class="img-fluid" src="../../assets/img/illustrations/women_watering.png" alt="" />
                        </div>
                        <div class="col-12 col-lg-6 d-flex flex-column justify-content-center mt-0">
                            <p class="fs-8 fs-md-7 fs-xxl-6 text-success fw-bold my-0 text-capitalize">restez informé</p>
                            <h1 class="fs-5 fs-lg-4 fs-xl-3 text-secondary text-capitalize fw-light mt-3 mb-4">
                                Ne manquez aucun<br />de nos <span class="fw-bold">Événements</span>
                            </h1>
                            <p class="text-success fs-8 fs-lg-7 text-capitalize beginner-guide my-4">
                                Recevez nos <span class="fw-bold">Notifications d'Événements</span><br class="d-none d-xxl-block" />en avant-première
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

<?php include '../footer.php'; ?> 