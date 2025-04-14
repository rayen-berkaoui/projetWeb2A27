<section class="feedback-form-section py-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Section Header -->
                <div class="text-center mb-5">
                    <h3 class="fs-8 fs-md-7 text-success fw-bold mb-3">Share Your Experience</h3>
                    <h2 class="display-6 fw-bold mb-4">We Value Your Feedback</h2>
                    <p class="lead text-muted">Your opinion helps us improve our plant services</p>
                </div>

                <!-- Feedback Form -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <?php if (isset($viewData['message'])): ?>
                            <div class="alert alert-<?= $viewData['message']['type'] ?> mb-4">
                                <?= $viewData['message']['text'] ?>
                            </div>
                        <?php endif; ?>

                        <form action="index.php" method="POST" id="feedbackForm" class="needs-validation" novalidate>
                            <div class="row g-4">
                                <!-- First Name -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="prenom" class="form-label text-secondary mb-2">First Name</label>
                                        <input type="text" 
                                               class="form-control form-control-lg bg-light border-0" 
                                               id="prenom" 
                                               name="prenom" 
                                               required>
                                        <div class="invalid-feedback">Please enter your first name</div>
                                    </div>
                                </div>

                                <!-- Last Name -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nom" class="form-label text-secondary mb-2">Last Name</label>
                                        <input type="text" 
                                               class="form-control form-control-lg bg-light border-0" 
                                               id="nom" 
                                               name="nom" 
                                               required>
                                        <div class="invalid-feedback">Please enter your last name</div>
                                    </div>
                                </div>

                                <!-- Rating -->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label text-secondary mb-2">Your Rating</label>
                                        <div class="star-rating-container">
                                            <div class="star-rating d-flex align-items-center">
                                                <?php for($i = 1; $i <= 5; $i++): ?>
                                                    <button type="button" 
                                                            class="btn-star p-0 bg-transparent border-0 me-2" 
                                                            data-rating="<?= $i ?>">
                                                        <i class="far fa-star fs-4 text-success"></i>
                                                    </button>
                                                <?php endfor; ?>
                                                <div class="rating-text ms-3 text-muted"></div>
                                            </div>
                                            <input type="hidden" name="note" id="rating" required>
                                            <div class="rating-feedback text-danger small mt-1 d-none">Please select a rating</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Comment -->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="commentaire" class="form-label text-secondary mb-2">Your Feedback</label>
                                        <textarea class="form-control form-control-lg bg-light border-0" 
                                                  id="commentaire" 
                                                  name="commentaire" 
                                                  rows="5" 
                                                  required></textarea>
                                        <div class="invalid-feedback">Please share your thoughts with us</div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="col-12 text-center mt-4">
                                    <button type="submit" name="submit" class="btn btn-success btn-lg px-5">
                                        Submit Feedback
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
:root {
    --green-primary: #65B741;
    --green-secondary: #549635;
    --green-light: rgba(101, 183, 65, 0.1);
    --green-border: rgba(101, 183, 65, 0.2);
    --gray-light: #f8f9fa;
    --gray-border: rgba(0,0,0,0.05);
    --text-dark: #333333;
    --text-muted: #6c757d;
    --shadow-sm: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
    --shadow-md: 0 0.5rem 1rem rgba(0,0,0,0.1);
    --transition: all 0.3s ease;
}

/* Section Styling */
.feedback-form-section {
    background-color: var(--gray-light);
    position: relative;
}

/* Form Styling */
.card {
    border-radius: 1rem;
    overflow: hidden;
    transition: var(--transition);
}

.card:hover {
    box-shadow: var(--shadow-md) !important;
}

/* Form Controls */
.form-control {
    padding: 0.75rem 1rem;
    transition: var(--transition);
}

.form-control:focus {
    box-shadow: 0 0 0 0.25rem var(--green-light);
    border-color: var(--green-primary);
}

.form-control.bg-light {
    background-color: white !important;
    border: 1px solid var(--gray-border);
}

/* Star Rating */
.star-rating {
    min-height: 48px;
}

.btn-star {
    cursor: pointer;
    transition: var(--transition);
}

.btn-star:hover i,
.btn-star.active i {
    font-weight: 900;
    transform: scale(1.1);
    color: var(--green-primary);
}

.btn-star i {
    transition: var(--transition);
}

.rating-text {
    min-width: 80px;
    font-weight: 500;
}

/* Button Styling */
.btn-success {
    background-color: var(--green-primary);
    border-color: var(--green-primary);
    transition: var(--transition);
    font-weight: 500;
}

.btn-success:hover {
    background-color: var(--green-secondary);
    border-color: var(--green-secondary);
    transform: translateY(-3px);
    box-shadow: 0 0.5rem 1rem rgba(101, 183, 65, 0.2);
}

/* Alert Styling */
.alert-success {
    background-color: var(--green-light);
    border-color: var(--green-border);
    color: var(--green-secondary);
}

/* Responsive Styling */
@media (max-width: 767.98px) {
    .form-control {
        padding: 0.625rem 0.875rem;
    }
    
    .btn-success {
        padding: 0.625rem 1.25rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Star Rating Implementation
    const starButtons = document.querySelectorAll('.btn-star');
    const ratingInput = document.getElementById('rating');
    const ratingText = document.querySelector('.rating-text');
    const ratingFeedback = document.querySelector('.rating-feedback');
    const ratingTexts = ['Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];

    starButtons.forEach(button => {
        button.addEventListener('click', function() {
            const rating = this.dataset.rating;
            ratingInput.value = rating;

            // Update stars visualization
            starButtons.forEach((btn, index) => {
                const star = btn.querySelector('i');
                if (index < rating) {
                    star.classList.remove('far');
                    star.classList.add('fas');
                    btn.classList.add('active');
                } else {
                    star.classList.remove('fas');
                    star.classList.add('far');
                    btn.classList.remove('active');
                }
            });

            // Update rating text and hide feedback
            ratingText.textContent = ratingTexts[rating - 1];
            ratingFeedback.classList.add('d-none');
        });
    });

    // Form Validation
    const form = document.getElementById('feedbackForm');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity() || !ratingInput.value) {
            event.preventDefault();
            event.stopPropagation();
            
            // Show rating feedback if no rating selected
            if (!ratingInput.value) {
                ratingFeedback.classList.remove('d-none');
            }
        }
        
        form.classList.add('was-validated');
    });

    // Star Rating Hover Effect
    starButtons.forEach((button, buttonIndex) => {
        // On hover: fill stars up to current hovered star
        button.addEventListener('mouseenter', function() {
            const rating = parseInt(this.dataset.rating);
            
            // Only change appearance if not already set
            if (!ratingInput.value) {
                starButtons.forEach((btn, index) => {
                    const star = btn.querySelector('i');
                    if (index < rating) {
                        star.classList.remove('far');
                        star.classList.add('fas');
                    }
                });
            }
        });

        // On leave: restore original state if no rating selected
        button.addEventListener('mouseleave', function() {
            if (!ratingInput.value) {
                starButtons.forEach(btn => {
                    const star = btn.querySelector('i');
                    star.classList.remove('fas');
                    star.classList.add('far');
                });
            }
        });
    });
});
</script>

