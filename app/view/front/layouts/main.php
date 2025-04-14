<!DOCTYPE html>
<html lang="en-US" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title><?= $pageTitle ?? 'GreenMind - Plant Nursery & Gardening' ?></title>

    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= BASE_URL ?>assets/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= BASE_URL ?>assets/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= BASE_URL ?>assets/img/favicons/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" href="<?= BASE_URL ?>assets/img/favicons/favicon.ico">
    <link rel="manifest" href="<?= BASE_URL ?>assets/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="<?= BASE_URL ?>assets/img/favicons/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">

    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400..700&family=Roboto:wght@100..900&display=swap" rel="stylesheet">
    <link href="<?= BASE_URL ?>vendors/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/theme.css" rel="stylesheet" />
    <link href="<?= BASE_URL ?>assets/css/theme/review-card.css" rel="stylesheet" />
    <link href="<?= BASE_URL ?>assets/css/user.css" rel="stylesheet" />
    
    <!-- Additional custom styles -->
    <?php if(isset($additionalStyles)): ?>
        <?php foreach($additionalStyles as $style): ?>
            <link href="<?= BASE_URL . $style ?>" rel="stylesheet" />
        <?php endforeach; ?>
    <?php endif; ?>
</head>

<body>
    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
        <?php include_once __DIR__ . '/../partials/navbar.php'; ?>
        
        <?= $content ?? '' ?>
        
        <?php include_once __DIR__ . '/../partials/footer.php'; ?>
    </main>
    <!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->

    <!-- ===============================================-->
    <!--    JavaScripts-->
    <!-- ===============================================-->
    <script src="<?= BASE_URL ?>vendors/popper/popper.min.js"></script>
    <script src="<?= BASE_URL ?>vendors/bootstrap/bootstrap.min.js"></script>
    <script src="<?= BASE_URL ?>vendors/anchorjs/anchor.min.js"></script>
    <script src="<?= BASE_URL ?>vendors/swiper/swiper-bundle.min.js"></script>
    <script src="<?= BASE_URL ?>vendors/fontawesome/all.min.js"></script>
    <script src="<?= BASE_URL ?>vendors/countup/countUp.umd.js"></script>
    <script src="<?= BASE_URL ?>vendors/is/is.min.js"></script>
    <script src="<?= BASE_URL ?>assets/js/theme.js"></script>

    <!-- Additional custom scripts -->
    <?php if(isset($additionalScripts)): ?>
        <?php foreach($additionalScripts as $script): ?>
            <script src="<?= BASE_URL . $script ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Inline scripts if needed -->
    <?php if(isset($inlineScripts)): ?>
        <script>
            <?= $inlineScripts ?>
        </script>
    <?php endif; ?>
</body>

</html>

