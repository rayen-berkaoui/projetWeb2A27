<section class="products-section py-6">
    <div class="container">
        <!-- Section Header -->
        <div class="section-header text-center mb-5">
            <h3 class="fs-8 fs-md-7 text-success fw-bold mb-3">Our Collection</h3>
            <h2 class="display-6 fw-bold mb-4">Featured Plants</h2>
        </div>

        <!-- Products Grid -->
        <div class="row g-4">
            <?php foreach ($products as $product): ?>
            <div class="col-sm-6 col-lg-3">
                <div class="product-card">
                    <div class="product-image">
                        <img src="<?= htmlspecialchars($product['image']) ?>" 
                             alt="<?= htmlspecialchars($product['name']) ?>" 
                             class="img-fluid">
                        
                        <!-- Quick Actions -->
                        <div class="product-actions">
                            <button type="button" 
                                    class="action-btn" 
                                    data-action="quick-view" 
                                    data-product-id="<?= $product['id'] ?>"
                                    title="Quick View">
                                <i class="uil uil-eye"></i>
                            </button>
                            <button type="button" 
                                    class="action-btn" 
                                    data-action="add-to-cart" 
                                    data-product-id="<?= $product['id'] ?>"
                                    title="Add to Cart">
                                <i class="uil uil-shopping-cart-alt"></i>
                            </button>
                            <button type="button" 
                                    class="action-btn" 
                                    data-action="add-to-wishlist" 
                                    data-product-id="<?= $product['id'] ?>"
                                    title="Add to Wishlist">
                                <i class="uil uil-heart"></i>
                            </button>
                        </div>

                        <!-- Product Badge (if any) -->
                        <?php if (!empty($product['badge'])): ?>
                        <span class="product-badge <?= $product['badge'] === 'new' ? 'badge-success' : 'badge-danger' ?>">
                            <?= ucfirst($product['badge']) ?>
                        </span>
                        <?php endif; ?>
                    </div>

                    <div class="product-info">
                        <!-- Product Category -->
                        <div class="product-category">
                            <a href="/greenmind/category/<?= $product['category_slug'] ?? '' ?>" class="text-muted">
                                <?= htmlspecialchars($product['category_name'] ?? 'Plant') ?>
                            </a>
                        </div>

                        <!-- Product Title -->
                        <h3 class="product-title">
                            <a href="/greenmind/product/<?= $product['id'] ?>">
                                <?= htmlspecialchars($product['name']) ?>
                            </a>
                        </h3>

                        <!-- Product Price -->
                        <div class="product-price">
                            <?php if (!empty($product['sale_price'])): ?>
                                <span class="price-new">$<?= number_format($product['sale_price'], 2) ?></span>
                                <span class="price-old">$<?= number_format($product['regular_price'], 2) ?></span>
                            <?php else: ?>
                                <span class="price-regular">$<?= number_format($product['price'], 2) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Load More Button -->
        <?php if (!empty($hasMoreProducts)): ?>
        <div class="text-center mt-5">
            <a href="/greenmind/shop" class="btn btn-outline-success btn-lg">
                View All Products
                <i class="uil uil-arrow-right ms-2"></i>
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>

<style>
/* Product Grid Styles */
.products-section {
    background-color: #f8f9fa;
}

/* Product Card Styles */
.product-card {
    background: #ffffff;
    border-radius: 0.75rem;
    overflow: hidden;
    transition: all 0.3s ease;
    position: relative;
    height: 100%;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
}

/* Product Image Container */
.product-image {
    position: relative;
    overflow: hidden;
    padding-top: 100%; /* 1:1 Aspect Ratio */
    background-color: #f8f9fa;
}

.product-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.5s ease;
}

.product-card:hover .product-image img {
    transform: scale(1.05);
}

/* Quick Action Buttons */
.product-actions {
    position: absolute;
    top: 1rem;
    right: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    opacity: 0;
    transform: translateX(20px);
    transition: all 0.3s ease;
    z-index: 2;
}

.product-card:hover .product-actions {
    opacity: 1;
    transform: translateX(0);
}

.action-btn {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    background: #ffffff;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #333;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
}

.action-btn:hover {
    background: #65B741;
    color: #ffffff;
    transform: translateY(-2px);
}

/* Product Badge */
.product-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    padding: 0.25rem 0.75rem;
    border-radius: 2rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    z-index: 2;
}

.badge-success {
    background: rgba(101, 183, 65, 0.1);
    color: #65B741;
}

.badge-danger {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

/* Product Info */
.product-info {
    padding: 1.25rem;
}

.product-category {
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.product-category a {
    color: #6c757d;
    text-decoration: none;
    transition: all 0.2s ease;
}

.product-category a:hover {
    color: #65B741;
}

.product-title {
    font-size: 1rem;
    margin-bottom: 0.5rem;
    font-weight: 600;
    line-height: 1.4;
}

.product-title a {
    color: #212529;
    text-decoration: none;
    transition: all 0.2s ease;
}

.product-title a:hover {
    color: #65B741;
}

/* Product Price */
.product-price {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.price-new,
.price-regular {
    color: #65B741;
    font-weight: 700;
    font-size: 1.125rem;
}

.price-old {
    color: #6c757d;
    text-decoration: line-through;
    font-size: 0.875rem;
}

/* Button Styles */
.btn-outline-success {
    color: #65B741;
    border-color: #65B741;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-outline-success:hover {
    background-color: #65B741;
    border-color: #65B741;
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(101, 183, 65, 0.2);
}

/* Responsive Styling */
@media (max-width: 767.98px) {
    .product-actions {
        opacity: 1;
        transform: translateX(0);
    }
    
    .product-card {
        margin-bottom: 1rem;
    }
    
    .action-btn {
        width: 2.25rem;
        height: 2.25rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quick View functionality
    document.querySelectorAll('[data-action="quick-view"]').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            
            // Example: Show quick view modal
            console.log('Quick view product ID:', productId);
            // Implement your quick view logic here
        });
    });

    // Add to Cart functionality
    document.querySelectorAll('[data-action="add-to-cart"]').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            
            // Example: Add to cart animation
            this.innerHTML = '<i class="uil uil-check"></i>';
            setTimeout(() => {
                this.innerHTML = '<i class="uil uil-shopping-cart-alt"></i>';
            }, 1500);
            
            // Implement your add to cart AJAX here
            console.log('Add to cart product ID:', productId);
        });
    });

    // Add to Wishlist functionality
    document.querySelectorAll('[data-action="add-to-wishlist"]').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            
            // Example: Toggle wishlist state
            this.classList.toggle('wishlist-active');
            if (this.classList.contains('wishlist-active')) {
                this.innerHTML = '<i class="fas fa-heart"></i>';
            } else {
                this.innerHTML = '<i class="uil uil-heart"></i>';
            }
            
            // Implement your wishlist AJAX here
            console.log('Toggle wishlist for product ID:', productId);
        });
    });
});
</script>

