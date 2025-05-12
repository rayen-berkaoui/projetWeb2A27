<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blank Template</title>
    <!-- Optional: Add CSS/JS links here -->
</head>
<body>
    <?php include_once __DIR__ . '/../src/controll/routes/header.php'; ?> <!-- Example: Include a header -->
    
    <main>
        <!-- Your dynamic content will go here -->
        <?= $content ?? 'Default content if no variable is passed'; ?>
    </main>

    <?php include_once __DIR__ . '/../src/controll/routes/footer.php'; ?> <!-- Example: Include a footer -->
</body>
</html>