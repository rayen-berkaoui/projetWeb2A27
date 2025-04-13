<?php
// Update with your real DB credentials
$host = 'localhost';
$dbname = 'db_html';
$username = 'your_username';
$password = 'your_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}

// Get POST data
$id = $_POST['id'] ?? null;
$name = $_POST['nom_compagne'] ?? '';
$start = $_POST['date_debut'] ?? '';
$end = $_POST['date_fin'] ?? '';
$budget = $_POST['budget'] ?? '';
$description = $_POST['description'] ?? '';

if ($name && $start && $end && is_numeric($budget)) {
    $stmt = $pdo->prepare("INSERT INTO campaigns (ID, Ncompagne, DateD, DateF, Budget, `Desc`)
                           VALUES (:id, :name, :start, :end, :budget, :description)");
    $stmt->execute([
        ':id' => $id,
        ':name' => $name,
        ':start' => $start,
        ':end' => $end,
        ':budget' => $budget,
        ':description' => $description
    ]);

    header("Location: marketing.php");
    exit;
} else {
    echo "Invalid input data.";
}
?>
