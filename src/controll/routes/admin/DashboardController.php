<?php
class DashboardController
{
    public function index()
    {
        require_once __DIR__ . '/../../../../view/admin/pages/dashboard.php';
    }

    protected function renderView($viewPath, $data = [])
    {
        // Get the absolute path to project root
        $projectRoot = realpath(__DIR__ . '/../../../../');
        
        // Set paths for views
        $viewsDir = $projectRoot . '/view/';
        
        // Extract variables
        extract($data);
        
        // Start output buffering
        ob_start();
        
        // Include the view file
        require $viewsDir . $viewPath . '.php';
        $content = ob_get_clean();
        
        // Include the layout
        require $viewsDir . 'admin/layout.php';
    }
}