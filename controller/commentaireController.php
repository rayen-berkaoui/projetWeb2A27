<?php
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $pdo = Database::getInstance()->getConnection();
    
    try {
        switch ($action) {
            case 'supprimer':
                if (!isset($_POST['commentaire_id'])) {
                    throw new Exception('ID du commentaire manquant');
                }
                $stmt = $pdo->prepare("DELETE FROM commentaires WHERE commentaire_id = ?");
                $result = $stmt->execute([$_POST['commentaire_id']]);
                echo $result ? 'OK' : 'ERROR';
                break;

            case 'toggle_visibility':
                if (!isset($_POST['commentaire_id'])) {
                    throw new Exception('ID du commentaire manquant');
                }
                $stmt = $pdo->prepare("SELECT is_visible FROM commentaires WHERE commentaire_id = ?");
                $stmt->execute([$_POST['commentaire_id']]);
                $currentState = $stmt->fetchColumn();
                if ($currentState === false) {
                    throw new Exception('Commentaire non trouvé');
                }
                $newState = !$currentState;
                $stmt = $pdo->prepare("UPDATE commentaires SET is_visible = ? WHERE commentaire_id = ?");
                $stmt->execute([$newState, $_POST['commentaire_id']]);
                echo json_encode([
                    'success' => true,
                    'newState' => $newState,
                    'message' => $newState ? 'Commentaire rendu visible' : 'Commentaire masqué'
                ]);
                break;

            default:
                echo json_encode(['success' => false, 'message' => 'Action invalide']);
                break;
        }
    } catch (Exception $e) {
        error_log("Erreur dans commentaireController.php ($action) : " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    $pdo = Database::getInstance()->getConnection();
    
    try {
        switch ($action) {
            case 'refresh':
                $stmt = $pdo->prepare("
                    SELECT c.*, a.nom as auteur_avis
                    FROM commentaires c
                    INNER JOIN avis a ON c.avis_id = a.avis_id
                    ORDER BY c.date_creation DESC
                ");
                $stmt->execute();
                $commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $html = '';
                if (empty($commentaires)) {
                    $html = '<tr><td colspan="7">Aucun commentaire trouvé.</td></tr>';
                } else {
                    foreach ($commentaires as $commentaire) {
                        $html .= '
                            <tr data-comment-id="' . $commentaire['commentaire_id'] . '">
                                <td>' . $commentaire['commentaire_id'] . '</td>
                                <td>' . $commentaire['avis_id'] . '</td>
                                <td>' . htmlspecialchars($commentaire['auteur_avis']) . '</td>
                                <td>
                                    ' . htmlspecialchars($commentaire['contenu']) . '
                                    ' . ($commentaire['signale'] > 0 ? '<strong style="color:red;">[Signalé]</strong>' : '') . '
                                </td>
                                <td>' . date('d/m/Y H:i', strtotime($commentaire['date_creation'])) . '</td>
                                <td>
                                    <span class="status-badge ' . ($commentaire['is_visible'] ? 'visible' : 'hidden') . '">
                                        ' . ($commentaire['is_visible'] ? 'Visible' : 'Masqué') . '
                                    </span>
                                </td>
                                <td class="actions">
                                    <button class="btn-visibility" onclick="toggleCommentVisibility(' . $commentaire['commentaire_id'] . ', ' . $commentaire['is_visible'] . ')">
                                        <i class="fas ' . ($commentaire['is_visible'] ? 'fa-eye-slash' : 'fa-eye') . '"></i>
                                        ' . ($commentaire['is_visible'] ? 'Masquer' : 'Afficher') . '
                                    </button>
                                    <button class="btn-delete" onclick="supprimerCommentaire(' . $commentaire['commentaire_id'] . ')">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </td>
                            </tr>';
                    }
                }
                echo json_encode(['success' => true, 'data' => $html]);
                break;

            default:
                echo json_encode(['success' => false, 'message' => 'Action invalide']);
                break;
        }
    } catch (Exception $e) {
        error_log("Erreur dans commentaireController.php (GET $action) : " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>