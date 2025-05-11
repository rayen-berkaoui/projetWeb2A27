<?php $active_menu = 'articles'; include_once 'C:\xampp\htdocs\2A27\view\admin\partials\sidebar.php'; ?>

<div style="margin-left: 270px; padding: 20px;">
    <h1>✏️ Edit Article</h1>

    <!-- Start of form -->
    <form method="POST" action="/2A27/admin/articles/update/<?= $article['id'] ?>">
        <!-- Type input field -->
        <div class="form-group">
            <label for="type">Type</label>
            <input type="text" name="type" id="type" value="<?= htmlspecialchars($article['type']) ?>" class="form-input" required>
        </div>

        <!-- Author input field -->
        <div class="form-group">
            <label for="author">Author</label>
            <input type="text" name="author" id="author" value="<?= htmlspecialchars($article['author']) ?>" class="form-input" required>
        </div>

        <!-- Content textarea -->
        <div class="form-group">
            <label for="content">Content</label>
            <textarea name="content" id="content" class="form-input" required><?= htmlspecialchars($article['content']) ?></textarea>
        </div>

        <!-- Submit button -->
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Update Article</button>
        </div>
    </form>
</div>

<!-- Inline CSS Styles -->
<style>
    /* Reset and Base Styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        background-color: #f5f7fa;
        color: #333;
    }

    /* Form Styles */
    .form-group {
        margin-bottom: 15px;
    }

    .form-input, textarea {
        width: 100%;
        padding: 10px;
        font-size: 1rem;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .form-input:focus, textarea:focus {
        border-color: #3498db;
        outline: none;
    }

    /* Button Styles */
    .btn {
        padding: 10px 20px;
        font-size: 1rem;
        cursor: pointer;
        border: none;
        border-radius: 5px;
        transition: background 0.3s;
    }

    .btn-primary {
        background-color: #3498db;
        color: white;
    }

    .btn-primary:hover {
        background-color: #2980b9;
    }

    /* Content Styles */
    .page-content {
        padding: 20px;
        flex: 1;
    }
</style>
