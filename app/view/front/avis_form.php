<?php
/**
 * Front office feedback form template
 * Enhanced with Aranyak styling
 */
?>

<!-- Required CSS -->
<link rel="stylesheet" href="/greenmind/public/assets/css/bootstrap.min.css">
<link rel="stylesheet" href="/greenmind/public/assets/css/theme.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Feedback Form Section -->
<section id="feedback-form" class="feedback-form-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <!-- Section Header -->
                <div class="text-center mb-4">
                    <h3 class="mb-3 fs-8 fs-md-7 text-success fw-bold">Share Your Experience</h3>
                    <p class="text-muted fs-9">We value your feedback</p>
                </div>

                <!-- Feedback Form Card -->
<div class="card border-0 shadow-sm hover-shadow">
    <div class="card-body p-4 p-md-5">
        <?php if (isset($_SESSION['feedback_message'])): ?>
            <div class="alert alert-<?= $_SESSION['feedback_message']['type'] ?>">
                <?= $_SESSION['feedback_message']['text'] ?>
            </div>
            <?php unset($_SESSION['feedback_message']); ?>
        <?php endif; ?>
        
        <form method="POST" action="index.php?action=submit" id="feedbackForm">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            
            <!-- Name Field -->
            <div class="mb-3">
                <label for="name" class="form-label text-secondary mb-2">Your Name</label>
                <input type="text" 
                       class="form-control bg-light border-0" 
                       id="name" 
                       name="name" 
                       required>
                <div class="invalid-feedback">Please enter your name</div>
            </div>

            <!-- Star Rating -->
            <div class="mb-3">
                <label class="form-label text-secondary mb-2">Rating</label>
                <div class="star-rating-container">
                    <div class="star-rating d-flex align-items-center">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <button type="button" 
                                    class="btn-star p-0 bg-transparent border-0 me-2" 
                                    data-rating="<?= $i ?>">
                                <i class="far fa-star fs-4 text-success"></i>
                            </button>
                        <?php endfor; ?>
                    </div>
                    <input type="hidden" name="rating" id="rating" required>
                    <div class="rating-feedback text-danger small mt-1 d-none">Please select a rating</div>
                </div>
            </div>

            <!-- Comment -->
            <div class="mb-4">
                <label for="message" class="form-label text-secondary mb-2">Comment</label>
                <textarea class="form-control bg-light border-0" 
                          id="message" 
                          name="message" 
                          rows="4" 
                          required></textarea>
                <div class="invalid-feedback">Please share your feedback</div>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="btn btn-success px-4 py-2">
                    Submit Review
                </button>
            </div>
        </form>
    </div>
</div>


<!-- Custom Styles -->
<style>
:root {
    --green-primary: #65B741;
    --green-hover: #549635;
    --green-light: rgba(101, 183, 65, 0.1);
    --gray-light: #f8f9fa;
    --gray-border: rgba(0,0,0,0.05);
    --transition: all 0.3s ease;
}

/* Section Styling */
.feedback-form-section {
    background-color: var(--gray-light);
}

/* Typography */
.text-success {
    color: var(--green-primary) !important;
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
    min-height: 40px;
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

/* Button Styling */
.btn-success {
    background-color: var(--green-primary);
    border-color: var(--green-primary);
    transition: var(--transition);
}

.btn-success:hover {
    background-color: var(--green-hover);
    border-color: var(--green-hover);
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
}

/* Card Styling */
.card {
    border-radius: 0.75rem;
    overflow: hidden;
    transition: var(--transition);
}

.hover-shadow:hover {
    box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.08) !important;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .form-control {
        padding: 0.625rem 0.875rem;
    }

    .btn-success {
        padding: 0.5rem 1.25rem;
    }
}
</style>

<!-- Star Rating Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Star Rating Implementation
    const starButtons = document.querySelectorAll('.btn-star');
    const ratingInput = document.getElementById('rating');
    const ratingFeedback = document.querySelector('.rating-feedback');
    const form = document.getElementById('feedbackForm');

    // Star click handler
    starButtons.forEach(button => {
        button.addEventListener('click', function() {
            const rating = this.dataset.rating;
            ratingInput.value = rating;
            ratingFeedback.classList.add('d-none');

            // Update star display
            starButtons.forEach((btn, index) => {
                const star = btn.querySelector('i');
                if (index < rating) {
                    star.classList.remove('far');
                    star.classList.add('fas');
                } else {
                    star.classList.remove('fas');
                    star.classList.add('far');
                }
            });
        });
    });

    // Form validation
    form.addEventListener('submit', function(event) {
        let isValid = true;
        
        // Validate rating
        if (!ratingInput.value) {
            ratingFeedback.classList.remove('d-none');
            isValid = false;
        }

        // Validate other fields
        form.querySelectorAll('[required]').forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            event.preventDefault();
            // Scroll to first error
            const firstError = form.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

    // Real-time validation
    form.querySelectorAll('[required]').forEach(field => {
        field.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('is-invalid');
            }
        });
    });
});
</script>
