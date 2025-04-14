<?php

?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="feedback-error p-4 text-center bg-white rounded shadow-sm">
                <div class="error-icon mb-3">
                    <span class="text-danger" style="font-size: 3rem;">⚠️</span>
                </div>
                <h3 class="text-danger fs-8 fw-bold mb-3">Erreur</h3>
                <p class="text-muted mb-0"><?= htmlspecialchars($error ?? 'Une erreur est survenue lors du chargement des avis.') ?></p>
                <div class="mt-4">
                    <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-outline-success">Réessayer</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
/**
 * Error template for feedback display
 */
?>
<div class="feedback-error">
    <div class="error-icon">⚠️</div>
    <h3>Erreur</h3>
    <p><?php echo htmlspecialchars($error ?? 'Une erreur est survenue.'); ?></p>
</div>

<style>
.feedback-error {
    max-width: 600px;
    margin: 2rem auto;
    text-align: center;
    padding: 2rem;
    background-color: #fff3f3;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border: 1px solid #ffcaca;
}

.error-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.feedback-error h3 {
    color: #e74c3c;
    margin-bottom: 1rem;
}

.feedback-error p {
    color: #555;
}
</style>

