<?php
class DashboardController
{
    public function index()
    {
        $data = [
            'page_title' => 'Dashboard Overview',
            'active_menu' => 'dashboard',
            'stats' => [
                'total_users' => 1245,
                'active_projects' => 28,
                'pending_tasks' => 14,
                'revenue' => 28450
            ]
        ];

        $this->renderView('admin/pages/dashboard', $data);
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