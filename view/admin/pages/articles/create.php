<!-- views/article/create.php -->
<h1>Create a New Article</h1>
<form action="/2A27/admin/articles/create" method="POST">
    <label for="type">Article Type:</label>
    <input type="text" name="type" id="type" required><br>

    <label for="author">Author:</label>
    <input type="text" name="author" id="author" required><br>

    <label for="content">Content:</label>
    <textarea name="content" id="content" required></textarea><br>

    <button type="submit">Create Article</button>
</form>
