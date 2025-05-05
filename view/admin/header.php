<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - GreenMind</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Unicons -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    
    <style>
        :root {
            --primary-color: #2E7D32;
            --secondary-color: #4CAF50;
            --sidebar-width: 250px;
        }
        
        .admin-sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: var(--primary-color);
            color: white;
            padding-top: 1rem;
        }
        
        .admin-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
        }
        
        .admin-sidebar .nav-link {
            color: rgba(255,255,255,.8);
            padding: 0.8rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .admin-sidebar .nav-link:hover,
        .admin-sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,.1);
        }
        
        .admin-sidebar .logo {
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            border-bottom: 1px solid rgba(255,255,255,.1);
        }
        
        .admin-header {
            background: white;
            border-bottom: 1px solid #eee;
            padding: 1rem 2rem;
            margin-bottom: 2rem;
        }
        
        .btn-success {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-success:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .text-success {
            color: var(--primary-color) !important;
        }
    </style>
</head>
<body>

<div class="admin-sidebar">
    <div class="logo">
        <img src="/assets/img/logos/logo_white.png" alt="GreenMind" height="40">
    </div>
    <nav class="nav flex-column">
        <a class="nav-link" href="/view/admin/dashboard.php">
            <i class="uil uil-dashboard"></i> Tableau de bord
        </a>
        <a class="nav-link active" href="/view/admin/evenements.php">
            <i class="uil uil-calendar-alt"></i> Événements
        </a>
        <a class="nav-link" href="/view/admin/produits.php">
            <i class="uil uil-box"></i> Produits
        </a>
        <a class="nav-link" href="/view/admin/utilisateurs.php">
            <i class="uil uil-users-alt"></i> Utilisateurs
        </a>
        <a class="nav-link" href="/view/admin/parametres.php">
            <i class="uil uil-setting"></i> Paramètres
        </a>
        <a class="nav-link text-danger mt-auto" href="/logout.php">
            <i class="uil uil-signout"></i> Déconnexion
        </a>
    </nav>
</div>

<div class="admin-content">
    <div class="admin-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Administration GreenMind</h4>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted">
                <i class="uil uil-user-circle"></i> Admin
            </span>
        </div>
    </div>
</body>
</html> 