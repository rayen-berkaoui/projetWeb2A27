<?php $active_menu = 'articles'; include_once 'C:\xampp\htdocs\lezm\view\admin\partials\sidebar.php'; 
?>

<div style="margin-left: 270px; padding: 20px;">
    <h1>✏️ Edit Article</h1>

    <!-- Start of form -->
    <form method="POST" action="/lezm/admin/articles/update/<?php echo $article['id']; ?>">
        <!-- Type input field -->
        <div class="form-group">
            <label for="type">Type</label>
            <select name="type_id" id="type_id" class="form-input" required>
    <?php foreach ($types as $type): ?>
        <option value="<?= $type['id'] ?>" <?= $type['id'] == $article['type_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($type['nom']) ?>
        </option>
    <?php endforeach; ?>
</select>

        </div>

        <!-- Author input field -->
        <div class="form-group">
            <label for="author">Author</label>
            <input type="text" name="author" id="author" value="<?= htmlspecialchars($article['author'] ?? '') ?>" class="form-input" required>
        </div>

        <!-- Content textarea -->
        <div class="form-group">
            <label for="content">Content</label>
            <textarea name="content" id="content" class="form-input" required><?= htmlspecialchars($article['content'] ?? '') ?></textarea>
        </div>

        <!-- Submit button -->
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Update Article</button>
        </div>
    </form>
</div>

<!-- Full CSS Styling -->
<style>
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

    h1 {
        font-size: 1.8rem;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-input,
    textarea {
        width: 100%;
        padding: 10px;
        font-size: 1rem;
        border: 1px solid #ccc;
        border-radius: 5px;
        resize: vertical;
    }

    .form-input:focus,
    textarea:focus {
        border-color: #3498db;
        outline: none;
    }

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

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
</style>
