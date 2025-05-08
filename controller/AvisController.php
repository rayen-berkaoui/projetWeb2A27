<?php
class AvisController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // =========================
    // Avis Methods
    // =========================

    public function indexAvis() {
        // Fetch all avis records
        $stmt = $this->db->query("SELECT * FROM avis ORDER BY date_creation DESC");
        $avisList = $stmt->fetchAll(PDO::FETCH_ASSOC); // Ensure avisList is fetched from DB
    
        // Now, pass the $avisList to the view
        include 'C:\xampp\htdocs\2A27\view\admin\pages\avis\list.php';
    }

    public function createAvis() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Assuming 'contenu' and 'note' are the fields for creating an avis
            $contenu = $_POST['contenu'];  // Assuming 'contenu' is the content field
            $note = $_POST['note'];        // If note is required
            $date_creation = $_POST['date_creation']; // Ensure this matches your form field name

            $stmt = $this->db->prepare("INSERT INTO avis (contenu, note, date_creation) VALUES (?, ?, ?)");
            $stmt->execute([$contenu, $note, $date_creation]);

            header("Location: /2A27/admin/avis");
            exit;
        }

        include 'C:\xampp\htdocs\2A27\view\admin\pages\avis\create.php';
    }

    public function editAvis($id) {
        // Fetch the avis by its ID
        $stmt = $this->db->prepare("SELECT * FROM avis WHERE avis_id = ?");
        $stmt->execute([$id]);
        $avis = $stmt->fetch(PDO::FETCH_ASSOC);

        include 'C:\xampp\htdocs\2A27\view\admin\pages\avis\edit.php';
    }

    public function updateAvis($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contenu = $_POST['contenu'];
            $note = $_POST['note'];
            $date_creation = $_POST['date_creation'];

            $stmt = $this->db->prepare("UPDATE avis SET contenu = ?, note = ?, date_creation = ? WHERE avis_id = ?");
            $stmt->execute([$contenu, $note, $date_creation, $id]);

            header("Location: /2A27/admin/avis");
            exit;
        }
    }

    public function deleteAvis($id) {
        $stmt = $this->db->prepare("DELETE FROM avis WHERE avis_id = ?");
        $stmt->execute([$id]);

        header("Location: /2A27/admin/avis");
        exit;
    }

    // =========================
    // Commentaire Methods
    // =========================

    public function indexCommentaires() {
        // Query to fetch commentaires and join with avis
        $query = "SELECT commentaires.*, avis.contenu AS avis_contenu FROM commentaires 
                  JOIN avis ON commentaires.avis_id = avis.avis_id 
                  ORDER BY commentaires.date_creation DESC";
        
        // Execute the query
        $stmt = $this->db->query($query);
        
        // Fetch the data as an associative array
        $commentairesList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Pass the data to the view
        include 'C:\xampp\htdocs\2A27\view\admin\pages\commentaires\list.php';
    }

    public function createCommentaire() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contenu = $_POST['contenu'];
            $auteur = $_POST['auteur'];
            $date_creation = $_POST['date_creation']; // Correct field name for the creation date
            $avis_id = $_POST['avis_id'];  // Correct field name for the avis reference

            $stmt = $this->db->prepare("INSERT INTO commentaires (contenu, auteur, date_creation, avis_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$contenu, $auteur, $date_creation, $avis_id]);

            header("Location: /2A27/admin/commentaires");
            exit;
        }

        // Fetch list of avis for the comment form
        $stmt = $this->db->query("SELECT avis_id, contenu FROM avis");
        $avisList = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'C:\xampp\htdocs\2A27\view\admin\pages\commentaires\create.php';
    }

    public function editCommentaire($id) {
        // Fetch the comment by its ID
        $stmt = $this->db->prepare("SELECT * FROM commentaires WHERE commentaire_id = ?");
        $stmt->execute([$id]);
        $commentaire = $stmt->fetch(PDO::FETCH_ASSOC);

        // Fetch the list of avis for the dropdown or selection in the edit form
        $avisList = $this->db->query("SELECT avis_id, contenu FROM avis")->fetchAll(PDO::FETCH_ASSOC);

        include 'C:\xampp\htdocs\2A27\view\admin\pages\commentaires\edit.php';
    }

    public function updateCommentaire($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contenu = $_POST['contenu'];
            $auteur = $_POST['auteur'];
            $date_creation = $_POST['date_creation'];
            $avis_id = $_POST['avis_id'];

            $stmt = $this->db->prepare("UPDATE commentaires SET contenu = ?, auteur = ?, date_creation = ?, avis_id = ? WHERE commentaire_id = ?");
            $stmt->execute([$contenu, $auteur, $date_creation, $avis_id, $id]);

            header("Location: /2A27/admin/commentaires");
            exit;
        }
    }

    public function deleteCommentaire($id) {
        $stmt = $this->db->prepare("DELETE FROM commentaires WHERE commentaire_id = ?");
        $stmt->execute([$id]);

        header("Location: /2A27/admin/commentaires");
        exit;
    }
}
?>
