<section class="hero-section position-relative py-6">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h5 class="text-success fs-8 fs-md-7 fw-bold mb-3">Welcome to GreenMind</h5>
                    <h1 class="display-4 fw-bold mb-4">
                        Discover the Beauty of 
                        <span class="text-success">Natural Plants</span>
                    </h1>
                    <p class="lead text-muted mb-5">
                        Find the perfect plants for your space and get expert advice 
                        on how to care for them. Transform your environment with our 
                        carefully selected collection.
                    </p>
                    <div class="hero-buttons">
                        <a href="/greenmind/shop" class="btn btn-success btn-lg me-3">
                            Shop Now
                            <i class="uil uil-arrow-right ms-2"></i>
                        </a>
                        <a href="/greenmind/about" class="btn btn-outline-dark btn-lg">
                            Learn More
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-5 mt-lg-0">
                <div class="hero-image text-center">
                    <img src="/greenmind/public/assets/img/hero/hero-plants.png" 
                         alt="GreenMind Plants" 
                         class="img-fluid">
                </div>
            </div>
        </div>
    </div>
    <div class="hero-shape position-absolute bottom-0 start-0 w-100">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#f8f9fa" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>
</section>

<style>
.hero-section {
    background-color: #ffffff;
    overflow: hidden;
}

.hero-content h1 {
    color: #03041C;
    line-height: 1.2;
}

.hero-content .text-success {
    color: #65B741 !important;
}

.hero-buttons .btn {
    border-radius: 0.5rem;
    padding: 0.75rem 1.5rem;
    transition: all 0.3s ease;
}

.hero-buttons .btn-success {
    background-color: #65B741;
    border-color: #65B741;
}

.hero-buttons .btn-success:hover {
    background-color: #549635;
    border-color: #549635;
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(101, 183, 65, 0.2);
}

.hero-buttons .btn-outline-dark:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.hero-image img {
    max-height: 500px;
    filter: drop-shadow(0 10px 15px rgba(0, 0, 0, 0.1));
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-15px);
    }
    100% {
        transform: translateY(0px);
    }
}

@media (max-width: 991.98px) {
    .hero-content {
        text-align: center;
    }
    
    .hero-buttons {
        justify-content: center;
        display: flex;
    }
}
</style>

