<?php
require_once __DIR__ . '/../model/FeedbackModel.php';

class FeedbackController {
    private $db;
    private $feedbackModel;
    private $isAdmin;

    public function __construct($db, $isAdmin = false) {
        $this->db = $db;
        $this->feedbackModel = new FeedbackModel($this->db);
        $this->isAdmin = $isAdmin;
    }
    /**
     * Main request handler
     */
    public function handleRequest() {
        if ($this->isAdmin) {
            $this->handleAdminRequest();
        } else {
            $this->handleFrontendRequest();
        }
    }
    // FRONT-END OPERATIONS
    
    
    /**
     * Handle front-end requests (display, form, submission)
     */
    private function handleFrontendRequest() {
        $action = $_GET['action'] ?? 'display';
        
        switch ($action) {
            case 'submit':
                $this->submitFeedback();
                break;
            case 'form':
                $this->showFeedbackForm();
                break;
            
            case 'display':
            default:
                $this->displayFeedback();
                break;
        }
    }
    


    /**
     * Display feedback page with pagination
     */
    private function displayFeedback() {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 5;
        $offset = ($page - 1) * $perPage;

        $viewData = [
            'feedbacks' => $this->feedbackModel->getPublicFeedback($perPage, $offset),
            'featuredFeedback' => $this->feedbackModel->getFeaturedFeedback(3),
            'totalCount' => $this->feedbackModel->getTotalPublicCount(),
            'averageRating' => $this->feedbackModel->getAverageRating(),
            'perPage' => $perPage,
            'currentPage' => $page
        ];

        include __DIR__ . '/../view/front/feedback_display.php';
    }
    /**
     * Show feedback form
     */
    private function showFeedbackForm() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        include __DIR__ . '/../view/front/avis_form.php';
    }


    /**
     * Process feedback submission
     */
    public function submitFeedback() {
        // Validate CSRF token
        if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['feedback_message'] = [
                'type' => 'danger',
                'text' => 'Security validation failed. Please try again.'
            ];
            header('Location: index.php?action=form');
            exit;
        }

        // Validate inputs
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
            'message' => trim($_POST['message'] ?? ''),
            'rating' => filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 1, 'max_range' => 5]
            ])
        ];

        // Check validation
        if (empty($data['name']) || empty($data['message']) || !$data['rating']) {
            $_SESSION['feedback_message'] = [
                'type' => 'danger',
                'text' => 'Please fill all required fields correctly.'
            ];
            header('Location: index.php?action=form');
            exit;
        }


        // Save feedback
        if ($this->feedbackModel->saveFeedback($data)) {
            $_SESSION['feedback_message'] = [
                'type' => 'success',
                'text' => 'Thank you for your feedback! It will be reviewed soon.'
            ];
            header('Location: index.php');
        } else {
            $_SESSION['feedback_message'] = [
                'type' => 'danger',
                'text' => 'An error occurred. Please try again later.'
            ];
            header('Location: index.php?action=form');
        }
        exit;
    }
}
        
        /*// Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }*/
    
    
    
    
    
    
    /**
     * Show all feedback on the front-end
     */
    private function displayFeedback() {
        // Get page number if set
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 5;
        $offset = ($page - 1) * $perPage;
         // Get all required data for the view
    $viewData = [
        'feedbacks' => $this->FeedbackModel->getPublicFeedback($perPage, $offset),
        'featuredFeedback' => $this->FeedbackModel->getFeaturedFeedback(6),
        'totalCount' => $this->FeedbackModel->getTotalPublicCount(),
        'averageRating' => $this->FeedbackModel->getAverageRating(),
        'perPage' => $perPage,
        'currentPage' => $page
    ];
    
    // Include the view template
    include __DIR__ . '/../view/front/feedback_display.php';
}
        
        /*// Get public feedback with pagination
        $feedbacks = $this->FeedbackModel->getPublicFeedback($perPage, $offset);
        $totalCount = $this->FeedbackModel->getTotalPublicCount();
        $averageRating = $this->FeedbackModel->getAverageRating();
        
        // Get featured feedback for testimonials slider
        $featuredFeedback = $this->FeedbackModel->getFeaturedFeedback(6);
        
        
        
        
            
        
        // Prepare view data
        $viewData = [
            'feedbacks' => $feedbacks,
            'featuredFeedback' => $featuredFeedback,
            
            'totalCount' => $totalCount,
            'perPage' => $perPage,
            'currentPage' => $page,
            'averageRating' => $averageRating
        ];
        
        // Include the view
        include __DIR__ . '/../view/front/feedback_display.php';
    }*/
    
    /**
     * Show the general feedback form
     */
    private function showFeedbackForm() {
        // Get any message from session if set
        $message = null;
        if (isset($_SESSION['feedback_message'])) {
            $message = $_SESSION['feedback_message'];
            unset($_SESSION['feedback_message']);
        }
        
        
        
        // Include the view
        include __DIR__ . '/../view/front/avis_form.php';
    }
    
    
        
    /**
     * Submit feedback from front-end form
     */
    private function submitFeedback() {
        // Check if form was submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate and sanitize inputs
            $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
            $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING);
            $commentaire = filter_input(INPUT_POST, 'commentaire', FILTER_SANITIZE_STRING);
            $note = filter_input(INPUT_POST, 'note', FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 1, 'max_range' => 5]
            ]);
            
            
            // Check for required fields
            if (empty($nom) || empty($prenom) || empty($commentaire) || $note === false) {
                // Set error message and redirect back to form
                $_SESSION['feedback_message'] = [
                    'type' => 'danger',
                    'text' => 'Please fill out all required fields with valid information.'
                ];
                
                $redirectUrl = 'index.php?action=form';
               
                
                header('Location: ' . $redirectUrl);
                exit;
            }
            
            // Process the feedback
            $email = 'anonymous@example.com'; // Default email since we don't collect it
            
            
            // Combine first and last name
            $name = $prenom . ' ' . $nom;
            
            // Save to database
            if ($this->FeedbackModel->saveFeedback($name, $email, $subject, $commentaire, $note, )) {
                // Set success message and redirect to display page
                $_SESSION['feedback_message'] = [
                    'type' => 'success',
                    'text' => 'Thank you for your feedback!'
                ];
                header('Location: index.php');
                exit;
            } else {
                // Set error message and redirect back to form
                $_SESSION['feedback_message'] = [
                    'type' => 'danger',
                    'text' => 'There was a problem submitting your feedback. Please try again.'
                ];
                
                $redirectUrl = 'index.php?action=form';
                
                
                header('Location: ' . $redirectUrl);
                exit;
            }
        } else {
            // If not a POST request, redirect to the form
            header('Location: index.php?action=form');
            exit;
        }
    }

    //-------------------------------------------------------------------------------
    // DASHBOARD OPERATIONS
    //-------------------------------------------------------------------------------
    
    /**
     * Dashboard: List all feedback for administration
     */
    private function listAllFeedback() {
        // Get page number and filters if set
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        
        // Get filter parameters
        $filters = [
            'status' => $_GET['status'] ?? '',
            'featured' => isset($_GET['featured']) ? (int)$_GET['featured'] : null,
            
            'search' => $_GET['search'] ?? ''
        ];
        
        // Get all feedback with filters
        $feedbacks = $this->FeedbackModel->getAllFeedback($perPage, $offset, $filters);
        $totalCount = $this->FeedbackModel->getTotalCount($filters);
        
        // Get statistics for dashboard
        $statistics = $this->getStatistics();
        
        
        
        // Prepare view data
        $viewData = [
            'feedbacks' => $feedbacks,
            
            'totalCount' => $totalCount,
            'perPage' => $perPage,
            'currentPage' => $page,
            'filters' => $filters,
            'statistics' => $statistics
        ];
        
        // Include the admin view
        include __DIR__ . '/../view/back/feedback_table.php';
    }
    
    /**
     * Dashboard: View details of a specific feedback
     */
    private function viewFeedbackDetails() {
        // Get feedback ID
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($id <= 0) {
            $this->setAdminMessage('Invalid feedback ID', 'danger');
            header('Location: dashboard.php?route=feedback/admin');
            exit;
        }
        
        // Get feedback details
        $feedback = $this->FeedbackModel->getFeedbackById($id);
        
        if (!$feedback) {
            $this->setAdminMessage('Feedback not found', 'danger');
            header('Location: dashboard.php?route=feedback/admin');
            exit;
        }
        
       
        
        // Admin response update
        if (isset($data['admin_response'])) {
            $updates[] = "admin_response = ?";
            $params[] = $data['admin_response'];
            $types .= 's';
            
            // Add admin response metadata if available
            if (isset($data['admin_response_by'])) {
                $updates[] = "admin_response_by = ?";
                $params[] = $data['admin_response_by'];
                $types .= 'i';
            }
            
            $updates[] = "admin_response_date = NOW()";
        }
        
        // If no updates, return
        if (empty($updates)) {
            return false;
        }
        
        // Complete the query
        $query .= implode(', ', $updates);
        $query .= " WHERE id = ?";
        $params[] = $id;
        $types .= 'i';
        
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            return false;
        }
        
        $stmt->bind_param($types, ...$params);
        return $stmt->execute();
    }
    
    
    /**
     * Delete feedback
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM feedbacks WHERE id = ?");
        if (!$stmt) {
            return false;
        }
        
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    /**
     * Get feedback statistics
     */
    public function getStatistics() {
        $stats = [
            'total' => 0,
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
            'avgRating' => 0,
            'recent' => []
        ];
        
        // Get counts by status
        $statusQuery = "SELECT status, COUNT(*) as count FROM feedbacks GROUP BY status";
        $result = $this->db->query($statusQuery);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $stats[$row['status']] = (int)$row['count'];
                $stats['total'] += (int)$row['count'];
            }
        }
        
        // Get average rating
        $ratingQuery = "SELECT AVG(rating) as avg_rating FROM feedbacks";
        $result = $this->db->query($ratingQuery);
        if ($result && $row = $result->fetch_assoc()) {
            $stats['avgRating'] = round($row['avg_rating'], 1);
        }
        
        // Get recent feedback
        $recentQuery = "SELECT id, name, subject, rating, created_at, status FROM feedbacks ORDER BY created_at DESC LIMIT 5";
        $result = $this->db->query($recentQuery);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $stats['recent'][] = $row;
            }
        }
        
        return $stats;
    }
    
    /**
     * Bulk action on multiple feedback items
     */
    public function bulkAction($ids, $action) {
        if (empty($ids)) {
            return false;
        }
        
        $validActions = ['approve', 'reject', 'delete', 'public', 'private', 'feature', 'unfeature'];
        if (!in_array($action, $validActions)) {
            return false;
        }
        
        // Create placeholders for SQL IN clause
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        $types = str_repeat('i', count($ids));
        
        switch ($action) {
            case 'approve':
                $query = "UPDATE feedbacks SET status = 'approved' WHERE id IN ($placeholders)";
                break;
                
            case 'reject':
                $query = "UPDATE feedbacks SET status = 'rejected' WHERE id IN ($placeholders)";
                break;
                
            case 'delete':
                $query = "DELETE FROM feedbacks WHERE id IN ($placeholders)";
                break;
                
            case 'public':
                $query = "UPDATE feedbacks SET is_public = 1 WHERE id IN ($placeholders)";
                break;
                
            case 'private':
                $query = "UPDATE feedbacks SET is_public = 0 WHERE id IN ($placeholders)";
                break;
                
            case 'feature':
                $query = "UPDATE feedbacks SET featured = 1 WHERE id IN ($placeholders)";
                break;
                
            case 'unfeature':
                $query = "UPDATE feedbacks SET featured = 0 WHERE id IN ($placeholders)";
                break;
        }
        
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            return false;
        }
        
        $stmt->bind_param($types, ...$ids);
        return $stmt->execute();
    }
    
    
    
    /**
     * Handle errors
     */
    private function handleError($message) {
        error_log("FeedbackController Error: " . $message);
        // Add flash message if session is available
        if (isset($_SESSION)) {
            $_SESSION['error_message'] = $message;
        }
    }
}
?>
