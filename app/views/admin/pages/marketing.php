<?php 
$active_menu = 'marketing';
include_once __DIR__ . '/../layout.php';
?>

<div class="content" style="margin-left: 260px; padding: 20px;">
    <h1>📣 Marketing</h1>
    <p>Welcome to the Marketing section of your admin panel.</p>

    <div style="margin-top: 20px;">
        <h3>Campaigns</h3>
        <ul>
            <li>Email newsletters</li>
            <li>Social media promotions</li>
            <li>SEO strategies</li>
            <li>Ad campaigns</li>
        </ul>
    </div>

    <div style="margin-top: 20px;">
        <h3>Quick Actions</h3>
        <button style="padding: 10px 15px; background-color: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer;">
            ➕ New Campaign
        </button>
    </div>
</div>
