<?php
// Ensure we have the featured feedback data from our controller
$featuredFeedback = $viewData['featuredFeedback'] ?? [];
$productReviews = $viewData['productReviews'] ?? [];

// Function to generate star ratings in Aranyak style
function displayStarRating($rating) {
    $output = '<div class="rating mb-2">';
    
    // Full stars
    for ($i = 1; $i <= floor($rating); $i++) {
        $output .= '<i class="fas fa-star text-success"></i>';
    }
    
    // Half star if applicable
    if ($rating - floor($rating) >= 0.5) {
        $output .= '<i class="fas fa-star-half-alt text-success"></i>';
        $i++;
    }
    
    // Empty stars
    for ($i = ceil($rating); $i <= 5; $i++) {
        $output .= '<i class="far fa-star text-success"></i>';
    }
    
    $output .= '</div>';
    return $output;
}

// Calculate average rating if we have reviews
$averageRating = 0;
$totalReviews = count($featuredFeedback) + count($productReviews);

if ($totalReviews > 0) {
    $ratingSum = 0;
    foreach ($featuredFeedback as $feedback) {
        $ratingSum += $feedback['rating'];
    }
    foreach ($productReviews as $review) {
        $ratingSum += $review['rating'];
    }
    $averageRating = $ratingSum / $totalReviews;
}
?>

<section class="testimonials-section py-6 bg-light overflow-hidden position-relative">
    <div class="container">
        <!-- Section Header -->
        <div class="row mb-5">
            <div class="col-lg-6 mx-auto text-center">
                <h5 class="text-success fs-8 fs-md-7 fw-bold mb-3">Testimonials</h5>
                <h2 class="display-5 fw-bold mb-4">What Our Customers Say</h2>
                <div class="rating-summary d-flex justify-content-center align-items-center">
                    <?= displayStarRating($averageRating) ?>
                    <span class="ms-2 fw-semibold"><?= number_format($averageRating, 1) ?>/5</span>
                    <span class="ms-2 text-muted">(<?= $totalReviews ?> reviews)</span>
                </div>
            </div>
        </div>
        
        <!-- Testimonials Slider -->
        <div class="row">
            <div class="col-12">
                <div class="swiper testimonials-swiper">
                    <div class="swiper-wrapper">
                        <?php foreach ($featuredFeedback as $feedback): ?>
                        <div class="swiper-slide">
                            <div class="testimonial-card">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body p-4 p-lg-5">
                                        <!-- Star Rating -->
                                        <?= displayStarRating($feedback['rating']) ?>
                                        
                                        <!-- Testimonial Content -->
                                        <div class="testimonial-content mb-4">
                                            <p class="testimonial-text mb-0 fs-5 fst-italic text-secondary">
                                                "<?= htmlspecialchars(strlen($feedback['message']) > 150 ? substr($feedback['message'], 0, 147) . '...' : $feedback['message']) ?>"
                                            </p>
                                        </div>
                                        
                                        <!-- Customer Info -->
                                        <div class="customer-info d-flex align-items-center">
                                            <div class="customer-avatar bg-success-subtle rounded-circle d-flex align-items-center justify-content-center">
                                                <span class="text-success fw-bold">
                                                    <?= strtoupper(substr($feedback['name'], 0, 1)) ?>
                                                </span>
                                            </div>
                                            <div class="customer-details ms-3">
                                                <h6 class="customer-name mb-0"><?= htmlspecialchars($feedback['name']) ?></h6>
                                                <p class="customer-title mb-0 small text-muted">Verified Customer</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if (empty($featuredFeedback)): ?>
                        <div class="swiper-slide">
                            <div class="testimonial-card">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body p-4 p-lg-5">
                                        <!-- Default Star Rating -->
                                        <?= displayStarRating(5) ?>
                                        
                                        <!-- Testimonial Content -->
                                        <div class="testimonial-content mb-4">
                                            <p class="testimonial-text mb-0 fs-5 fst-italic text-secondary">
                                                "I've been using GreenMind plants to decorate my home for the past 3 months. The plants are healthy and the customer service is exceptional!"
                                            </p>
                                        </div>
                                        
                                        <!-- Customer Info -->
                                        <div class="customer-info d-flex align-items-center">
                                            <div class="customer-avatar bg-success-subtle rounded-circle d-flex align-items-center justify-content-center">
                                                <span class="text-success fw-bold">J</span>
                                            </div>
                                            <div class="customer-details ms-3">
                                                <h6 class="customer-name mb-0">Jane Smith</h6>
                                                <p class="customer-title mb-0 small text-muted">Verified Customer</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="testimonial-card">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body p-4 p-lg-5">
                                        <!-- Default Star Rating -->
                                        <?= displayStarRating(4.5) ?>
                                        
                                        <!-- Testimonial Content -->
                                        <div class="testimonial-content mb-4">
                                            <p class="testimonial-text mb-0 fs-5 fst-italic text-secondary">
                                                "The Monstera I ordered arrived in perfect condition. It has thrived in my living room and gets compliments from everyone who visits!"
                                            </p>
                                        </div>
                                        
                                        <!-- Customer Info -->
                                        <div class="customer-info d-flex align-items-center">
                                            <div class="customer-avatar bg-success-subtle rounded-circle d-flex align-items-center justify-content-center">
                                                <span class="text-success fw-bold">R</span>
                                            </div>
                                            <div class="customer-details ms-3">
                                                <h6 class="customer-name mb-0">Robert Johnson</h6>
                                                <p class="customer-title mb-0 small text-muted">Verified Customer</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Swiper Navigation -->
                    <div class="swiper-pagination d-md-none mt-4"></div>
                    <div class="swiper-button-next testimonial-next"></div>
                    <div class="swiper-button-prev testimonial-prev"></div>
                </div>
            </div>
        </div>
        
        <?php if (!empty($productReviews)): ?>
        <!-- Product-specific Reviews -->
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="h4 fw-bold mb-4">Product Reviews</h3>
                <div class="product-reviews">
                    <?php foreach ($productReviews as $index => $review): ?>
                    <div class="product-review <?= $index > 0 ? 'mt-4 pt-4 border-top' : '' ?>">
                        <div class="row">
                            <?php if (!empty($review['product_image'])): ?>
                            <div class="col-md-2 col-lg-1">
                                <img src="<?= htmlspecialchars($review['product_image']) ?>" alt="<?= htmlspecialchars($review['product_name'] ?? 'Product') ?>" class="img-fluid rounded">
                            </div>
                            <div class="col-md-10 col-lg-11">
                            <?php else: ?>
                            <div class="col-12">
                            <?php endif; ?>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="product-name mb-0"><?= htmlspecialchars($review['product_name'] ?? 'Product Review') ?></h5>
                                    <small class="text-muted"><?= date('M d, Y', strtotime($review['created_at'])) ?></small>
                                </div>
                                <?= displayStarRating($review['rating']) ?>
                                <p class="review-content mb-2"><?= htmlspecialchars($review['message']) ?></p>
                                <div class="reviewer-info">
                                    <small class="text-muted">By <?= htmlspecialchars($review['name']) ?></small>
                                </div>
                                
                                <?php if (!empty($review['admin_response'])): ?>
                                <div class="admin-response mt-3 p-3 bg-light rounded">
                                    <p class="mb-1 fst-italic"><?= htmlspecialchars($review['admin_response']) ?></p>
                                    <small class="text-muted">- GreenMind Team</small>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Call to Action -->
        <div class="row mt-5">
            <div class="col-lg-8 mx-auto text-center">
                <p class="mb-4">Share your experience with our plants and services</p>
                <a href="#feedback-form" class="btn btn-success btn-lg px-4 py-2">
                    Leave Your Feedback
                </a>
            </div>
        </div>
    </div>
    
    <!-- Background Decoration -->
    <div class="leaf-decoration leaf-top-left"></div>
    <div class="leaf-decoration leaf-bottom-right"></div>
</section>

<style>
/* Testimonials Section */
.testimonials-section {
    position: relative;
    z-index: 1;
}

/* Testimonial Card */
.testimonial-card {
    height: 100%;
    padding: 15px;
}

.testimonial-card .card {
    border-radius: 1rem;
    transition: all 0.3s ease;
}

.testimonial-card .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.1) !important;
}

/* Star Rating */
.rating i {
    color: #65B741;
    margin-right: 2px;
}

/* Customer Avatar */
.customer-avatar {
    width: 45px;
    height: 45px;
    font-size: 18px;
}

/* Swiper Navigation */
.swiper-button-next,
.swiper-button-prev {
    width: 40px;
    height: 40px;
    background-color: #65B741;
    border-radius: 50%;
    color: white;
}

.swiper-button-next:after,
.swiper-button-prev:after {
    font-size: 18px;
}

.swiper-button-next:hover,
.swiper-button-prev:hover {
    background-color: #549635;
}

.swiper-pagination-bullet-active {
    background-color: #65B741;
}

/* Leaf Decorations */
.leaf-decoration {
    position: absolute;
    width: 150px;
    height: 150px;
    background-size: contain;
    background-repeat: no-repeat;
    z-index: -1;
    opacity: 0.2;
}

.leaf-top-left {
    top: 0;
    left: 0;
    background-image: url('assets/img/icons/leaf-decoration-1.svg');
}

.leaf-bottom-right {
    bottom: 0;
    right: 0;
    background-image: url('assets/img/icons/leaf-decoration-2.svg');
}

/* Product Reviews */
.product-reviews {
    background-color: white;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

/* Admin Response */
.admin-response {
    border-left: 3px solid #65B741;
}

@media (max-width: 767.98px) {
    .testimonial-card {
        padding: 10px;
    }
    
    .testimonial-card .card-body {
        padding: 1.5rem;
    }
    
    .leaf-decoration {
        width: 100px;
        height: 100px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Swiper
    const testimonialsSwiper = new Swiper('.testimonials-swiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.testimonial-next',
            prevEl: '.testimonial-prev',
        },
        breakpoints: {
            768: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            992: {
                slidesPerView: 3,
                spaceBetween: 30,
            }
        },
        effect: 'slide',
        speed: 800,
        grabCursor: true
    });
    
    // Add hover effect to testimonial cards
    const testimonialCards = document.querySelectorAll('.testimonial-card');
    testimonialCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.querySelector('.card').style.transform = 'translateY(-8px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.querySelector('.card').style.transform = 'translateY(-5px)';
        });
    });
    
    // Pause autoplay on testimonial interaction
    const swiperContainer = document.querySelector('.testimonials-swiper');
    if (swiperContainer) {
        swiperContainer.addEventListener('mouseenter', function() {
            testimonialsSwiper.autoplay.stop();
        });
        
        swiperContainer.addEventListener('mouseleave', function() {
            testimonialsSwiper.autoplay.start();
        });
    }
    
    // Smooth scroll to feedback form when clicking the CTA button
    const feedbackCta = document.querySelector('a[href="#feedback-form"]');
    if (feedbackCta) {
        feedbackCta.addEventListener('click', function(e) {
            e.preventDefault();
            const feedbackForm = document.getElementById('feedback-form');
            if (feedbackForm) {
                feedbackForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                // If specific ID not found, try to find the section by class
                const feedbackFormSection = document.querySelector('.feedback-form-section');
                if (feedbackFormSection) {
                    feedbackFormSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        });
    }
});

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
}
</style>

