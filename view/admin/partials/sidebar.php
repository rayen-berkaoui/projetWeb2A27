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
        <img src="/2A27/view/assets/img/logo.png" alt="" style="width: 120px; height: auto;">
    </div>

    <!-- Navigation Menu -->
    <nav>
        <ul style="list-style: none; padding: 0; margin: 0;">

            <!-- Dashboard (no submenu) -->
            <li style="margin-bottom: 10px;">
                <a href="/2A27/admin/dashboard" style="
                    display: block;
                    background: <?= ($active_menu ?? '') === 'dashboard' ? '#3498db' : '#34495e' ?>;
                    color: white;
                    padding: 12px 15px;
                    border-radius: 4px;
                    text-decoration: none;
                    font-weight: bold;
                ">📊 Dashboard</a>
            </li>

            <!-- Reusable nav-section with submenu -->
            <?php
$sections = [
    'login' => ['🔐 Login', [
        ['label' => '🔍 View Logins', 'url' => '/2A27/admin/login', 'key' => 'list'],
        ['label' => '➕ Add Login', 'url' => '/2A27/admin/login/create', 'key' => 'create']
    ]],
    'marketing' => ['📣 Marketing', [
        ['label' => '📈 Campaigns', 'url' => '/2A27/admin/marketing', 'key' => 'list'],
        ['label' => '➕ New Campaign', 'url' => '/2A27/admin/marketing/create', 'key' => 'create']
    ]],
    'forums' => ['💬 Forums', [
        ['label' => '📑 Forum List', 'url' => '/2A27/admin/forums', 'key' => 'list'],
        ['label' => '➕ Create Forum', 'url' => '/2A27/admin/forums/create', 'key' => 'create']
    ]],
    'articles' => ['📰 Articles', [
        ['label' => '🔍 View Articles', 'url' => '/2A27/admin/articles', 'key' => 'list'],
        ['label' => '➕ Add Article', 'url' => '/2A27/admin/articles/create', 'key' => 'create'],
        // Add the new Types options here
        ['label' => '📋 List Types', 'url' => '/2A27/admin/articles/listTypes', 'key' => 'list'],
        ['label' => '➕ Add Type', 'url' => '/2A27/admin/articles/createType', 'key' => 'create']
    ]],
    'avis' => ['⭐ Avis', [
        ['label' => '📃 Avis List', 'url' => '/2A27/admin/avis', 'key' => 'list'],
        ['label' => '➕ New Avis', 'url' => '/2A27/admin/avis/create', 'key' => 'create']
    ]],
    'produit' => ['📦 Produit', [
        ['label' => '📦 View Produits', 'url' => '/2A27/admin/produit', 'key' => 'list'],
        ['label' => '➕ Add Produit', 'url' => '/2A27/admin/produit/create', 'key' => 'create']
    ]],
    'evenements' => ['📅 Événements', [
    ['label' => '📅 View Events', 'url' => '/2A27/admin/evenements', 'key' => 'list'],
    ['label' => '➕ Add Event', 'url' => '/2A27/admin/evenements/create', 'key' => 'create'],
    ['label' => '📋 List Reservations', 'url' => '/2A27/admin/reservations', 'key' => 'list_reservations'],
    ['label' => '➕ Add Reservation', 'url' => '/2A27/admin/reservations/create', 'key' => 'create_reservation'],
    ]],
    'user' => ['👥 User', [
        ['label' => '👤 Home', 'url' => '/2A27/home', 'key' => 'list'],
        ['label' => '➕ Register', 'url' => '/2A27/home/register', 'key' => 'create']
    ]],
];

foreach ($sections as $menu => [$label, $items]) {
    $isActive = ($active_menu ?? '') === $menu;
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
        $subActive = ($active_submenu ?? '') === $item['key'];
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
        </ul>
    </nav>

    <!-- Footer -->
    <div style="
        position: absolute;
        top: auto;
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

<script src="/2A27/view/assets/js/dashboard.js"></script>

<!-- JavaScript to handle submenu toggle -->
<script>
    const navToggles = document.querySelectorAll('.nav-toggle');
    navToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const subMenu = this.nextElementSibling;
            subMenu.style.maxHeight = subMenu.style.maxHeight ? null : subMenu.scrollHeight + "px";
        });
    });
</script>
