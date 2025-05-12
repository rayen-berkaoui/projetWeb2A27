<?php
require_once __DIR__ . '/../../process/database.php';
require_once __DIR__ . '/../../../domain/AvisModel.php';

class AvisController {
    private $model;

    public function __construct() {
        $this->model = new AvisModel();
    }

    public function getAvis($criteria = null) {
        return $this->model->getAvis($criteria);
    }

    public function toggleVisibility($id, $state) {
        return $this->model->toggleVisibility($id, $state);
    }

    public function updateAvis($id, $data) {
        return $this->model->updateAvis($id, $data);
    }

    public function deleteAvis($id) {
        return $this->model->deleteAvis($id);
    }

    public function signalAvis($id) {
        return $this->model->signalAvis($id);
    }
}

// Traitement des requêtes AJAX
$pdo = Database::getInstance()->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    try {
        switch ($action) {
            case 'ajouter':
                // Validation côté serveur
                $nom = trim(isset($_POST['nom']) ? $_POST['nom'] : '');
                $prenom = trim(isset($_POST['prenom']) ? $_POST['prenom'] : '');
                $email = trim(isset($_POST['email']) ? $_POST['email'] : '');
                $contenu = trim(isset($_POST['contenu']) ? $_POST['contenu'] : '');
                $note = isset($_POST['note']) ? (int)$_POST['note'] : 0;

                if (empty($nom) || strlen($nom) < 2) {
                    throw new Exception("Le nom doit contenir au moins 2 caractères");
                }
                if (empty($prenom) || strlen($prenom) < 2) {
                    throw new Exception("Le prénom doit contenir au moins 2 caractères");
                }
                if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("L'email n'est pas valide");
                }
                if (empty($contenu) || strlen($contenu) < 5) {
                    throw new Exception("Le contenu doit contenir au moins 5 caractères");
                }
                if ($note < 1 || $note > 5) {
                    throw new Exception("La note doit être comprise entre 1 et 5");
                }

                $stmt = $pdo->prepare("
                    INSERT INTO avis (nom, prenom, email, contenu, note, is_visible, date_creation) 
                    VALUES (?, ?, ?, ?, ?, 1, NOW())
                ");
                $result = $stmt->execute([
                    $nom,
                    $prenom,
                    $email,
                    $contenu,
                    $note
                ]);
                echo json_encode(['success' => $result, 'message' => $result ? 'Avis ajouté avec succès' : 'Erreur lors de l\'ajout']);
                break;

            case 'toggle_visibility':
                try {
                    if (!isset($_POST['avis_id'])) {
                        throw new Exception('ID de l\'avis manquant');
                    }

                    $avisId = (int)$_POST['avis_id'];
                    
                    // Begin transaction
                    $pdo->beginTransaction();
                    
                    // Get current state first
                    $stmt = $pdo->prepare("SELECT is_visible FROM avis WHERE avis_id = ?");
                    if (!$stmt->execute([$avisId])) {
                        throw new Exception('Erreur lors de la lecture du statut');
                    }
                    
                    $currentState = $stmt->fetchColumn();
                    if ($currentState === false) {
                        throw new Exception('Avis non trouvé');
                    }
                    
                    // Toggle visibility
                    $newState = !$currentState;
                    
                    // Update visibility
                    $stmt = $pdo->prepare("UPDATE avis SET is_visible = ? WHERE avis_id = ?");
                    if (!$stmt->execute([$newState, $avisId])) {
                        throw new Exception('Erreur lors de la mise à jour');
                    }
                    
                    // Commit the transaction
                    $pdo->commit();
                    
                    echo json_encode([
                        'success' => true,
                        'newState' => $newState,
                        'message' => $newState ? 'Avis rendu visible' : 'Avis masqué'
                    ]);
                    
                } catch (Exception $e) {
                    // Rollback on error
                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                    
                    error_log("Error in toggle_visibility: " . $e->getMessage());
                    
                    echo json_encode([
                        'success' => false,
                        'message' => $e->getMessage()
                    ]);
                }
                break;

            case 'modifier':
                $controller = new AvisController();
                $result = $controller->updateAvis(
                    $_POST['avis_id'],
                    $_POST
                );
                echo $result ? 'OK' : 'ERROR';
                break;
                
            case 'supprimer':
                $controller = new AvisController();
                echo $controller->deleteAvis($_POST['avis_id']) ? 'OK' : 'ERROR';
                break;
                
            case 'signaler':
                $controller = new AvisController();
                echo $controller->signalAvis($_POST['avis_id']) ? 'OK' : 'ERROR';
                break;

            case 'like':
                if (!isset($_POST['avis_id'])) {
                    throw new Exception('ID de l\'avis manquant');
                }
                
                $stmt = $pdo->prepare("
                    UPDATE avis 
                    SET likes = likes + 1 
                    WHERE avis_id = ?
                ");
                
                if ($stmt->execute([$_POST['avis_id']])) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Like ajouté'
                    ]);
                } else {
                    throw new Exception('Erreur lors de l\'ajout du like');
                }
                break;

            case 'dislike':
                if (!isset($_POST['avis_id'])) {
                    throw new Exception('ID de l\'avis manquant');
                }
                
                $stmt = $pdo->prepare("
                    UPDATE avis 
                    SET dislikes = dislikes + 1 
                    WHERE avis_id = ?
                ");
                
                if ($stmt->execute([$_POST['avis_id']])) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Dislike ajouté'
                    ]);
                } else {
                    throw new Exception('Erreur lors de l\'ajout du dislike');
                }
                break;

            case 'repondre':
                if (!isset($_POST['avis_id'], $_POST['nom'], $_POST['prenom'], $_POST['contenu'])) {
                    throw new Exception('Données manquantes');
                }
                
                $stmt = $pdo->prepare("
                    INSERT INTO commentaires (avis_id, nom, prenom, contenu, is_visible, date_creation) 
                    VALUES (?, ?, ?, ?, 1, NOW())
                ");
                
                if ($stmt->execute([
                    $_POST['avis_id'],
                    $_POST['nom'],
                    $_POST['prenom'],
                    $_POST['contenu']
                ])) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Réponse ajoutée'
                    ]);
                } else {
                    throw new Exception('Erreur lors de l\'ajout de la réponse');
                }
                break;

            default:
                echo json_encode(['success' => false, 'message' => 'Action invalide']);
                break;
        }
    } catch (Exception $e) {
        error_log("Erreur dans avisController.php ($action) : " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    
    try {
        switch ($action) {
            case 'sort':
                $controller = new AvisController();
                $avis = $controller->getAvis($_GET['criteria']);
                $partialPath = __DIR__ . '/../view/back/partials/avis_table_body.php';
                if (file_exists($partialPath)) {
                    include $partialPath;
                } else {
                    echo "Erreur: Template non trouvé";
                }
                break;

            case 'refresh':
                $stmt = $pdo->query("
                    SELECT a.*, COUNT(c.commentaire_id) as nb_commentaires 
                    FROM avis a 
                    LEFT JOIN commentaires c ON a.avis_id = c.avis_id 
                    GROUP BY a.avis_id 
                    ORDER BY a.date_creation DESC
                ");
                $avis = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode(['success' => true, 'data' => $avis]);
                break;

            case 'get_note_stats':
                $stmt = $pdo->prepare("
                    SELECT note, COUNT(*) as count
                    FROM avis
                    GROUP BY note
                    ORDER BY note
                ");
                $stmt->execute();
                $noteStats = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $data = [0, 0, 0, 0, 0];
                foreach ($noteStats as $stat) {
                    if ($stat['note'] >= 1 && $stat['note'] <= 5) {
                        $data[$stat['note'] - 1] = $stat['count'];
                    }
                }
                echo json_encode(['success' => true, 'data' => $data]);
                break;

            default:
                echo json_encode(['success' => false, 'message' => 'Action invalide']);
                break;
        }
    } catch (Exception $e) {
        error_log("Erreur dans avisController.php (GET $action) : " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>