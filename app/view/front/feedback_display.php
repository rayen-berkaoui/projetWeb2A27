<?php
/**
 * Front office feedback display template
 * Displays approved customer feedbacks with Aranyak theme styling
 */
?>

<!-- Required CSS -->
<link rel="stylesheet" href="/greenmind/public/assets/css/bootstrap.min.css">
<link rel="stylesheet" href="/greenmind/public/assets/css/theme.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Reviews Section -->
<!-- Main Content Section -->
<section class="feedback-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header -->
                <div class="text-center mb-5">
                    <h2 class="mb-3">Customer Reviews</h2>
                    <?php if ($viewData['totalCount'] > 0): ?>
                        <div class="average-rating mb-4">
                            <div class="stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php if ($i <= round($viewData['averageRating'])): ?>
                                        <i class="fas fa-star text-warning"></i>
                                    <?php else: ?>
                                        <i class="far fa-star text-warning"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            <span class="ms-2">
                                <?= number_format($viewData['averageRating'], 1) ?> 
                                (<?= $viewData['totalCount'] ?> reviews)
                            </span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Feedback List -->
                <div class="feedback-list">
                    <?php if (empty($viewData['feedbacks'])): ?>
                        <div class="alert alert-info text-center">
                            <p>No reviews yet. Be the first to share your experience!</p>
                            <a href="?action=form" class="btn btn-primary">Write a Review</a>
                        </div>
                    <?php else: ?>
                        <?php foreach ($viewData['feedbacks'] as $feedback): ?>
                            <div class="feedback-item mb-4 p-4 border rounded">
                                <div class="d-flex justify-content-between mb-2">
                                    <h5 class="mb-0">
                                        <?= htmlspecialchars($feedback['prenom'] . ' ' . mb_substr($feedback['nom'], 0, 1) . '.' ?>
                                    </h5>
                                    <div class="rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= $feedback['note']): ?>
                                                <i class="fas fa-star text-warning"></i>
                                            <?php else: ?>
                                                <i class="far fa-star text-warning"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <p class="text-muted small mb-2">
                                    <?= date('F j, Y', strtotime($feedback['created_at'])) ?>
                                </p>
                                <p class="mb-0">
                                    <?= nl2br(htmlspecialchars($feedback['commentaire'])) ?>
                                </p>
                            </div>
                        <?php endforeach; ?>

                        <!-- Pagination -->
                        <?php if ($viewData['totalCount'] > $viewData['perPage']): ?>
                            <nav class="mt-4">
                                <ul class="pagination justify-content-center">
                                    <?php if ($viewData['currentPage'] > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" 
                                               href="?action=display&page=<?= $viewData['currentPage'] - 1 ?>">
                                                Previous
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = 1; $i <= ceil($viewData['totalCount'] / $viewData['perPage']); $i++): ?>
                                        <li class="page-item <?= $i == $viewData['currentPage'] ? 'active' : '' ?>">
                                            <a class="page-link" href="?action=display&page=<?= $i ?>">
                                                <?= $i ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <?php if ($viewData['currentPage'] < ceil($viewData['totalCount'] / $viewData['perPage'])): ?>
                                        <li class="page-item">
                                            <a class="page-link" 
                                               href="?action=display&page=<?= $viewData['currentPage'] + 1 ?>">
                                                Next
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section> 
<section id="reviews" class="review-section py-6 mb-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Section Header -->
                <div class="section-header text-center mb-5">
                    <h3 class="mb-3 fs-8 fs-md-7 fs-xxl-6 text-success fw-bold">Customer Reviews</h3>
                    <h2 class="fs-5 fs-md-4 fs-xxl-3 text-secondary fw-light mb-4">
                        What Our <span class="fw-bold">Customers Say</span>
                    </h2>

                    <?php if ($viewData['totalCount'] > 0): ?>
                        <!-- Average Rating Display -->
                        <div class="average-rating-container mb-5">
                            <div class="average-rating d-inline-flex align-items-center bg-light rounded px-4 py-3 shadow-sm">
                                <div class="rating-stars me-3">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?php if ($i <= round($viewData['averageRating'])): ?>
                                            <i class="fa-solid fa-star text-success"></i>
                                        <?php else: ?>
                                            <i class="fa-regular fa-star text-success"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                                <div class="rating-text">
                                    <span class="rating-value fs-4 fw-bold"><?= number_format($viewData['averageRating'], 1) ?></span>
                                    <span class="rating-count text-muted fs-6 ms-2">
                                        (<?= $viewData['totalCount'] ?> <?= $viewData['totalCount'] > 1 ? 'reviews' : 'review' ?>)
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Review Cards -->
                <div class="row g-4">
                    <?php if (empty($viewData['feedbacks'])): ?>
                        <div class="col-12">
                            <div class="text-center py-5 bg-light rounded shadow-sm">
                                <div class="mb-4">
                                    <i class="fa-regular fa-comment-dots fa-3x text-success opacity-75"></i>
                                </div>
                                <h4 class="fs-7 text-secondary">No Reviews Yet</h4>
                                <p class="text-muted fs-9">Be the first to share your experience with us!</p>
                                <a href="?action=form" class="btn btn-success btn-sm mt-3">Write a Review</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="<?= count($viewData['feedbacks']) > 2 ? 'col-12' : 'col-lg-8' ?>">
                            <div class="review-cards">
                                <?php foreach ($viewData['feedbacks'] as $index => $feedback): ?>
                                    <div class="review-card bg-white rounded p-4 mb-4 shadow-sm hover-shadow transition-all">
                                        <div class="d-flex justify-content-between align-items-start mb-3 pb-2 border-bottom-light">
                                            <div class="user-info">
                                                <h2 class="text-success fs-9 fs-md-8 fw-bold mb-1">
                                                    <?= htmlspecialchars($feedback['prenom'] . ' ' . mb_substr($feedback['nom'], 0, 1) . '.') ?>
                                                </h2>
                                                <p class="text-muted fs-10 mb-0">
                                                    <?= date('F j, Y', strtotime($feedback['created_at'])) ?>
                                                </p>
                                            </div>
                                            <div class="rating">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <?php if ($i <= $feedback['note']): ?>
                                                        <i class="fa-solid fa-star text-success"></i>
                                                    <?php else: ?>
                                                        <i class="fa-regular fa-star text-success"></i>
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                        <div class="review-content">
                                            <p class="text-black fs-9 line-clamp-3 mb-0">
                                                <?= nl2br(htmlspecialchars($feedback['commentaire'])) ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Pagination -->
                            <?php 
                            $totalPages = ceil($viewData['totalCount'] / ($viewData['perPage'] ?? 5));
                            $currentPage = $_GET['page'] ?? 1;
                            
                            if ($totalPages > 1): 
                            ?>
                            <div class="pagination-container d-flex justify-content-center mt-5">
                                <nav aria-label="Customer reviews pagination">
                                    <ul class="pagination">
                                        <?php if ($currentPage > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?page=<?= $currentPage - 1 ?>&action=display" aria-label="Previous">
                                                    <i class="fa-solid fa-chevron-left small"></i>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        
                                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                            <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                                <a class="page-link" href="?page=<?= $i ?>&action=display"><?= $i ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        
                                        <?php if ($currentPage < $totalPages): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="?page=<?= $currentPage + 1 ?>&action=display" aria-label="Next">
                                                    <i class="fa-solid fa-chevron-right small"></i>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            </div>
                            <?php endif; ?>
                        </div>

                        <?php if (count($viewData['feedbacks']) <= 2): ?>
                            <div class="col-lg-4 d-none d-lg-block">
                                <div class="text-center">
                                    <img src="/greenmind/public/assets/img/illustrations/review-illustration.png" alt="Reviews" class="img-fluid" style="max-height: 300px;">
                                    <div class="mt-4">
                                        <a href="?action=form" class="btn btn-success">Share Your Experience</a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Enhanced Custom Styles for Aranyak Theme -->
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
.review-section {
    background-color: var(--gray-light);
    position: relative;
}

/* Typography */
.text-success {
    color: var(--green-primary) !important;
}

h2.text-success, h3.text-success {
    font-weight: 700;
}

/* Review Cards */
.review-card {
    transition: var(--transition);
    border: 1px solid var(--gray-border);
    position: relative;
    overflow: hidden;
}

.review-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md) !important;
}

.review-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background-color: var(--green-primary);
    opacity: 0;
    transition: var(--transition);
}

.review-card:hover::before {
    opacity: 1;
}

.border-bottom-light {
    border-bottom: 1px solid var(--gray-border);
}

/* Rating Stars */
.rating i {
    color: var(--green-primary);
    margin-right: 2px;
}

.average-rating {
    background: var(--green-light);
    border: 1px solid var(--green-border);
    box-shadow: var(--shadow-sm);
}

.average-rating .rating-value {
    position: relative;
    top: -1px;
}

/* Buttons */
.btn-success {
    background-color: var(--green-primary);
    border-color: var(--green-primary);
    transition: var(--transition);
}

.btn-success:hover {
    background-color: var(--green-secondary);
    border-color: var(--green-secondary);
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}

/* Pagination */
.pagination {
    margin-bottom: 0;
}

.pagination .page-link {
    color: var(--green-primary);
    border-color: var(--gray-border);
    transition: var(--transition);
}

.pagination .page-item.active .page-link {
    background-color: var(--green-primary);
    border-color: var(--green-primary);
    color: white;
}

.pagination .page-link:hover {
    background-color: var(--green-light);
    border-color: var(--green-border);
}

.pagination .page-link:focus {
    box-shadow: 0 0 0 0.25rem rgba(101, 183, 65, 0.25);
}

/* Utilities */
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.transition-all {
    transition: var(--transition);
}

.hover-shadow:hover {
    box-shadow: var(--shadow-md) !important;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .review-card {
        padding: 1rem !important;
    }
    
    .rating i {
        font-size: 0.8rem;
    }
    
    .average-rating {
        padding: 0.75rem !important;
    }
    
    h3.fs-8 {
        font-size: 1.5rem !important;
    }
    
    h2.fs-5 {
        font-size: 1.25rem !important;
    }
}
</style>

<!-- Include required JavaScript -->
<script src="/greenmind/public/assets/js/bootstrap.bundle.min.js"></script>
<script>
    // Initialize any JavaScript functionality for reviews if needed
    document.addEventListener('DOMContentLoaded', function() {
        // Add smooth hover effect to review cards
        const reviewCards = document.querySelectorAll('.review-card');
        reviewCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transition = 'all 0.3s ease';
            });
        });
    });
</script>
