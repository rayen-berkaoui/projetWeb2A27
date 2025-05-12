<?php

class ArticleController {
    private $db;

    // Constructor - Initialize the database connection
    public function __construct($db) {
        $this->db = $db;
    }

    // Show all articles
    public function index() {
        $query = "SELECT * FROM articles";
        $result = $this->db->query($query);
        $articles = $result->fetch_all(MYSQLI_ASSOC);
        
        // Pass the articles to the view
        include 'C:\xampp\htdocs\lezm\view\admin\pages\articles\list.php'; 
    }

    // Show the create form and process the form submission
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle form submission and process data
            $type = $_POST['type'];
            $author = $_POST['author'];
            $content = $_POST['content'];
    
            // Validate and save the article to the database
            $query = "INSERT INTO articles (type, author, content) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('sss', $type, $author, $content);
            $stmt->execute();
    
            // Redirect to the articles list page after successful insertion
            header("Location: /lezm/admin/articles");
            exit; // Ensure that no further code is executed
        }
    
        // Display the article creation form (view)
        include 'C:\xampp\htdocs\lezm\view\admin\pages\articles\create.php';
    }
    

    // Show the edit form for an article
    public function edit($id) {
        $query = "SELECT * FROM articles WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $article = $result->fetch_assoc();

        // Include the view for editing the article
        include 'C:\xampp\htdocs\lezm\view\admin\pages\articles\edit.php'; // Using relative path
    }

    // Update the article
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize and collect input values
            $type = $_POST['type'];
            $author = $_POST['author'];
            $content = $_POST['content'];
    
            // Update the article in the database
            $query = "UPDATE articles SET type = ?, author = ?, content = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('sssi', $type, $author, $content, $id);
            $stmt->execute();
            
            // After updating, redirect to the article list page
            header("Location: /lezm/admin/articles");
            exit;
        }
    }
    
    
    

    // Delete an article
    public function delete($id) {
        $query = "DELETE FROM articles WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        // After deletion, redirect to the article list page
        header("Location: /lezm/admin/articles");
        exit;
    }
}
?>
