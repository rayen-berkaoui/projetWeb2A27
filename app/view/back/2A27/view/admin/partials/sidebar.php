<?php
// Sidebar file for GreenMind admin dashboard
$currentRoute = $_GET['route'] ?? 'dashboard';
$baseUrl = "/greenmind/public";  // Base URL for the application

// Determine active menu and submenu based on the route
$active_menu = explode('/', $currentRoute)[0];
$active_submenu = isset(explode('/', $currentRoute)[1]) ? explode('/', $currentRoute)[1] : '';

// Special case for feedback/avis section
if ($active_menu === 'feedback') {
    $active_menu = 'avis';
}
?>

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
        Admin Panel
    </div>

    <!-- Navigation Menu -->
    <nav>
        <ul style="list-style: none; padding: 0; margin: 0;">
            <li style="margin-bottom: 10px;">
                <a href="/greenmind/public/dashboard.php" style="
                    display: block;
                    background: <?= $active_menu === 'dashboard' ? '#3498db' : '#34495e' ?>;
                    color: white;
                    padding: 12px 15px;
                    border-radius: 4px;
                    text-decoration: none;
                    font-weight: bold;
                    transition: all 0.3s;
                ">📊 Dashboard</a>
            </li>
            <li style="margin-bottom: 10px;">
                <a href="/greenmind/public/dashboard.php?route=users/admin" style="
                    display: block;
                    background: <?= $active_menu === 'users' ? '#3498db' : '#34495e' ?>;
                    color: white;
                    padding: 12px 15px;
                    border-radius: 4px;
                    text-decoration: none;
                    font-weight: bold;
                    transition: all 0.3s;
                ">🔐 Login</a>
            </li>
            <li style="margin-bottom: 10px;">
                <a href="/greenmind/public/dashboard.php?route=marketing" style="
                    display: block;
                    background: <?= $active_menu === 'marketing' ? '#3498db' : '#34495e' ?>;
                    color: white;
                    padding: 12px 15px;
                    border-radius: 4px;
                    text-decoration: none;
                    font-weight: bold;
                    transition: all 0.3s;
                ">📣 Marketing</a>
            </li>
            <li style="margin-bottom: 10px;">
                <a href="/greenmind/public/dashboard.php?route=forums" style="
                    display: block;
                    background: <?= $active_menu === 'forums' ? '#3498db' : '#34495e' ?>;
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
                <a href="/greenmind/public/dashboard.php?route=blogs/admin" style="
                    display: block;
                    background: <?= $active_menu === 'blogs' ? '#3498db' : '#34495e' ?>;
                    color: white;
                    padding: 12px 15px;
                    border-radius: 4px;
                    text-decoration: none;
                    font-weight: bold;
                    transition: all 0.3s;
                ">📰 Articles</a>
                <ul style="list-style: none; padding-left: 20px; margin: 10px 0 0 0;">
                    <li style="margin-bottom: 5px;">
                        <a href="/greenmind/public/dashboard.php?route=blogs/admin" style="
                            display: block;
                            background: <?= $active_submenu === 'admin' && !isset(explode('/', $currentRoute)[2]) ? '#2980b9' : '#34495e' ?>;
                            color: white;
                            padding: 10px 15px;
                            border-radius: 4px;
                            text-decoration: none;
                            font-weight: bold;
                            transition: all 0.3s;
                        ">🔍 View Articles</a>
                    </li>
                    <li style="margin-bottom: 5px;">
                        <a href="/greenmind/public/dashboard.php?route=blogs/admin/create" style="
                            display: block;
                            background: <?= $active_submenu === 'admin' && isset(explode('/', $currentRoute)[2]) && explode('/', $currentRoute)[2] === 'create' ? '#2980b9' : '#34495e' ?>;
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
                <a href="/greenmind/public/dashboard.php?route=feedback/admin" style="
                    display: block;
                    background: <?= $active_menu === 'avis' ? '#3498db' : '#34495e' ?>;
                    color: white;
                    padding: 12px 15px;
                    border-radius: 4px;
                    text-decoration: none;
                    font-weight: bold;
                    transition: all 0.3s;
                ">⭐ Avis</a>
            </li>
            <li style="margin-bottom: 10px;">
                <a href="/greenmind/public/dashboard.php?route=products/admin" style="
                    display: block;
                    background: <?= ($active_menu ?? '') === 'produit' ? '#3498db' : '#34495e' ?>;
                    background: <?= $active_menu === 'products' ? '#3498db' : '#34495e' ?>;
                    padding: 12px 15px;
                    border-radius: 4px;
                    text-decoration: none;
                    font-weight: bold;
                    transition: all 0.3s;
                ">📦 Produit</a>
            </li>
            <li style="margin-bottom: 10px;">
                <a href="/greenmind/public/dashboard.php?route=evenements" style="
                    display: block;
                    background: <?= ($active_menu ?? '') === 'evenements' ? '#3498db' : '#34495e' ?>;
                    background: <?= $active_menu === 'evenements' ? '#3498db' : '#34495e' ?>;
                    padding: 12px 15px;
                    border-radius: 4px;
                    text-decoration: none;
                    font-weight: bold;
                    transition: all 0.3s;
                ">📅 Événements</a>
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
        Last loaded: <?= date('Y-m-d H:i:s') ?><br>
        Current route: <?= htmlspecialchars($currentRoute) ?>
    </div>
</aside>
