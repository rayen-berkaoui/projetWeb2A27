<?php
// Include Dompdf autoload
require '../../../libraries/dompdf/autoload.inc.php';  // Update with the correct path

use Dompdf\Dompdf;
use Dompdf\Options;

// Include the database connection file
require_once 'db.php';  // Adjust the path if necessary

try {
    $pdo = new PDO("mysql:host=localhost;dbname=db_html", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Query to fetch articles from the database (type removed)
$query = "SELECT id, author, content, time_created FROM articles";
$stmt = $pdo->prepare($query);
$stmt->execute();

// Fetch all articles into an associative array
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Generate HTML content
$html = '<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; }
        h1 { color: #3498db; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #3498db; color: white; }
    </style>
</head>
<body>
    <h1>Articles List</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Author</th>
                <th>Content</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>';

foreach ($articles as $article) {
    $html .= "<tr>
                <td>" . htmlspecialchars($article['id']) . "</td>
                <td>" . htmlspecialchars($article['author']) . "</td>
                <td>" . htmlspecialchars(substr($article['content'], 0, 50)) . "...</td>
                <td>" . htmlspecialchars($article['time_created']) . "</td>
              </tr>";
}

$html .= '</tbody>
    </table>
</body>
</html>';

// Initialize Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true); // Optional if needed
$dompdf = new Dompdf($options);

// Load HTML
$dompdf->loadHtml($html);

// Set paper size (A4 by default)
$dompdf->setPaper('A4', 'portrait');

// Render PDF
$dompdf->render();

// Stream the PDF to the browser
$dompdf->stream('articles_list.pdf', array('Attachment' => 0));  // 'Attachment' => 0 to view in browser
?>
