<?php

require_once __DIR__ . '/../../../domain/post.php';

class PostFrontController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function index() {
        $posts = Post::getAll($this->db);
        require_once __DIR__ . '/../../../../view/user/pages/posts/list.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $content = $_POST['content'];

            $post = new Post(null, $title, $content);
            $post->save($this->db);

            header('Location: /lezm/posts');
            exit;
        } else {
            require_once __DIR__ . '/../../../../view/user/pages/posts/create.php';
        }
    }
}
