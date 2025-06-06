<?php
require_once 'C:\xampp\htdocs\2A27\src\domain\ArticleType.php';
class ArticleController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Show all articles
    public function index() {
        // Join with type table to show type name
        $query = "SELECT articles.*, type.nom AS type_name 
                  FROM articles 
                  LEFT JOIN type ON articles.type_id = type.id";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC); // Use fetchAll for PDO
        
        include 'C:\xampp\htdocs\2A27\view\admin\pages\articles\list.php'; 
    }

    // Show the create form and process form submission
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type_id = $_POST['type_id'];
            $author = $_POST['author'];
            $content = $_POST['content'];

            $query = "INSERT INTO articles (type_id, author, content) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $type_id, PDO::PARAM_INT);
            $stmt->bindParam(2, $author, PDO::PARAM_STR);
            $stmt->bindParam(3, $content, PDO::PARAM_STR);
            $stmt->execute();

            header("Location: /2A27/admin/articles");
            exit;
        }

        // Fetch types for the dropdown
        $type_query = "SELECT * FROM type";
        $type_result = $this->db->prepare($type_query);
        $type_result->execute();
        $types = $type_result->fetchAll(PDO::FETCH_ASSOC);

        include 'C:\xampp\htdocs\2A27\view\admin\pages\articles\create.php';
    }

    // Show the edit form
    public function edit($id) {
        $query = "SELECT * FROM articles WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $article = $stmt->fetch(PDO::FETCH_ASSOC); // Use fetch for a single row

        // Fetch types again for the dropdown
        $type_query = "SELECT * FROM type";
        $type_result = $this->db->prepare($type_query);
        $type_result->execute();
        $types = $type_result->fetchAll(PDO::FETCH_ASSOC);

        include 'C:\xampp\htdocs\2A27\view\admin\pages\articles\edit.php';
    }

    // Update the article
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $type_id = $_POST['type_id'];
            $author = $_POST['author'];
            $content = $_POST['content'];

            $query = "UPDATE articles SET type_id = ?, author = ?, content = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $type_id, PDO::PARAM_INT);
            $stmt->bindParam(2, $author, PDO::PARAM_STR);
            $stmt->bindParam(3, $content, PDO::PARAM_STR);
            $stmt->bindParam(4, $id, PDO::PARAM_INT);
            $stmt->execute();

            header("Location: /2A27/admin/articles");
            exit;
        }
    }

    // Delete an article
    public function delete($id) {
        $query = "DELETE FROM articles WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: /2A27/admin/articles");
        exit;
    }
    public function createType() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get the type name from the form
            $nom = $_POST['nom'];

            // Insert the new type into the database
            $query = "INSERT INTO type (nom) VALUES (?)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $nom, PDO::PARAM_STR);
            $stmt->execute();

            // Redirect to the types list after the type is created
            header("Location: /2A27/admin/articles/listTypes");
            exit;
        }

        // Include the view to display the create type form
        include 'C:\xampp\htdocs\2A27\view\admin\pages\articles\createType.php';
    }
    public function listTypes() {
        // Fetch article types from the model
        $types = ArticleType::all();
    
        // Render the view for listing types
        require_once  'C:\xampp\htdocs\2A27\view\admin\pages\articles\listType.php'; // Adjust path if necessary
    }
    public function deleteType($id) {
        // Prepare delete query
        $query = "DELETE FROM type WHERE id = :id";
        $stmt = $this->db->prepare($query);
        
        // Bind the ID to the query and execute
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        // Redirect back to the list page
        header("Location: /2A27/admin/articles/listTypes");
        exit();
    }
    public function editType($id) {
        // Fetch the current type data
        $query = "SELECT * FROM type WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $type = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($type) {
            // Show the edit form (you need to create an editType.php file)
            require_once 'C:\xampp\htdocs\2A27\view\admin\pages\articles\editType.php';
        } else {
            // Type not found
            header("Location: /2A27/admin/articles/listTypes");
            exit();
        }
    }
    
    public function updateType($id) {
        // Check if the form has been submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'];
    
            // Validate input
            if (empty($nom)) {
                echo "Type name cannot be empty.";
                return;
            }
    
            // Update type in the database
            $query = "UPDATE type SET nom = :nom WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':nom' => $nom, ':id' => $id]);
    
            // Redirect to the types list after update
            header("Location: /2A27/admin/articles/listTypes");
            exit;
        }
    
        // Fetch the type based on ID
        $query = "SELECT * FROM type WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        $type = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Check if the type exists
        if (!$type) {
            echo "Type not found.";
            return;
        }
    

        // Render the page with the data (assuming you have a view file for editing)
        require_once 'C:\xampp\htdocs\2A27\view\admin\pages\articles\editType.php';
    }
}

?>
