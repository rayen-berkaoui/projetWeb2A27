<aside class="sidebar" style="
        position: fixed;
        left: 0;
        top: 0;
        width: 250px;	
        height: 100vh;
        background: #2c3e50;
        color: white;
        padding: 20px;
        box-shadow: 5px 0 15px rgba(0,0,0,0.1);
        z-index: 1000;
    ">
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

    <nav>
        <ul style="list-style: none; padding: 0; margin: 0;">
            <li style="margin-bottom: 10px;">
                <a href="/2A27/admin/dashboard" style="
                    display: block;
                    background: <?= (isset($active_menu) && $active_menu === 'dashboard') ? '#3498db' : '#34495e' ?>;
                    color: white;
                    padding: 12px 15px;
                    border-radius: 4px;
                    text-decoration: none;
                    font-weight: bold;
                    transition: all 0.3s;
                ">📊 Dashboard</a>
            </li>
            <li style="margin-bottom: 10px;">
                <a href="/2A27/admin/marketing" style="
                    display: block;
                    background: <?= (isset($active_menu) && $active_menu === 'marketing') ? '#3498db' : '#34495e' ?>;
                    color: white;
                    padding: 12px 15px;
                    border-radius: 4px;
                    text-decoration: none;
                    font-weight: bold;
                    transition: all 0.3s;
                ">📣 Marketing</a>
            </li>
            <li style="margin-bottom: 10px;">
                <a href="#" style="
                    display: block;
                    background: <?= (isset($active_menu) && $active_menu === 'articles') ? '#3498db' : '#34495e' ?>;
                    color: white;
                    padding: 12px 15px;
                    border-radius: 4px;
                    text-decoration: none;
                    font-weight: bold;
                    transition: all 0.3s;
                ">📝 Articles</a>
            </li>
            <li style="margin-bottom: 10px;">
                <a href="#" style="
                    display: block;
                    background: <?= (isset($active_menu) && $active_menu === 'login') ? '#3498db' : '#34495e' ?>;
                    color: white;
                    padding: 12px 15px;
                    border-radius: 4px;
                    text-decoration: none;
                    font-weight: bold;
                    transition: all 0.3s;
                ">🔐 Login</a>
            </li>
            <li style="margin-bottom: 10px;">
                <a href="#" style="
                    display: block;
                    background: <?= (isset($active_menu) && $active_menu === 'forums') ? '#3498db' : '#34495e' ?>;
                    color: white;
                    padding: 12px 15px;
                    border-radius: 4px;
                    text-decoration: none;
                    font-weight: bold;
                    transition: all 0.3s;
                ">💭 Forums</a>
            </li>
            <li style="margin-bottom: 10px;">
                <a href="#" style="
                    display: block;
                    background: <?= (isset($active_menu) && $active_menu === 'evenements') ? '#3498db' : '#34495e' ?>;
                    color: white;
                    padding: 12px 15px;
                    border-radius: 4px;
                    text-decoration: none;
                    font-weight: bold;
                    transition: all 0.3s;
                ">🎉 Evenements</a>
            </li>
            <li style="margin-bottom: 10px;">
                <a href="#" style="
                    display: block;
                    background: <?= (isset($active_menu) && $active_menu === 'produits') ? '#3498db' : '#34495e' ?>;
                    color: white;
                    padding: 12px 15px;
                    border-radius: 4px;
                    text-decoration: none;
                    font-weight: bold;
                    transition: all 0.3s;
                ">🛍️ Produits</a>
            </li>
            <li style="margin-bottom: 10px;">
                <a href="#" style="
                    display: block;
                    background: <?= (isset($active_menu) && $active_menu === 'avis') ? '#3498db' : '#34495e' ?>;
                    color: white;
                    padding: 12px 15px;
                    border-radius: 4px;
                    text-decoration: none;
                    font-weight: bold;
                    transition: all 0.3s;
                ">⭐ Avis</a>
            </li>
        </ul>
    </nav>
</aside>
