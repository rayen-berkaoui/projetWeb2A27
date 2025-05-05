<?php
require_once __DIR__ . '/../../config.php';

// Traitement du formulaire avant tout affichage HTML
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $connexion = config::getConnexion();
        
        $sql = "INSERT INTO evenements (titre, description, date_evenement, lieu) 
                VALUES (:titre, :description, :date_evenement, :lieu)";
        
        $stmt = $connexion->prepare($sql);
        $stmt->execute([
            ':titre' => $_POST['titre'],
            ':description' => $_POST['description'],
            ':date_evenement' => $_POST['date_evenement'],
            ':lieu' => $_POST['lieu']
        ]);

        // Redirection après succès
        header("Location: list.php?success=1");
        exit();
    } catch(PDOException $e) {
        $error = "Erreur lors de la création de l'événement : " . $e->getMessage();
    }
}

// Inclusion du header après le traitement
require_once __DIR__ . '/../header.php';
?>

<section class="py-6 bg-success-subtle">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-4 p-lg-5">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <div class="text-center mb-5">
                            <div class="feature-icon-wrapper rounded-circle bg-success-subtle mb-3 mx-auto" style="width: 80px; height: 80px;">
                                <span class="uil uil-calendar-plus text-success fs-1 d-flex align-items-center justify-content-center h-100"></span>
                            </div>
                            <h4 class="text-success fw-bold">Nouvel Événement</h4>
                            <p class="text-primary">Créez un nouvel événement écologique et partagez-le avec la communauté</p>
                        </div>

                        <form action="" method="POST" class="needs-validation" novalidate>
                            <div class="row g-4">
                                <!-- Titre -->
                                <div class="col-12">
                                    <label class="form-label fw-bold" for="titre">
                                        <span class="uil uil-text-fields me-1"></span> Titre de l'événement
                                    </label>
                                    <input type="text" class="form-control form-control-lg" id="titre" name="titre" 
                                           placeholder="Ex: Atelier de jardinage urbain" required>
                                    <div class="invalid-feedback">Veuillez saisir un titre pour l'événement.</div>
                                </div>

                                <!-- Description -->
                                <div class="col-12">
                                    <label class="form-label fw-bold" for="description">
                                        <span class="uil uil-text-size me-1"></span> Description
                                    </label>
                                    <textarea class="form-control" id="description" name="description" rows="5" 
                                              placeholder="Décrivez votre événement en détail..." required></textarea>
                                    <div class="invalid-feedback">Veuillez fournir une description de l'événement.</div>
                                </div>

                                <!-- Date -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" for="date_evenement">
                                        <span class="uil uil-calendar-alt me-1"></span> Date
                                    </label>
                                    <input type="date" class="form-control" id="date_evenement" name="date_evenement" required>
                                    <div class="invalid-feedback">Veuillez choisir une date.</div>
                                </div>

                                <!-- Lieu -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" for="lieu">
                                        <span class="uil uil-location-point me-1"></span> Lieu
                                    </label>
                                    <input type="text" class="form-control" id="lieu" name="lieu" 
                                           placeholder="Ex: Jardin botanique" required>
                                    <div class="invalid-feedback">Veuillez indiquer le lieu de l'événement.</div>
                                </div>

                                <!-- Boutons -->
                                <div class="col-12 text-center mt-5">
                                    <button type="submit" class="btn btn-gradient btn-lg px-5 me-2">
                                        <span class="uil uil-check me-2"></span> Créer l'événement
                                    </button>
                                    <a href="index.php" class="btn btn-outline-secondary btn-lg px-5">
                                        <span class="uil uil-times me-2"></span> Annuler
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Form Validation Script -->
<script>
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>

<?php require_once __DIR__ . '/../footer.php'; ?> 