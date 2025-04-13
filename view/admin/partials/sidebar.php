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
        <img src="/2A27/view/assets/img/logo.png" alt="" style="width: 120px; height: auto; vertical-align: middle; margin-right: 10px;">
    </div>

    <!-- Navigation Menu -->
    <nav>
        <ul style="list-style: none; padding: 0; margin: 0;">
            <li style="margin-bottom: 10px;">
                <a href="/2A27/admin/dashboard" style="
                    display: block;
                    background: <?= ($active_menu ?? '') === 'dashboard' ? '#3498db' : '#34495e' ?>;
                    color: white;
                    padding: 12px 15px;
                    border-radius: 4px;
                    text-decoration: none;
                    font-weight: bold;
                    transition: all 0.3s;
                ">📊 Dashboard</a>
            </li>
            <li style="margin-bottom: 10px;">
                <a href="/2A27/admin/login" style="
                    display: block;
                    background: <?= ($active_menu ?? '') === 'login' ? '#3498db' : '#34495e' ?>;
                    color: white;
                    padding: 12px 15px;
                    border-radius: 4px;
                    text-decoration: none;
                    font-weight: bold;
                    transition: all 0.3s;
                ">🔐 Login</a>
            </li>
            <li style="margin-bottom: 10px;">
                <a href="/2A27/admin/marketing" style="
                    display: block;
                    background: <?= ($active_menu ?? '') === 'marketing' ? '#3498db' : '#34495e' ?>;
                    color: white;
                    padding: 12px 15px;
                    border-radius: 4px;
                    text-decoration: none;
                    font-weight: bold;
                    transition: all 0.3s;
                ">📣 Marketing</a>
            </li>
            <li style="margin-bottom: 10px;">
                <a href="/2A27/admin/forums" style="
                    display: block;
                    background: <?= ($active_menu ?? '') === 'forums' ? '#3498db' : '#34495e' ?>;
                    color: white;
                    padding: 12px 15px;
                    border-radius: 4px;
                    text-decoration: none;
                    font-weight: bold;
                    transition: all 0.3s;
                ">💬 Forums</a>
            </li>

            <!-- Articles Section with Sub-menu -->
            <li style="margin-bottom: 10px;">
                <a href="/2A27/admin/articles" style="
                    display: block;
                    background: <?= ($active_menu ?? '') === 'articles' ? '#3498db' : '#34495e' ?>;
                    color: white;
                    padding: 12px 15px;
                    border-radius: 4px;
                    text-decoration: none;
                    font-weight: bold;
                    transition: all 0.3s;
                ">📰 Articles</a>
                <ul style="list-style: none; padding-left: 20px; margin: 10px 0 0 0;">
                    <li style="margin-bottom: 5px;">
                        <a href="/2A27/admin/articles" style="
                            display: block;
                            background: <?= ($active_submenu ?? '') === 'list' ? '#2980b9' : '#34495e' ?>;
                            color: white;
                            padding: 10px 15px;
                            border-radius: 4px;
                            text-decoration: none;
                            font-weight: bold;
                            transition: all 0.3s;
                        ">🔍 View Articles</a>
                    </li>
                    <li style="margin-bottom: 5px;">
                        <a href="/2A27/admin/articles/create" style="
                            display: block;
                            background: <?= ($active_submenu ?? '') === 'create' ? '#2980b9' : '#34495e' ?>;
                            color: white;
                            padding: 10px 15px;
                            border-radius: 4px;
                            text-decoration: none;
                            font-weight: bold;
                            transition: all 0.3s;
                        ">➕ Add Article</a>
                    </li>
                </ul>
            </li>

            <li style="margin-bottom: 10px;">
                <a href="/2A27/admin/avis" style="
                    display: block;
                    background: <?= ($active_menu ?? '') === 'avis' ? '#3498db' : '#34495e' ?>;
                    color: white;
                    padding: 12px 15px;
                    border-radius: 4px;
                    text-decoration: none;
                    font-weight: bold;
                    transition: all 0.3s;
                ">⭐ Avis</a>
            </li>
            <li style="margin-bottom: 10px;">
                <a href="/2A27/admin/produit" style="
                    display: block;
                    background: <?= ($active_menu ?? '') === 'produit' ? '#3498db' : '#34495e' ?>;
                    color: white;
                    padding: 12px 15px;
                    border-radius: 4px;
                    text-decoration: none;
                    font-weight: bold;
                    transition: all 0.3s;
                ">📦 Produit</a>
            </li>
            <li style="margin-bottom: 10px;">
                <a href="/2A27/admin/evenements" style="
                    display: block;
                    background: <?= ($active_menu ?? '') === 'evenements' ? '#3498db' : '#34495e' ?>;
                    color: white;
                    padding: 12px 15px;
                    border-radius: 4px;
                    text-decoration: none;
                    font-weight: bold;
                    transition: all 0.3s;
                ">📅 Événements</a>
            </li>
            <li style="margin-bottom: 10px;">
                <a href="/2A27/home" style="
                    display: block;
                    background: <?= ($active_menu ?? '') === 'produit' ? '#3498db' : '#34495e' ?>;
                    color: white;
                    padding: 12px 15px;
                    border-radius: 4px;
                    text-decoration: none;
                    font-weight: bold;
                    transition: all 0.3s;
                ">👥 User</a>
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
