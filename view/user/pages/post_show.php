<?php
require_once 'C:\xampp\htdocs\2A27\view\user\pages\layout\header.php';

// Get post ID
$postId = intval($_GET['id'] ?? 0);

if ($postId === 0) {
    echo "Invalid post.";
    require_once 'C:\xampp\htdocs\2A27\view\user\pages\layout\footer.php';
    exit;
}

// Fetch post
$stmt = $db->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param('i', $postId);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();

if (!$post) {
    echo "Post not found.";
    require_once 'C:\xampp\htdocs\2A27\view\user\pages\layout\footer.php';
    exit;
}

// Fetch comments
$commentsStmt = $db->prepare("SELECT * FROM commentaires WHERE post_id = ?");
$commentsStmt->bind_param('i', $postId);
$commentsStmt->execute();
$comments = $commentsStmt->get_result();
?>

<h2><?= htmlspecialchars($post['title']) ?></h2>
<p><?= nl2br(htmlspecialchars($post['content'])) ?></p>

<hr>

<h3>Comments</h3>

<?php if ($comments->num_rows > 0): ?>
    <?php while ($comment = $comments->fetch_assoc()): ?>
        <div style="margin-bottom: 15px; padding-left: 10px; border-left: 2px solid #ccc;">
            <strong><?= htmlspecialchars($comment['author']) ?></strong><br>
            <small><?= htmlspecialchars($comment['content']) ?></small>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No comments yet.</p>
<?php endif; ?>

<hr>

<h3>Leave a Comment</h3>

<form action="<?= base_url('add_comment') ?>" method="POST">
    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">

    <label for="author">Name:</label><br>
    <input type="text" name="author" required><br><br>

    <label for="content">Comment:</label><br>
    <textarea name="content" required></textarea><br><br>

    <button type="submit">Submit</button>
</form>

<?php require_once 'C:\xampp\htdocs\2A27\view\user\pages\layout\footer.php'; ?>
