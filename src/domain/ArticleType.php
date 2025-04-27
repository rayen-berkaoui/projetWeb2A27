<?php

class ArticleType {
    public $id;
    public $name;

    // Constructor
    public function __construct($id, $nom) {
        $this->id = $id;
        $this->name = $nom; // 💡 Set this as name
    }

    // Fetch all article types
    public static function all() {
        global $db;
        $query = "SELECT * FROM type";
        $stmt = $db->query($query);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $types = [];
        foreach ($results as $row) {
            $types[] = new ArticleType($row['id'], $row['nom']);
        }
        return $types;
    }
    
    
}
