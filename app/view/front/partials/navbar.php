<nav class="navbar navbar-expand-lg navbar-light sticky-top" data-navbar-on-scroll="data-navbar-on-scroll">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>">
            <img src="<?= BASE_URL ?>assets/img/logos/logo.png" alt="GreenMind" width="150" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                <?php
                // Define menu items with their active state
                $menuItems = [
                    ['name' => 'Home', 'url' => '', 'active' => $activePage === 'home'],
                    ['name' => 'About', 'url' => 'about', 'active' => $activePage === 'about'],
                    ['name' => 'Products', 'url' => 'products', 'active' => $activePage === 'products'],
                    ['name' => 'Blog', 'url' => 'blog', 'active' => $activePage === 'blog'],
                    ['name' => 'Contact', 'url' => 'contact', 'active' => $activePage === 'contact'],
                ];

                // Render menu items
                foreach ($menuItems as $item) {
                    echo '<li class="nav-item">';
                    echo '<a class="nav-link ' . ($item['active'] ? 'active' : '') . '" href="' . BASE_URL . $item['url'] . '">' . $item['name'] . '</a>';
                    echo '</li>';
                }
                ?>
            </ul>
            <div class="d-flex ms-lg-4">
                <a class="btn btn-outline-primary btn-sm" href="<?= BASE_URL ?>cart">
                    <i class="fas fa-shopping-cart me-2"></i> Cart
                    <?php if (isset($cartItemCount) && $cartItemCount > 0): ?>
                        <span class="badge bg-success rounded-pill ms-2"><?= $cartItemCount ?></span>
                    <?php endif; ?>
                </a>
                <?php if (isset($isLoggedIn) && $isLoggedIn): ?>
                    <div class="dropdown ms-3">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user me-1"></i> <?= htmlspecialchars($userName ?? 'Account') ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>profile">My Profile</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>orders">My Orders</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>logout">Logout</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a class="btn btn-sm btn-primary ms-3" href="<?= BASE_URL ?>login">
                        <i class="fas fa-sign-in-alt me-1"></i> Login
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

