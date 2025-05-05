<!DOCTYPE html>
<html data-bs-theme="light" lang="en-US" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>GreenMind | Événements</title>

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/img/favicons/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/favicons/favicon.ico">
    <link rel="manifest" href="../assets/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="../assets/img/favicons/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="../vendors/swiper/swiper-bundle.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">
    <link href="../assets/css/theme.css" rel="stylesheet" id="style-default">
    <link href="../assets/css/user.css" rel="stylesheet" id="user-style-default">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link href="../assets/css/evenements.css" rel="stylesheet">

    <style>
      :root {
        --primary-color: #2ecc71;
        --primary-dark: #27ae60;
        --primary-light: #a8e6cf;
        --text-color: #2d3436;
        --bg-light: #f8f9fa;
      }

      body {
        font-family: 'Raleway', sans-serif;
        color: var(--text-color);
      }

      .transition-all {
        transition: all 0.3s ease !important;
      }

      .hover-lift {
        transition: transform 0.2s ease;
      }

      .hover-lift:hover {
        transform: translateY(-3px);
      }

      .text-gradient {
        background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
      }

      .bg-gradient {
        background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
      }

      .navbar {
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
      }

      .navbar-nav .nav-link {
        color: var(--text-color);
        font-weight: 500;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
      }

      .navbar-nav .nav-link:hover,
      .navbar-nav .nav-link.active {
        color: var(--primary-color);
      }

      .loading {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: white;
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
      }

      .loading::after {
        content: "";
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid var(--primary-color);
        border-radius: 50%;
        animation: spin 1s linear infinite;
      }

      @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
      }
    </style>
  </head>

  <body>
    <!-- Loading Indicator -->
    <div class="loading"></div>

    <!-- Main Content -->
    <main class="main" id="top">
      <div class="bg-white">
        <div class="content">
          <div class="bg-white p-1 p-lg-2">
            <div class="container">
              <div class="d-flex justify-content-end align-items-center">
                <a class="ms-2 ms-md-3 submenu" href="#!">
                  <span class="uil uil-user-circle"></span>
                  <span class="ms-1 fs-10 fs-sm-9">connexion</span>
                </a>
              </div>
            </div>
          </div>
          <nav class="navbar navbar-expand-lg py-1" id="navbar-top" data-navbar-soft-on-scroll="data-navbar-soft-on-scroll">
            <div class="container">
              <a class="navbar-brand me-lg-auto cursor-pointer" href="../index.php">
                <img class="w-50 w-md-100 img-fluid" src="../assets/img/logos/logo.png" alt="GreenMind" />
              </a>
              <button class="navbar-toggler border-0 pe-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="container d-lg-flex justify-content-lg-end pe-lg-0 w-lg-100">
                  <form class="form-inline position-relative w-lg-50 ms-lg-4 ms-xl-9 mt-3 mt-lg-0" onsubmit="return false;">
                    <input class="search fs-8 bg-transparent form-control" type="search" name="search" placeholder="Rechercher..." />
                    <div class="search-icon"> <span class="uil uil-search"></span></div>
                  </form>
                  <ul class="navbar-nav mt-2 mt-lg-1 ms-lg-4 ms-xl-8 ms-2xl-9 gap-lg-x1">
                    <li class="nav-item">
                      <a class="nav-link nav-bar-item px-0" href="../index.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link nav-bar-item px-0 active" href="evenements">Événements</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link nav-bar-item px-0" href="../produits">Produits</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link nav-bar-item px-0" href="../contact">Contact</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link nav-bar-item px-0" href="../dashboard">
                        <i class="uil uil-dashboard"></i> Dashboard
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </nav>
          <div class="container">
        </div>
      </div>
    </main>

    <!-- Loading Script -->
    <script>
      window.addEventListener('load', function() {
        document.querySelector('.loading').style.display = 'none';
      });
    </script>
  </body>
</html> 