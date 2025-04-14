<?php
// Bootstrap file to display feedback on the frontend

// Include the controller
require_once '../config/database.php';
require_once '../app/controller/FeedbackController.php';

// Initialize the controller
$database = new Database();
$db = $database->getConnection();
$controller = new FeedbackController($db);

// Display feedbacks
$controller->displayFeedback();
