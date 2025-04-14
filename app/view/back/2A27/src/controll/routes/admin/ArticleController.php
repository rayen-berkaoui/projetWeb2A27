<?php
define('BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/2A27');
class ArticleController {

    // Database connection (assuming you are using mysqli)
    private $db;

    public function __construct($db) {
        $this->db = $db; // Injecting database connection
    }

    // Create Article
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type = $_POST['type'];
            $author = $_POST['author'];
            $content = $_POST['content'];

            // Validate input (you can add more validation as needed)
            if (empty($type) || empty($author) || empty($content)) {
                echo "All fields are required!";
                return;
            }

            // Insert into the database
            $query = "INSERT INTO articles (type, author, content) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("sss", $type, $author, $content);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                header("Location: /2A27/admin/articles"); // Redirect to the articles list page
                exit();
            } else {
                echo "Error inserting article.";
            }
        }
        // Show the form to create an article
        include  'C:\xampp\htdocs\2A27\view\admin\pages\articles\create.php';
    }

    // View All Articles
    public function index() {
        $query = "SELECT * FROM articles ORDER BY time_created DESC";
        $result = $this->db->query($query);

        $articles = [];
        while ($row = $result->fetch_assoc()) {
            $articles[] = $row;
        }

        // Pass the articles to the view
        include 'C:\xampp\htdocs\2A27\view\admin\pages\articles\list.php';
    }

    // Edit Article
    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type = $_POST['type'];
            $author = $_POST['author'];
            $content = $_POST['content'];

            // Validate input
            if (empty($type) || empty($author) || empty($content)) {
                echo "All fields are required!";
                return;
            }

            // Update the article
            $query = "UPDATE articles SET type = ?, author = ?, content = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("sssi", $type, $author, $content, $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                header("Location: /2A27/admin/articles"); // Redirect to articles list
                exit();
            } else {
                echo "Error updating article.";
            }
        }

        // Fetch the existing article data
        $query = "SELECT * FROM articles WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $article = $result->fetch_assoc();

        if (!$article) {
            echo "Article not found!";
            return;
        }

        // Show the form to edit the article
        include 'views/article/edit.php';
    }

    // Delete Article
    public function delete($id) {
        // Delete the article from the database
        $query = "DELETE FROM articles WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            header("Location: /2A27/admin/articles"); // Redirect to the articles list page
            exit();
        } else {
            echo "Error deleting article.";
        }
    }
}
