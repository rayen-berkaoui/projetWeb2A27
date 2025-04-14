<ul>
    <?php foreach ($articles as $article): ?>
        <li>
            <strong><?= htmlspecialchars($article['type']) ?></strong><br>
            <small>By <?= htmlspecialchars($article['author']) ?> on <?= $article['time_created'] ?></small><br>
            <p><?= htmlspecialchars(substr($article['content'], 0, 100)) ?>...</p>
            <a href="/2A27/admin/articles/edit/<?= $article['id'] ?>">Edit</a> |
            <a href="/2A27/admin/articles/delete/<?= $article['id'] ?>">Delete</a>
        </li>
    <?php endforeach; ?>
</ul>
