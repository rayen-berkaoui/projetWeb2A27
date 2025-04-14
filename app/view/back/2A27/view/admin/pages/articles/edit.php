<?php $active_menu = 'articles'; include_once __DIR__ . '/../partials/sidebar.php'; ?>
<div style="margin-left: 270px; padding: 20px;">
    <h1>✏️ Edit Article</h1>
    <form method="POST" action="/2A27/admin/articles/edit/<?= $article['id'] ?>">
    <label for="type">Type</label>
    <input type="text" name="type" id="type" value="<?= htmlspecialchars($article['type']) ?>" required>
    
    <label for="author">Author</label>
    <input type="text" name="author" id="author" value="<?= htmlspecialchars($article['author']) ?>" required>

    <label for="content">Content</label>
    <textarea name="content" id="content" required><?= htmlspecialchars($article['content']) ?></textarea>

    <button type="submit">Update Article</button>
</form>

</div>
