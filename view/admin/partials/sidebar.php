<aside class="sidebar" style="
    position: fixed;
    left: 0;
    top: 0;
    width: 250px;
    height: 100vh;
    background: #2c3e50;
    color: white;
    font-family: Arial;
    padding: 20px;
    box-shadow: 5px 0 15px rgba(0,0,0,0.1);
    z-index: 1000;
    border-right: 4px solid #3498db;
">
    <!-- Sidebar Header -->
    <div style="
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #34495e;
        text-align: center;
    ">
        <img src="/lezm/view/assets/img/logo.png" alt="Logo" style="width: 120px; height: auto;">
    </div>

    <!-- Navigation Menu -->
    <nav>
        <ul style="list-style: none; padding: 0; margin: 0;">
            <!-- Dashboard (no submenu) -->
            <li style="margin-bottom: 10px;">
                <a href="/lezm/admin/dashboard" style="
                    display: block;
                    background: <?= (isset($active_menu) && $active_menu === 'dashboard') ? '#3498db' : '#34495e' ?>;
                    color: white;
                    padding: 12px 15px;
                    border-radius: 4px;
                    text-decoration: none;
                    font-weight: bold;
                ">📊 Dashboard</a>
            </li>
             <li style="margin-bottom: 10px;">
                <a href="/lezm/admin/produit" style="
                    display: block;
                    background: <?= (isset($active_menu) && $active_menu === 'produit') ? '#3498db' : '#34495e' ?>;
                    color: white;
                    padding: 12px 15px;
                    border-radius: 4px;
                    text-decoration: none;
                    font-weight: bold;
                ">📦 Produits</a>
            </li>
             


            <!-- Reusable nav-section with submenu -->
            <?php
            // Enable error reporting for debugging
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);

            $sections = [
                'marketing' => ['📣 Marketing', [
                    ['label' => '📈 Campaigns', 'url' => '/lezm/admin/marketing', 'key' => 'list'],
                ]],
                 'produit' => ['📦 Produit', [
                    ['label' => '📦 View Produits', 'url' => '/lezm/admin/produit', 'key' => 'list'],
                    ['label' => '➕ Add Produit', 'url' => '/lezm/admin/produit/create', 'key' => 'create'],
                    ['label' => 'Categorie', 'url' => '/lezm/admin/categorie', 'key' => 'list']
                ]],
                'forums' => ['💬 Forums', [
                    ['label' => '📑 Forum List', 'url' => '/lezm/admin/forums', 'key' => 'list'],
                    ['label' => '➕ Create Forum', 'url' => '/lezm/admin/forums/create', 'key' => 'create']
                ]],
                'articles' => ['📰 Articles', [
                    ['label' => '🔍 View Articles', 'url' => '/lezm/admin/articles', 'key' => 'list'],
                    ['label' => '➕ Add Article', 'url' => '/lezm/admin/articles/create', 'key' => 'create'],
                    ['label' => '📋 List Types', 'url' => '/lezm/admin/articles/listTypes', 'key' => 'list_types'],
                    ['label' => '➕ Add Type', 'url' => '/lezm/admin/articles/createType', 'key' => 'create_type']
                ]],
                'avis' => ['⭐ Avis', [
                    ['label' => '📃 Avis List', 'url' => '/lezm/admin/avis', 'key' => 'list'],
                ]],
               
                'evenements' => ['📅 Événements', [
                    ['label' => '📅 View Events', 'url' => '/lezm/admin/evenements', 'key' => 'list'],
                    ['label' => '➕ Add Event', 'url' => '/lezm/admin/evenements/create', 'key' => 'create'],
                    ['label' => '📋 List Reservations', 'url' => '/lezm/admin/reservations', 'key' => 'list_reservations'],
                    ['label' => '➕ Add Reservation', 'url' => '/lezm/admin/reservations/create', 'key' => 'create_reservation']
                ]],
                'user' => ['👥 User', [
                    ['label' => '👤 Home', 'url' => '/lezm/home', 'key' => 'list'],
                    ['label' => '➕ USERS Dashboard', 'url' => '/lezm/admin/user', 'key' => 'create']
                ]],
                'posts' => ['📝 Posts', [
                    ['label' => '📋 Liste des Posts', 'url' => '/lezm/admin/posts/list', 'key' => 'list'],
                    ['label' => '➕ Ajouter un Post', 'url' => '/lezm/admin/posts/create', 'key' => 'create']
                ]]
            ];

            // Debug: Confirm sections array is loaded
            echo "<!-- Debug: Rendering sections -->";

            foreach ($sections as $menu => $section_data) {
                $label = $section_data[0];
                $items = $section_data[1];
                $isActive = (isset($active_menu) && $active_menu === $menu);
                echo '<li class="nav-section ' . ($isActive ? 'active' : '') . '" style="margin-bottom: 10px;">';
                echo '<div class="nav-toggle" style="
                    cursor: pointer;
                    background: ' . ($isActive ? '#3498db' : '#34495e') . ';
                    color: white;
                    padding: 12px 15px;
                    border-radius: 4px;
                    font-weight: bold;
                ">' . $label . '</div>';
                echo '<ul class="sub-menu" style="
                    list-style: none;
                    padding-left: 20px;
                    margin: 0;
                    overflow: hidden;
                    transition: max-height 0.3s ease;
                    max-height: 0;
                ">';
                foreach ($items as $item) {
                    $subActive = (isset($active_submenu) && $active_submenu === $item['key']);
                    echo '<li style="margin-bottom: 5px;">
                        <a href="' . $item['url'] . '" style="
                            display: block;
                            background: ' . ($subActive ? '#2980b9' : '#34495e') . ';
                            color: white;
                            padding: 10px 15px;
                            border-radius: 4px;
                            text-decoration: none;
                            font-weight: bold;
                        ">' . $item['label'] . '</a>
                    </li>';
                }
                echo '</ul>';
                echo '</li>';
            }
            ?>

            <!-- Login (direct link) -->
            <li style="margin-bottom: 10px;">
                <a href="/lezm/login" style="
                    display: block;
                    background: <?= (isset($active_menu) && $active_menu === 'login') ? '#3498db' : '#34495e' ?>;
                    color: white;
                    padding: 12px 15px;
                    border-radius: 4px;
                    text-decoration: none;
                    font-weight: bold;
                ">🔐 Login</a>
            </li>
        </ul>
    </nav>

    <!-- Footer -->
    <div style="
        position: absolute;
        bottom: 20px;
        left: 20px;
        right: 20px;
        color: #7f8c8d;
        font-size: 12px;
        text-align: center;
        padding-top: 15px;
        border-top: 1px solid #34495e;
    ">
        Last loaded: <?= date('H:i:s') ?>
    </div>
</aside>

<script src="/lezm/view/assets/js/dashboard.js"></script>