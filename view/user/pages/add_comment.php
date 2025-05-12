<?php
require_once 'C:\xampp\htdocs\lezm\process\db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = intval($_POST['post_id']);
    $author = $_POST['author'];
    $content = $_POST['content'];

    if (!empty($postId) && !empty($author) && !empty($content)) {
        $stmt = $db->prepare("INSERT INTO commentaires (post_id, content, author) VALUES (?, ?, ?)");
        $stmt->bind_param('iss', $postId, $content, $author);
        $stmt->execute();
    }

    header("Location: /lezm/post/$postId");
    exit;
}
?>
